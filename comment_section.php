<?php
require_once 'api/session.php';

// Skontrolujeme, či je používateľ prihlásený
if (!isset($_SESSION['userid'])) {
    error_log("Prístup do komentárovej sekcie odmietnutý: používateľ nie je prihlásený.");
    header("Location: login.php");
    exit;
}
?>
<main class="app-main">
  <div class="container my-4">
    <!-- Kontajner pre timeline komentáre -->
    <div id="comments-timeline" class="timeline">
      <!-- Dynamicky generované komentáre budú vložené sem -->
    </div>

    <!-- Formulár pre odoslanie nového komentára -->
    <div class="mt-4">
      <form id="commentForm">
        <div class="mb-3">
          <textarea id="commentText" class="form-control" rows="3" placeholder="Napíš komentár..."></textarea>
        </div>
        <!-- Hodnota fk_user_ID sa načíta zo session -->
        <input type="hidden" id="fk_user_ID" value="<?= htmlspecialchars($_SESSION['userid']); ?>">
        <!-- Skrytý input pre article_id (ak je k dispozícii) -->
        <input type="hidden" id="articleId" value="<?= isset($_GET['article_id']) ? intval($_GET['article_id']) : 0; ?>">
        <button type="submit" class="btn btn-primary">Odoslať</button>
      </form>
    </div>
  </div>
</main>

<script>
// Načítame session user id do premennej
const sessionUserId = "<?= htmlspecialchars($_SESSION['userid']); ?>";

// Funkcia na vygenerovanie HTML pre jeden komentár
function renderComment(comment) {
  return `
    <div class="timeline-item mb-4">
      <div class="d-flex align-items-start">
        <!-- Profilová fotka s odkazom na profil -->
        <a href="profile.php?id=${comment.fk_user_ID}">
          <img src="${comment.user_picture}" alt="${comment.user_nick}" class="rounded-circle" style="width:50px; height:50px;">
        </a>
        <div class="ms-2">
          <h5 class="mb-1">
            <a href="profile.php?id=${comment.fk_user_ID}">${comment.user_nick}</a>
            <small class="text-muted ms-2"><i class="bi bi-clock-fill"></i> ${comment.created_at}</small>
          </h5>
          <p class="mb-0">${comment.comment_text}</p>
        </div>
      </div>
    </div>
  `;
}

// Funkcia pre načítanie komentárov z backendu
function loadComments() {
  console.log("Načítavam komentáre...");
  fetch('comments.php<?= isset($_GET['article_id']) ? "?article_id=" . intval($_GET['article_id']) : "" ?>')
    .then(response => response.json())
    .then(data => {
      console.log("Komentáre načítané:", data);
      const timeline = document.getElementById('comments-timeline');
      timeline.innerHTML = '';
      data.forEach(comment => {
        timeline.innerHTML += renderComment(comment);
      });
    })
    .catch(error => console.error('Chyba pri načítaní komentárov:', error));
}

// Spracovanie odoslania komentára
document.getElementById('commentForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const commentText = document.getElementById('commentText').value.trim();
  const userId = document.getElementById('fk_user_ID').value;
  const articleId = document.getElementById('articleId').value;

  if (!commentText) {
    alert("Prosím, napíš komentár.");
    return;
  }

  const formData = new FormData();
  formData.append('fk_user_ID', userId);
  formData.append('comment_text', commentText);
  formData.append('article_id', articleId);

  console.log("Odosielam komentár:", { fk_user_ID: userId, comment_text: commentText, article_id: articleId });

  fetch('comments.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.json())
  .then(result => {
    console.log("Výsledok odoslania komentára:", result);
    if (result.success) {
      document.getElementById('commentText').value = '';
      loadComments(); // Obnovíme zoznam komentárov
    } else {
      alert("Chyba pri odosielaní komentára: " + (result.error || 'Neznáma chyba'));
    }
  })
  .catch(error => console.error('Chyba pri odosielaní komentára:' . $formData, error ));
});

// Načítame komentáre pri načítaní stránky
document.addEventListener('DOMContentLoaded', loadComments);
</script>
