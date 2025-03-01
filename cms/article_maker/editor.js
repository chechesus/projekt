document.addEventListener('DOMContentLoaded', function () {
  const articleForm = document.getElementById('articleForm');
  const markdownEditorElem = document.getElementById('markdown-editor');

  // Inicializácia SimpleMDE
  const simplemde = new SimpleMDE({ element: markdownEditorElem });

  articleForm.addEventListener('submit', function (e) {
    e.preventDefault(); // zabránime bežnému odoslaniu formulára

    // Získanie hodnôt z formulára
    const formData = new FormData(articleForm);

    // Obsah z Markdown editora
    formData.set('content', simplemde.value());

    // Odošleme dáta cez fetch (AJAX)
    fetch('save_articles.php', {
      method: 'POST',
      body: formData
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          // Vyprázdnime formulár a editor
          articleForm.reset();
          simplemde.value('');
        } else {
          alert('Chyba: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Chyba:', error);
        alert('Vyskytla sa chyba pri ukladaní článku.');
      });
  });
});
