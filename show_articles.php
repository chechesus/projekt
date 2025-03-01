<?php
require_once 'api/session.php';

$articleId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($articleId <= 0) {
    die("Neplatné ID článku.");
}


// 2) Načítame základné údaje o článku z tabuľky `articles`
$sqlA = "SELECT id, title, created_at, scheduled_date, scheduled_time, user_id
         FROM articles.articles
         WHERE id = ?";
$stmtA = $conn->prepare($sqlA);
$stmtA->bind_param("i", $articleId);
$stmtA->execute();
$resultA = $stmtA->get_result();
if ($resultA->num_rows < 1) {
    die("Článok s ID $articleId neexistuje.");
}
$articleData = $resultA->fetch_assoc();
$stmtA->close();

// 3) Načítame z tabuľky `composer` všetky prvky patriace k článku, zoradené podľa order_id
$sqlC = "SELECT element_type, element_id, order_id
         FROM articles.composer
         WHERE article_id = ?
         ORDER BY order_id ASC";
$stmtC = $conn->prepare($sqlC);
$stmtC->bind_param("i", $articleId);
$stmtC->execute();
$elementsResult = $stmtC->get_result();
$elements = $elementsResult->fetch_all(MYSQLI_ASSOC);
$stmtC->close();

// 4) Vytvoríme HTML výstup pre článok
$output  = "<h1>" . htmlspecialchars($articleData['title']) . "</h1>\n";
$output .= "<p><em>Publikované: " . $articleData['created_at'] . "</em></p>\n";

if (!empty($articleData['scheduled_date']) || !empty($articleData['scheduled_time'])) {
    $output .= "<p><em>Naplánované publikovanie: " . $articleData['scheduled_date'] . " " . $articleData['scheduled_time'] . "</em></p>\n";
}

// 5) Prejdeme všetky prvky v composer a načítame ich obsah
foreach ($elements as $elem) {
    $type      = $elem['element_type'];  // napr. 'paragraph', 'poll', 'image_box'
    $elementId = $elem['element_id'];

    switch ($type) {
        case 'paragraph':
            $stmtP = $conn->prepare("SELECT content, html_content FROM articles.paragraphs WHERE id = ?");
            $stmtP->bind_param("i", $elementId);
            $stmtP->execute();
            $resP = $stmtP->get_result();
            if ($rowP = $resP->fetch_assoc()) {
                $paragraphHtml = $rowP['html_content'];
                $output .= "<div class='paragraph'>\n" . $paragraphHtml . "\n</div>\n";
            }
            $stmtP->close();
            break;

        case 'poll':
            $stmtPoll = $conn->prepare("SELECT question FROM articles.polls WHERE id = ?");
            $stmtPoll->bind_param("i", $elementId);
            $stmtPoll->execute();
            $resPoll = $stmtPoll->get_result();
            if ($rowPoll = $resPoll->fetch_assoc()) {
                $output .= "<div class='poll'>\n";
                $output .= "<strong>Anketa:</strong> " . htmlspecialchars($rowPoll['question']) . "<br>\n";
                $stmtOpt = $conn->prepare("SELECT option_text FROM articles.poll_options WHERE poll_id = ?");
                $stmtOpt->bind_param("i", $elementId);
                $stmtOpt->execute();
                $resOpt = $stmtOpt->get_result();
                while ($rowOpt = $resOpt->fetch_assoc()) {
                    $optText = htmlspecialchars($rowOpt['option_text']);
                    $output .= "<label style='margin-left:10px;'><input type='checkbox' disabled> $optText</label><br>\n";
                }
                $stmtOpt->close();
                $output .= "</div>\n";
            }
            $stmtPoll->close();
            break;

        case 'image_box':
            $stmtI = $conn->prepare("SELECT image_url, caption FROM articles.image_boxes WHERE id = ?");
            $stmtI->bind_param("i", $elementId);
            $stmtI->execute();
            $resI = $stmtI->get_result();
            if ($rowI = $resI->fetch_assoc()) {
                $url     = htmlspecialchars($rowI['image_url']);
                $caption = htmlspecialchars($rowI['caption']);
                $output .= "<div class='image-box'>\n";
                $output .= "  <img src='$url' alt='$caption' style='max-width:300px;'><br>\n";
                $output .= "  <em>$caption</em>\n";
                $output .= "</div>\n";
            }
            $stmtI->close();
            break;

        default:
            // Neznámy typ – môžeme preskočiť alebo vypísať varovanie
            break;
    }
}
$conn->close();

require 'inc/header.php';
?>

