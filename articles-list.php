<?php
require_once 'api/session.php';

$query = "SELECT id, title, thumbnail, created_at, 
                 IFNULL(excerpt, SUBSTRING(content, 1, 100)) AS excerpt
          FROM articles.articles
          ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    error_log("DEBUG: SQL query error: " . $conn->error);
    die("Chyba pri načítaní článkov.");
}

$articleCount = $result->num_rows;

require 'inc/header.php';
?>

<body>
  <div class="grid-container">
    <?php require_once 'website_elements/menu.php'; ?>
  </div>

  <div class="container my-4">
    <h1 class="mb-4">Príspevky</h1>
    <!-- Debug výpis: Počet článkov -->
    <p><small>DEBUG: Počet článkov: <?= $articleCount; ?></small></p>
    
    <div class="row">
      <?php if ($articleCount > 0): ?>
        <?php while ($article = $result->fetch_assoc()):
          $articleId = $article['id'];
          $title = $article['title'];
          $thumbnail = !empty($article['thumbnail']) ? $article['thumbnail'] : "default_thumbnail.jpg";
          $created_at = $article['created_at'];
          $excerpt = $article['excerpt'];
          error_log("DEBUG: Článok id={$articleId}, title={$title}, created_at={$created_at}");
          if (!preg_match('/^https?:\/\//', $thumbnail)) {
            $thumbnail = "http://localhost/projekt/cms/user_funct/uploads/" . $thumbnail;
          }

          // Konverzia profilového obrázka na data URI
          $imageContent = @file_get_contents($thumbnail);
          if ($imageContent === false) {
            $imageContent = file_get_contents('C:\xampp\htdocs\projekt\images\user_ico.png');
          }
          $finfo      = new finfo(FILEINFO_MIME_TYPE);
          $mimeType   = $finfo->buffer($imageContent);
          $base64Image = base64_encode($imageContent);
          $thumbnail    = "data:" . $mimeType . ";base64," . $base64Image;
        ?>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="article-box">
              <!-- Odkaz s korektne vloženým articleId -->
              <a href="show_articles.php?id=<?= $articleId; ?>" style="text-decoration: none; color: inherit;">
                <div class="info-box-icon" style="background-image: url('<?= htmlspecialchars($thumbnail); ?>');"></div>
                <div class="info-box-content p-2">
                  <span class="info-box-text article-title"><?= htmlspecialchars($title); ?></span>
                  <span class="info-box-number article-meta"><?= date("d.m.Y", strtotime($created_at)); ?></span>
                  <p class="article-excerpt"><?= htmlspecialchars($excerpt) . '...'; ?></p>
                </div>
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Žiadne články nenájdené.</p>
      <?php endif; ?>
    </div>
  </div>
  
  <?php require_once 'website_elements/footer.php'; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
