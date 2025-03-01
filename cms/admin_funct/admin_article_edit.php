<?php
require_once 'api/session.php';

$articleId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($articleId <= 0) {
    die("Neplatné ID článku.");
}

// Načítame základné údaje o článku
$sqlA = "SELECT id, title, scheduled_date, scheduled_time FROM articles.articles WHERE id = ?";
$stmtA = $conn->prepare($sqlA);
$stmtA->bind_param("i", $articleId);
$stmtA->execute();
$resultA = $stmtA->get_result();
if ($resultA->num_rows < 1) {
    die("Článok s ID $articleId neexistuje.");
}
$articleData = $resultA->fetch_assoc();
$stmtA->close();

// Načítame prvky článku zo spojovacej tabuľky composer
$sqlC = "SELECT element_type, element_id, order_id FROM articles.composer WHERE article_id = ? ORDER BY order_id ASC";
$stmtC = $conn->prepare($sqlC);
$stmtC->bind_param("i", $articleId);
$stmtC->execute();
$resultC = $stmtC->get_result();
$composerElements = $resultC->fetch_all(MYSQLI_ASSOC);
$stmtC->close();

// Pre každý element načítame detaily a vytvoríme pole $composerData
$composerData = [];
foreach ($composerElements as $elem) {
    $type = $elem['element_type'];
    $elementId = $elem['element_id'];
    $order = $elem['order_id'];
    $item = [
        'type' => $type,
        'order' => $order,
        'payload' => []
    ];
    
    switch ($type) {
        case 'paragraph':
            $stmtP = $conn->prepare("SELECT content FROM articles.paragraphs WHERE id = ?");
            $stmtP->bind_param("i", $elementId);
            $stmtP->execute();
            $resP = $stmtP->get_result();
            if ($rowP = $resP->fetch_assoc()) {
                $item['payload']['content'] = $rowP['content'];
            }
            $stmtP->close();
            break;
        case 'poll':
            $stmtPoll = $conn->prepare("SELECT question FROM articles.polls WHERE id = ?");
            $stmtPoll->bind_param("i", $elementId);
            $stmtPoll->execute();
            $resPoll = $stmtPoll->get_result();
            if ($rowPoll = $resPoll->fetch_assoc()) {
                $item['payload']['question'] = $rowPoll['question'];
            }
            $stmtPoll->close();
            
            // Načítame možnosti ankety
            $stmtOpt = $conn->prepare("SELECT option_text FROM articles.poll_options WHERE poll_id = ?");
            $stmtOpt->bind_param("i", $elementId);
            $stmtOpt->execute();
            $resOpt = $stmtOpt->get_result();
            $options = [];
            while ($rowOpt = $resOpt->fetch_assoc()) {
                $options[] = $rowOpt['option_text'];
            }
            $item['payload']['options'] = $options;
            $stmtOpt->close();
            break;
        case 'image_box':
            $stmtI = $conn->prepare("SELECT image_url, caption FROM articles.image_boxes WHERE id = ?");
            $stmtI->bind_param("i", $elementId);
            $stmtI->execute();
            $resI = $stmtI->get_result();
            if ($rowI = $resI->fetch_assoc()) {
                $item['payload']['url'] = $rowI['image_url'];
                $item['payload']['caption'] = $rowI['caption'];
            }
            $stmtI->close();
            break;
        default:
            // Neznámy typ – preskočíme
            continue 2;
    }
    $composerData[] = $item;
}

$conn->close();

// Pre jednoduchosť prevedieme $composerData do JSON, ktorý využijeme v JavaScripte pre predvyplnenie editora
$composerJson = json_encode($composerData);
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <title>Editácia článku - Admin</title>
    <link rel="icon" href="/projekt/images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .element-block { border: 1px solid #ccc; padding: 15px; margin-bottom: 15px; }
        .element-header { font-weight: bold; }
    </style>
