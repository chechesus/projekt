<?php
// editor.php
require_once '../../api/session.php';
$userId = $_SESSION['userId'] ?? null;
$roleId = $_SESSION['roleId'] ?? null;
?>
<!DOCTYPE html>
<html lang="sk">

<head>
  <meta charset="UTF-8">
  <title>Editor článkov - Vlaky Adminský panel</title>
  <link rel="icon" href="/projekt/images/logo.jpg">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Fonty a ikony -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
  <!-- Štýly -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="/projekt/style.css">
  <link rel="stylesheet" href="adminCSS.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <!-- Navigácia -->
    <?php require_once 'C:\xampp\htdocs\projekt\cms\includes\nav.php'; ?>
    <!-- Sidebar -->
    <aside class="app-sidebar">
      <aside class="sidebar">
        <?php require_once '/xampp/htdocs/projekt/cms/sidebar-menu/index.php'; ?>
      </aside>
    </aside>
    <!-- Hlavný obsah -->
    <main class="app-main">
      <div class="app-content">
        <div class="container-fluid">
          <!-- Editor článkov -->
          <div class="row mb-4">
            <div class="col-md-10">
              <div class="card">
                <div class="container my-4">
                  <!-- Oblasť pre drag & drop -->

                  <!-- Formulár článku -->
                  <form id="articleForm" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                      <label for="title">Názov článku</label>
                      <input type="text" name="title" id="title" class="form-control" required>
                    </div>

                    <!-- Kategória -->
                    <div class="mb-3">
                      <label for="category">Kategória</label>
                      <input type="text" name="category" id="category" class="form-control">
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-3">
                      <label for="excerpt">Stručný popis</label>
                      <textarea name="excerpt" id="excerpt" class="form-control"></textarea>
                    </div>

                    <!-- Thumbnail ako URL -->
                    <div class="mb-3">
                      <label for="thumbnail_link">URL thumbnailu</label>
                      <input type="text" name="thumbnail_link" id="thumbnail_link" class="form-control">
                    </div>

                    <!-- Alebo thumbnail ako súbor -->
                    <div class="mb-3">
                      <label for="thumbnail_file">Nahrať thumbnail</label>
                      <input type="file" name="thumbnail_file" id="thumbnail_file" class="form-control">
                    </div>

                    <!-- Markdown editor -->
                    <div class="mb-3">
                      <label for="markdown-editor">Obsah článku (Markdown):</label>
                      <textarea id="markdown-editor" name="content" class="form-control"></textarea>
                    </div>

                    <!-- Dátum, čas -->
                    <div class="mb-3">
                      <label for="schedule_date">Dátum publikácie (voliteľné)</label>
                      <input type="date" name="schedule_date" id="schedule_date" class="form-control">
                    </div>
                    <div class="mb-3">
                      <label for="schedule_time">Čas publikácie (voliteľné)</label>
                      <input type="time" name="schedule_time" id="schedule_time" class="form-control">
                    </div>

                    <!-- Composer JSON ak používaš drag&drop -->
                    <input type="hidden" name="composer_json" id="composer_json">

                    <button type="submit" class="btn btn-primary">Uložiť článok</button>
                  </form>

                  <br><br>
                  <!-- Náhľad článku -->
                  <h2>Náhľad článku</h2>
                  <div id="preview-container" style="border: 1px solid #ddd; padding: 15px; background: #f9f9f9;"></div>
                </div>
              </div>
            </div>
          </div>
          <!-- Modály pre ďalšie funkcie -->
          <!-- Paragraph Modal -->
          <div class="modal fade" id="paragraphModal" tabindex="-1" aria-labelledby="paragraphModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <!-- Obsah modálu pre odsek -->
              </div>
            </div>
          </div>
          <!-- Poll Modal -->
          <div class="modal fade" id="pollModal" tabindex="-1" aria-labelledby="pollModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <!-- Obsah modálu pre anketu -->
              </div>
            </div>
          </div>
          <!-- Image Modal -->
          <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <!-- Obsah modálu pre obrázok -->
              </div>
            </div>
          </div>
          <!-- Confirmation Modal -->
          <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="confirmationModalLabel">Potvrdiť zmeny</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <p id="modalContent"></p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušiť</button>
                  <button type="button" class="btn btn-primary" id="confirmSave">Potvrdiť</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Koniec modálov -->
        </div>
      </div>
    </main>
  </div>
  <!-- Skripty -->
  <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="editor.js"></script>
  <script src="/projekt/scripts.js"></script>
  <script>
    window.wsConfig = {
      userId: <?= json_encode($userId); ?>,
      roleId: <?= json_encode($roleId); ?>,
      csrfToken: <?= json_encode($_SESSION['csrf_token'] ?? ''); ?>
    };
  </script>
</body>

</html>