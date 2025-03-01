<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
require_once '../auth/auth.php';
// Načítanie obrázkov z databázy
$query = "SELECT * FROM gallery.gallery_images
          ORDER BY upload_date DESC";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="sk">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Vlaky - Adminský panel</title>
    <link rel="icon" href="/projekt/images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token']; ?>">
    <!-- Fonts, Bootstrap, a ďalšie knižnice -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
    <link rel="stylesheet" href="/projekt/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Navigácia a sidebar -->
        <?php require_once 'C:\xampp\htdocs\projekt\cms\includes\nav.php'; ?>
        <aside class="app-sidebar">
            <aside class="sidebar">
                <?php require_once '/xampp/htdocs/projekt/cms/sidebar-menu/index.php'; ?>
            </aside>
        </aside>
        <!-- Hlavný obsah -->
        <main class="app-main">
            <div class="app-content">
                <div class="container-fluid">
                    <div class="container my-4">
                        <!-- Formulár na nahrávanie nových obrázkov -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <strong>Nový obrázok</strong>
                            </div>
                            <div class="card-body">
                                <form id="galleryForm" action="/projekt/gallery/save_gallery_images.php" method="POST" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Vyberte obrázok:</label>
                                        <input type="file" name="image" id="image" class="form-control" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Kategória:</label>
                                        <input type="text" name="category" id="category" class="form-control" placeholder="napr. Interiér / Exteriér / ...">
                                    </div>
                                    <div class="mb-3">
                                        <label for="vehicle" class="form-label">Vozidlo:</label>
                                        <input type="text" name="vehicle" id="vehicle" class="form-control" placeholder="napr. Model vlaku">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="is_best" value="1" id="is_best" class="form-check-input">
                                        <label class="form-check-label" for="is_best">Označiť ako Najlepší výber</label>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Nahrať obrázok</button>
                                </form>
                            </div>

                            <script>
                                document.getElementById('galleryForm').addEventListener('submit', async function(e) {
                                    e.preventDefault(); // Zamedzí tradičné odoslaniu formulára

                                    const form = e.target;
                                    const formData = new FormData(form);

                                    try {
                                        const response = await fetch(form.action, {
                                            method: 'POST',
                                            body: formData
                                        });
                                        const result = await response.json();
                                        if (result.success) {
                                            alert('Obrázok bol úspešne nahratý.');
                                            // Obnovíme stránku alebo aktualizujeme galériu
                                            location.reload();
                                        } else {
                                            alert('Chyba: ' + (result.error || 'Neznáma chyba.'));
                                        }
                                    } catch (error) {
                                        console.error('Chyba pri odosielaní dát:', error);
                                        alert('Chyba pri komunikácii so serverom.');
                                    }
                                });
                            </script>

                        </div>

                        <!-- Zoznam existujúcich obrázkov -->
                        <div class="card">
                            <div class="card-header">
                                <strong>Existujúce obrázky v galérii</strong>
                            </div>
                            <div class="card-body">
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Titulka</th>
                                                    <th>Náhľad</th>
                                                    <th>Kategória</th>
                                                    <th>Vozidlo</th>
                                                    <th>Najlepší výber</th>
                                                    <th>Pridal</th>
                                                    <th>Dátum</th>
                                                    <th>Akcia</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()):
                                                    $id         = $row['id'];
                                                    $title      = $row['title'];
                                                    $imageUrl   = $row['image_url'];
                                                    $imageContent = @file_get_contents($imageUrl);
                                                    if ($imageContent === false) {
                                                        $imageContent = file_get_contents('C:\xampp\htdocs\projekt\images\user_ico.png');
                                                    }
                                                    $finfo      = new finfo(FILEINFO_MIME_TYPE);
                                                    $mimeType   = $finfo->buffer($imageContent);
                                                    $base64Image = base64_encode($imageContent);
                                                    $imageDataUrl   = "data:" . $mimeType . ";base64," . $base64Image;

                                                    $category   = $row['category'];
                                                    $vehicle    = $row['vehicle'];
                                                    $isBest     = $row['is_best'];
                                                    $uploadDate = $row['upload_date'];
                                                    $userName   = $row['user_name'];
                                                ?>
                                                    <tr>
                                                        <td><?= $id; ?></td>
                                                        <td><?= htmlspecialchars($title); ?></td>
                                                        <td class="table-img-col">
                                                            <img src="<?= htmlspecialchars($imageDataUrl); ?>" alt="img" class="preview-img">
                                                        </td>
                                                        <td><?= htmlspecialchars($category); ?></td>
                                                        <td><?= htmlspecialchars($vehicle); ?></td>
                                                        <td><?= ($isBest) ? 'Áno' : 'Nie'; ?></td>
                                                        <td><?= htmlspecialchars($userName); ?></td>
                                                        <td><?= date("d.m.Y H:i", strtotime($uploadDate)); ?></td>
                                                        <td>
                                                            <!-- Tlačidlá s dátovými atribútmi pre modálne okná -->
                                                            <button type="button" class="btn btn-sm btn-warning edit-btn"
                                                                data-id="<?= $id; ?>"
                                                                data-title="<?= htmlspecialchars($title); ?>"
                                                                data-category="<?= htmlspecialchars($category); ?>"
                                                                data-vehicle="<?= htmlspecialchars($vehicle); ?>"
                                                                data-is_best="<?= $isBest; ?>">
                                                                Upraviť
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                                data-id="<?= $id; ?>">
                                                                Zmazať
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <p>V galérii zatiaľ nie sú žiadne obrázky.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div> <!-- End container -->
                </div>
            </div>
        </main>
    </div>

    <!-- Modal pre úpravu obrázku -->
    <!-- Modal pre úpravu obrázku -->
    <!-- Modal pre úpravu obrázku -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Upraviť obrázok</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvoriť"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Titulka:</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category" class="form-label">Kategória:</label>
                            <input type="text" name="category" id="edit_category" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit_vehicle" class="form-label">Vozidlo:</label>
                            <input type="text" name="vehicle" id="edit_vehicle" class="form-control">
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_best" value="1" id="edit_is_best" class="form-check-input">
                            <label class="form-check-label" for="edit_is_best">Označiť ako Najlepší výber</label>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Nový obrázok (voliteľné):</label>
                            <input type="file" name="image" id="edit_image" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušiť</button>
                        <button type="submit" class="btn btn-primary">Uložiť zmeny</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Odoslanie dát z modálneho formulára cez fetch
        document.getElementById("editForm").addEventListener("submit", async function(e) {
            e.preventDefault(); // Zamedzíme tradičnému odoslaniu formulára
            const form = e.target;
            const formData = new FormData(form);

            // Pre kontrolu - vypíšeme všetky kľúče a hodnoty z FormData
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            try {
                const response = await fetch("admin_gallery_edit.php", {
                    method: "POST",
                    body: formData
                });
                const result = await response.json();
                if (result.status === "success") {
                    alert("Zmeny boli úspešne uložené.");
                    // Zatvoríme modál
                    const modalEl = document.getElementById("editModal");
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalInstance.hide();
                    // Aktualizujeme stránku alebo zoznam obrázkov
                    location.reload();
                } else {
                    alert("Chyba pri aktualizácii: " + result.message);
                }
            } catch (error) {
                console.error("Chyba pri komunikácii so serverom:", error);
                alert("Chyba pri komunikácii so serverom.");
            }
        });
    </script>


    <script>
        // Odoslanie dát z modálneho formulára cez fetch
        document.getElementById("editForm").addEventListener("submit", async function(e) {
            e.preventDefault(); // Zamedzíme tradičnému odoslaniu formulára
            const form = e.target;
            const formData = new FormData(form);

            // Pre kontrolu - vypíšeme všetky kľúče a hodnoty z FormData
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }

            try {
                const response = await fetch("admin_gallery_edit.php", {
                    method: "POST",
                    body: formData
                });
                const result = await response.json();
                if (result.status === "success") {
                    alert("Zmeny boli úspešne uložené.");
                    // Zatvoríme modál
                    const modalEl = document.getElementById("editModal");
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalInstance.hide();
                    // Aktualizujeme stránku alebo zoznam obrázkov
                    location.reload();
                } else {
                    alert("Chyba pri aktualizácii: " + result.message);
                }
            } catch (error) {
                console.error("Chyba pri komunikácii so serverom:", error);
                alert("Chyba pri komunikácii so serverom.");
            }
        });
    </script>


    <!-- Modal pre vymazanie obrázku -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteForm" action="./admin_gallery_delete.php" method="POST">
                <input type="hidden" name="id" id="delete_id">
                <input type="hidden" name="csrf_token" id="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Vymazať obrázok</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvoriť"></button>
                    </div>
                    <div class="modal-body">
                        <p>Ste si istý, že chcete vymazať tento obrázok?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušiť</button>
                        <button type="submit" class="btn btn-danger">Vymazať</button>
                    </div>
                </div>
            </form>
        </div>

    </div>

    <!-- Bootstrap JS a vlastný skript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Inicializácia modálov
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

        // Spracovanie kliknutí na tlačidlá "Upraviť"
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                const category = this.getAttribute('data-category');
                const vehicle = this.getAttribute('data-vehicle');
                const isBest = this.getAttribute('data-is_best');

                document.getElementById('edit_id').value = id;
                document.getElementById('edit_title').value = title;
                document.getElementById('edit_category').value = category;
                document.getElementById('edit_vehicle').value = vehicle;
                document.getElementById('edit_is_best').checked = (isBest == 1);
                // Otvorenie modálneho okna pre úpravu
                editModal.show();
            });
        });

        // Spracovanie kliknutí na tlačidlá "Zmazať"
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id').value = id;
                // Otvorenie modálneho okna pre vymazanie
                deleteModal.show();
            });
        });
    </script>
</body>

</html>
<?php
$result->free();
$conn->close();
?>