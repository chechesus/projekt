<?php 
require_once 'api/session.php';

// Spracovanie odoslania nového komentára
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new-comment'])) {
    $new_comment = trim($_POST['new-comment']);
    $article_id  = (int) $_POST['article-id'];

    if (!empty($new_comment) && $article_id > 0) {
        // Použijeme prepared statement pre bezpečný insert
        $stmt = $conn->prepare("INSERT INTO comments (article_id, fk_user_ID, comment_text, timestamp) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iis", $article_id, $_SESSION['userid'], $new_comment);
        $stmt->execute();
        $stmt->close();

        // Vloženie notifikácie (príklad)
        $notification_message = "A new comment has been posted on your thread.";
        $stmtNot = $conn->prepare("INSERT INTO notifications (message, type, sent) VALUES (?, 'comment', 0)");
        $stmtNot->bind_param("s", $notification_message);
        $stmtNot->execute();
        $stmtNot->close();
    }
    // Po spracovaní presmerujeme (alebo môžeme načítať komentáre cez AJAX)
    header("Location: blog_post.php?id=" . $_POST['article-id']);
    exit;
}

// Spracovanie editácie komentára
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edited-comment'])) {
    $edited_comment = trim($_POST['edited-comment']);
    $comment_id = (int) $_POST['comment-id'];
    $article_id = (int) $_POST['article-id'];

    if (!empty($edited_comment) && $comment_id > 0) {
        $stmtEdit = $conn->prepare("UPDATE comments SET comment_text = ? WHERE comment_id = ?");
        $stmtEdit->bind_param("si", $edited_comment, $comment_id);
        $stmtEdit->execute();
        $stmtEdit->close();
    }
    header("Location: blog_post.php?id=" . $article_id);
    exit;
}

require 'inc/header.php';
?>
<body>
  <div class="grid-container">
    <?php include 'website_elements/menu.php';?> 
  </div>

  <div class="text">
    <?php
    // Načítanie článku
    require_once 'api/config.php';
    $article_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($article_id <= 0) {
        echo "<p>Article not found.</p>";
    } else {
        $stmtArt = $conn->prepare("SELECT title, content FROM articles WHERE article_id = ?");
        $stmtArt->bind_param("i", $article_id);
        $stmtArt->execute();
        $resultArt = $stmtArt->get_result();
        if ($resultArt->num_rows > 0) {
            $article_data = $resultArt->fetch_assoc();
            echo "<h1>" . htmlspecialchars($article_data['title']) . "</h1>";
            echo "<p>" . htmlspecialchars($article_data['content']) . "</p>";
        } else {
            echo "<p>Article not found.</p>";
        }
        $stmtArt->close();
    }
    ?>
  </div>

  <div class="comment-thread">
    <h2>Komentáre</h2>
    <div id="comments-timeline" class="timeline">
      <?php
      // Načítanie komentárov pre článok
      if ($article_id > 0) {
          $stmtCom = $conn->prepare("
              SELECT c.comment_id, c.comment_text, c.timestamp, u.nick AS username, u.profile_picture, c.fk_user_ID 
              FROM comments c
              JOIN users u ON c.fk_user_ID = u.id
              WHERE c.article_id = ?
              ORDER BY c.timestamp DESC
          ");
          $stmtCom->bind_param("i", $article_id);
          $stmtCom->execute();
          $resultCom = $stmtCom->get_result();
          while ($comment = $resultCom->fetch_assoc()) {
              $comment_id = $comment['comment_id'];
              $comment_text = htmlspecialchars($comment['comment_text']);
              $comment_author = htmlspecialchars($comment['username']);
              $comment_timestamp = htmlspecialchars($comment['timestamp']);
              // Ak profilovú fotku nenájdeme, použijeme fallback
              $profile_pic = !empty($comment['profile_picture']) ? $comment['profile_picture'] : '/projekt/images/user_ico.png';
              ?>
              <div class="comment" id="comment-<?php echo $comment_id; ?>">
                <div class="comment-heading">
                  <div class="comment-voting">
                    <button type="button"><span aria-hidden="true">&#9650;</span></button>
                    <button type="button"><span aria-hidden="true">&#9660;</span></button>
                  </div>
                  <div class="comment-info">
                    <a href="show_profile.php?id=<?php echo $comment['fk_user_ID']; ?>" class="comment-author"><?php echo $comment_author; ?></a>
                    <p class="m-0"><?php echo $comment_timestamp; ?></p>
                  </div>
                </div>
                <div class="comment-body">
                  <p><?php echo $comment_text; ?></p>
                  <!-- Edit button pre komentáre, ak je používateľ autorom -->
                  <?php if (isset($_SESSION['userid']) && $_SESSION['userid'] == $comment['fk_user_ID']): ?>
                    <form action="" method="post" style="margin-top:10px;">
                      <textarea name="edited-comment" class="form-control" rows="2"><?php echo $comment_text; ?></textarea>
                      <input type="hidden" name="comment-id" value="<?php echo $comment_id; ?>">
                      <input type="hidden" name="article-id" value="<?php echo $article_id; ?>">
                      <button type="submit" class="btn btn-sm btn-primary" style="margin-top:5px;">Update Comment</button>
                    </form>
                  <?php endif; ?>
                  <button type="button">Reply</button>
                  <button type="button">Flag</button>
                </div>
              </div>
              <?php
          }
          $stmtCom->close();
      }
      ?>
    </div>

    <!-- Formulár pre pridanie nového komentára -->
    <div class="mt-4">
      <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
        <form action="blog_post.php?id=<?php echo $article_id; ?>" method="post">
          <textarea name="new-comment" class="form-control" placeholder="Napíš komentár..."></textarea>
          <input type="hidden" name="article-id" value="<?php echo $article_id; ?>">
          <button type="submit" class="btn btn-primary mt-2">Add Comment</button>
        </form>
      <?php else: ?>
        <p>Pre pridanie komentára sa prihlás, alebo zaregistruj. (Hostia nemajú možnosť komentovať.)</p>
      <?php endif; ?>
    </div>
  </div>

  <script>
    // Zabezpečíme, že sa stránka po odoslaní komentára neznovu odošle formulár
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>

<?php 
include 'website_elements/footer.php';
$conn->close();
?>
</body>
</html>