</head>
<body>
    <div class="container my-4">
        <h1>Editácia článku</h1>
        <form id="edit-article-form" action="save_edited_article.php" method="post">
            <!-- Hidden pole pre article id -->
            <input type="hidden" name="article_id" value="<?= htmlspecialchars($articleData['id']); ?>">
            
            <div class="mb-3">
                <label for="title" class="form-label">Názov článku</label>
                <input type="text" name="title" id="title" class="form-control" value="<?= htmlspecialchars($articleData['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="scheduled_date" class="form-label">Dátum publikácie</label>
                <input type="date" name="scheduled_date" id="scheduled_date" class="form-control" value="<?= htmlspecialchars($articleData['scheduled_date']); ?>">
            </div>
            <div class="mb-3">
                <label for="scheduled_time" class="form-label">Čas publikácie</label>
                <input type="time" name="scheduled_time" id="scheduled_time" class="form-control" value="<?= htmlspecialchars($articleData['scheduled_time']); ?>">
            </div>
            
            <hr>
            <h3>Prvky článku</h3>
            <!-- Kontajner pre editovateľné prvky -->
            <div id="composer-container">
                <!-- Dynamicky generované bloky budú vytvorené cez JS -->
            </div>
            <!-- Skryté pole pre odoslanie JSON dát s prvkami -->
            <input type="hidden" name="composer_json" id="composer_json">
            
            <button type="submit" class="btn btn-primary">Uložiť zmeny</button>
        </form>
    </div>
    
    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Predvyplnené údaje z backendu
    const composerData = <?= $composerJson ?>;
    
    // Funkcia na vytvorenie bloku pre každý prvok
    function createElementBlock(element, index) {
        const block = document.createElement('div');
        block.className = 'element-block';
        block.dataset.index = index;
        let innerHTML = `<div class="element-header">Typ: ${element.type}</div>`;
        
        if (element.type === 'paragraph') {
            innerHTML += `<label>Obsah (Markdown):</label>
                <textarea class="form-control element-content" rows="4">${element.payload.content || ''}</textarea>`;
        } else if (element.type === 'poll') {
            innerHTML += `<label>Otázka:</label>
                <input type="text" class="form-control element-question" value="${element.payload.question || ''}">
                <label>Možnosti (oddelené čiarkou):</label>
                <input type="text" class="form-control element-options" value="${(element.payload.options || []).join(', ')}">`;
        } else if (element.type === 'image_box') {
            innerHTML += `<label>URL obrázka:</label>
                <input type="text" class="form-control element-url" value="${element.payload.url || ''}">
                <label>Popis:</label>
                <input type="text" class="form-control element-caption" value="${element.payload.caption || ''}">`;
        }
        block.innerHTML = innerHTML;
        return block;
    }
    
    // Predvyplnenie kontajnera s prvkami
    const composerContainer = document.getElementById('composer-container');
    composerData.forEach((element, index) => {
        const block = createElementBlock(element, index);
        composerContainer.appendChild(block);
    });
    
    // Pred odoslaním formulára zozbierame údaje z editovaných blokov
    document.getElementById('edit-article-form').addEventListener('submit', function(e) {
        const updatedElements = [];
        const blocks = document.querySelectorAll('#composer-container .element-block');
        blocks.forEach(block => {
            const index = block.dataset.index;
            const element = composerData[index];
            let payload = {};
            if (element.type === 'paragraph') {
                payload.content = block.querySelector('.element-content').value;
            } else if (element.type === 'poll') {
                payload.question = block.querySelector('.element-question').value;
                // Rozdelíme možnosti podľa čiarky a orezaných medzier
                payload.options = block.querySelector('.element-options').value.split(',').map(opt => opt.trim()).filter(opt => opt !== '');
            } else if (element.type === 'image_box') {
                payload.url = block.querySelector('.element-url').value;
                payload.caption = block.querySelector('.element-caption').value;
            }
            updatedElements.push({
                type: element.type,
                order: element.order,
                payload: payload
            });
        });
        document.getElementById('composer_json').value = JSON.stringify(updatedElements);
    });
    </script>
</body>
</html>