<body>
    <div class="grid-container">
        <?php require_once 'website_elements/menu.php'; ?>
    </div>

    <div class="text">
        <?= $output ?>
    </div>

    <!-- Komentárová sekcia -->
    <main class="app-main">
        <div class="container my-4">
            <h2>Komentáre</h2>
            <!-- Kontajner pre timeline komentáre -->
            <div id="comments-timeline" class="timeline">
                <!-- Dynamicky generované komentáre budú vložené sem -->
            </div>

            <!-- Formulár pre odoslanie nového komentára -->
            <!-- Formulár pre odoslanie nového komentára -->
            <div class="mt-4">
                <?php if (isset($_SESSION['role_id']) && $_SESSION['role_id'] !== 'guest'): ?>
                    <form id="commentForm">
                        <div class="mb-3">
                            <textarea id="commentText" class="form-control" rows="3" placeholder="Napíš komentár..."></textarea>
                        </div>
                        <!-- Hodnota fk_user_ID sa načíta zo session -->
                        <input type="hidden" id="fk_user_ID" value="<?= htmlspecialchars($_SESSION['userid']); ?>">
                        <!-- Skrytý input pre article_id -->
                        <input type="hidden" id="articleId" value="<?= $articleId; ?>">
                        <button type="submit" class="btn btn-primary">Odoslať</button>
                    </form>
                <?php else: ?>
                    <p>Pre pridanie komentára sa prihlás, alebo zaregistruj. (Hostia nemajú možnosť komentovať.)</p>
                <?php endif; ?>
            </div>

        </div>
    </main>

    <script>
        // Funkcia načítania komentárov z backendu a vybudovanie stromovej štruktúry
        function loadComments() {
            console.log("Načítavam komentáre...");
            fetch('comments.php?article_id=' + articleId)
                .then(response => response.json())
                .then(data => {
                    console.log("Komentáre načítané:", data);
                    const commentTree = buildCommentTree(data);
                    const timeline = document.getElementById('comments-timeline');
                    timeline.innerHTML = '';
                    commentTree.forEach(comment => {
                        timeline.innerHTML += renderComment(comment);
                    });
                })
                .catch(error => console.error('Chyba pri načítaní komentárov:', error));
        }

        // Funkcia na vytvorenie stromovej štruktúry z plochej odpovede
        function buildCommentTree(comments) {
            const commentMap = {};
            const roots = [];
            comments.forEach(comment => {
                // Inicializujeme pole pre vnorené komentáre
                comment.replies = [];
                commentMap[comment.comment_id] = comment;
            });
            comments.forEach(comment => {
                if (comment.parent_comment_id && commentMap[comment.parent_comment_id]) {
                    commentMap[comment.parent_comment_id].replies.push(comment);
                } else {
                    roots.push(comment);
                }
            });
            return roots;
        }

        // Funkcia na vykreslenie komentára s možnosťou zobrazenia hlasovacích tlačidiel a odpovedí
        function renderComment(comment) {
            let html = `
        <div class="timeline-item mb-4" data-comment-id="${comment.comment_id}">
          <div class="d-flex align-items-start">
            <a href="show_profile.php?id=${comment.fk_user_ID}">
              <img src="${comment.user_picture}" alt="${comment.user_nick}" class="rounded-circle" style="width:50px; height:50px;">
            </a>
            <div class="ms-2">
              <h5 class="mb-1">
                <a href="show_profile.php?id=${comment.fk_user_ID}">${comment.user_nick}</a>
                <small class="text-muted ms-2"><i class="bi bi-clock-fill"></i> ${comment.created_at}</small>
              </h5>
              <p class="mb-0">${comment.comment_text}</p>
              <!-- Hlasovacie tlačidlá -->
              <div class="vote-buttons mt-2">
                <button class="btn btn-sm btn-outline-success" onclick="voteComment(${comment.comment_id}, 'like')">
                  Like (<span id="like-count-${comment.comment_id}">${comment.likes || 0}</span>)
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="voteComment(${comment.comment_id}, 'dislike')">
                  Dislike (<span id="dislike-count-${comment.comment_id}">${comment.dislikes || 0}</span>)
                </button>
              </div>
              <!-- Tlačidlo pre odpoveď -->
              <button class="reply-button btn btn-link p-0" onclick="toggleReplyForm(${comment.comment_id})">Reply</button>
              <div class="reply-form-container mt-2" id="reply-form-${comment.comment_id}" style="display: none;">
                <textarea class="form-control reply-text" rows="2" placeholder="Napíš odpoveď..."></textarea>
                <button class="btn btn-secondary btn-sm mt-1" onclick="submitReply(${comment.comment_id})">Odoslať odpoveď</button>
              </div>
      `;

            // Ak má komentár odpovede, pridáme tlačidlo na rozbalenie a kontajner pre replies
            if (comment.replies && comment.replies.length > 0) {
                html += `<button class="btn btn-link btn-sm" onclick="toggleReplies(${comment.comment_id})" id="toggle-replies-${comment.comment_id}">
                    Show Replies (${comment.replies.length})
                   </button>`;
                html += `<div class="replies ms-5 mt-3" id="replies-container-${comment.comment_id}" style="display:none;">`;
                comment.replies.forEach(reply => {
                    html += renderComment(reply);
                });
                html += `</div>`;
            }
            html += `</div></div>`;
            return html;
        }

        // Funkcia pre prepínanie zobrazenia formulára na odpoveď
        function toggleReplyForm(commentId) {
            const formContainer = document.getElementById('reply-form-' + commentId);
            formContainer.style.display = formContainer.style.display === 'none' ? 'block' : 'none';
        }

        // Funkcia pre odoslanie odpovede
        function submitReply(parentCommentId) {
            const formContainer = document.getElementById('reply-form-' + parentCommentId);
            const replyTextArea = formContainer.querySelector('.reply-text');
            const replyText = replyTextArea.value.trim();
            const userId = document.getElementById('fk_user_ID').value;

            if (!replyText) {
                alert("Prosím, napíš odpoveď.");
                return;
            }

            const formData = new FormData();
            formData.append('fk_user_ID', userId);
            formData.append('comment_text', replyText);
            formData.append('article_id', articleId);
            formData.append('parent_comment_id', parentCommentId);

            console.log("Odosielam odpoveď:", {
                fk_user_ID: userId,
                comment_text: replyText,
                article_id: articleId,
                parent_comment_id: parentCommentId
            });

            fetch('comments.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    console.log("Raw response:", text);
                    let result;
                    try {
                        result = JSON.parse(text);
                    } catch (e) {
                        console.error("Chyba pri parsovaní JSON:", e);
                        alert("Neplatná odpoveď zo servera: " + text);
                        return;
                    }
                    console.log("Výsledok odoslania odpovede:", result);
                    if (result.success) {
                        replyTextArea.value = '';
                        formContainer.style.display = 'none';
                        loadComments(); // Obnovíme zoznam komentárov
                    } else {
                        alert("Chyba pri odosielaní odpovede: " + (result.error || 'Neznáma chyba'));
                    }
                })
                .catch(error => {
                    console.error('Chyba pri odosielaní odpovede:', error);
                    alert("Chyba pri odosielaní odpovede: " + error);
                });
        }

        // Funkcia na prepínanie zobrazenia vnorených komentárov
        function toggleReplies(commentId) {
            const container = document.getElementById('replies-container-' + commentId);
            const toggleButton = document.getElementById('toggle-replies-' + commentId);
            if (container.style.display === 'none') {
                container.style.display = 'block';
                toggleButton.textContent = 'Hide Replies';
            } else {
                container.style.display = 'none';
                // Aktualizácia textu tlačidla, ak chcete zobraziť počet odpovedí
                toggleButton.textContent = 'Show Replies (' + container.childElementCount + ')';
            }
        }

        // Funkcia pre hlasovanie (like/dislike) - potrebuje vlastný backend endpoint (napr. vote_comment.php)
        function voteComment(commentId, voteType) {
            const formData = new FormData();
            formData.append('comment_id', commentId);
            formData.append('vote', voteType);

            fetch('vote_comment.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Aktualizácia počtov hlasov
                        document.getElementById('like-count-' + commentId).textContent = data.likes;
                        document.getElementById('dislike-count-' + commentId).textContent = data.dislikes;
                    } else {
                        alert("Chyba pri hlasovaní: " + data.error);
                    }
                })
                .catch(error => {
                    console.error("Chyba pri hlasovaní komentára:", error);
                });
        }

        // Načítame session user id a article id do premenných
        const sessionUserId = "<?= htmlspecialchars($_SESSION['userid']); ?>";
        const articleId = document.getElementById('articleId').value;

        // Spracovanie odoslania nového komentára
        document.getElementById('commentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const commentText = document.getElementById('commentText').value.trim();
            const userId = document.getElementById('fk_user_ID').value;

            if (!commentText) {
                alert("Prosím, napíš komentár.");
                return;
            }

            const formData = new FormData();
            formData.append('fk_user_ID', userId);
            formData.append('comment_text', commentText);
            formData.append('article_id', articleId);
            console.log("Odosielam komentár:", {
                fk_user_ID: userId,
                comment_text: commentText,
                article_id: articleId
            });

            fetch('comments.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(text => {
                    console.log("Raw response:", text);
                    let result;
                    try {
                        result = JSON.parse(text);
                    } catch (e) {
                        console.error("Chyba pri parsovaní JSON:", e);
                        alert("Neplatná odpoveď zo servera: " + text);
                        return;
                    }
                    console.log("Výsledok odoslania komentára:", result);
                    if (result.success) {
                        document.getElementById('commentText').value = '';
                        loadComments(); // Obnovíme zoznam komentárov
                    } else {
                        alert("Chyba pri odosielaní komentára: " + (result.error || 'Neznáma chyba'));
                    }
                })
                .catch(error => {
                    console.error('Chyba pri odosielaní komentára:', error);
                    alert("Chyba pri odosielaní komentára: " + error);
                });
        });

        // Načítame komentáre pri načítaní stránky
        document.addEventListener('DOMContentLoaded', loadComments);
    </script>


    <?php require_once 'website_elements/footer.php'; ?>
</body>

</html>