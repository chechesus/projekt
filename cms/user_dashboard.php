<?php
// Start session and require database connection
require_once '../api/session.php';

$userId = $_SESSION['userid'] ?? null;
if (!$userId) {
    header('Location: login.php');
    exit;
}

// Retrieve user data from database (assuming $conn is a mysqli connection)
$stmt = $conn->prepare("SELECT name, nick, bio, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$name = $user['name'] ?? '';
$nickname = $user['nick'] ?? '';
$bio = $user['bio'] ?? '';
$profilePicture = $user['profile_picture'] ?? '';
?>
<!DOCTYPE html>
<html lang="sk">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Vlaky - Adminský panel</title><!--begin::Primary Meta Tags-->
    <link rel="icon" href="../images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="/projekt/style.css"><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous"><!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">

</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
    <?php require_once 'includes/nav.php' ?>
    <aside class="app-sidebar">
            <aside class="sidebar">
                <?php require_once '../cms/sidebar-menu/index.php'; ?>
            </aside>
        </aside>
        <div class="app-content">
            <div class="container-fluid" style="margin-top: 20px;">
                <div class="row mb-4">
                    <div class="col-md-10">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Upraviť profil</h3>
                            </div>
                            <div class="card-body">
                                <form id="profileForm" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="fullName" class="form-label">Celé meno</label>
                                        <input type="text" class="form-control" id="fullName" name="fullName" value="<?= htmlspecialchars($name) ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="nickname" class="form-label">Prezývka</label>
                                        <input type="text" class="form-control" id="nickname" name="nickname" value="<?= htmlspecialchars($nickname) ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Bio</label>
                                        <textarea class="form-control" id="bio" name="bio" rows="3"><?= htmlspecialchars($bio) ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="profilePic" class="form-label">Profilový obrázok</label>
                                        <input type="file" class="form-control" id="profilePic" name="profilePic" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Zmeniť heslo</label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="confirmPassword" class="form-label">Potvrdiť heslo</label>
                                        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                                    </div>
                                    <button type="button" class="btn btn-primary" onclick="openConfirmationModal()">Uložiť zmeny</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
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
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function openConfirmationModal() {
        const fullName = document.getElementById('fullName').value;
        const nickname = document.getElementById('nickname').value;
        const bio = document.getElementById('bio').value;
        const profilePic = document.getElementById('profilePic').files[0];
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (password && password !== confirmPassword) {
            alert('Heslá sa nezhodujú!');
            return;
        }

        const modalContent = document.getElementById('modalContent');
        modalContent.innerHTML = `
            <p>Celé meno: ${fullName}</p>
            <p>Prezývka: ${nickname}</p>
            <p>Bio: ${bio}</p>
            <p>Profilový obrázok: ${profilePic ? profilePic.name : 'Žiadny'}</p>
        `;

        const modalElement = document.getElementById('confirmationModal');
        const modalInstance = new bootstrap.Modal(modalElement);
        modalInstance.show();
    }

    document.getElementById('confirmSave').addEventListener('click', function() {
        const formData = new FormData(document.getElementById('profileForm'));

        fetch('../cms/user_funct/update_profile.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data);
            // Získame existujúcu inštanciu modalu
            const modalElement = document.getElementById('confirmationModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if(modalInstance){
                modalInstance.hide();
            } else {
                // Ak inštancia neexistuje, vytvoríme ju a potom skryjeme
                new bootstrap.Modal(modalElement).hide();
            }
            // Ak je potrebné, môžeme presmerovať
            if(data.redirectUrl){
                window.location.href = data.redirectUrl;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // V prípade chyby tiež môžeme skryť modal
            const modalElement = document.getElementById('confirmationModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if(modalInstance){
                modalInstance.hide();
            } else {
                new bootstrap.Modal(modalElement).hide();
            }
        });
    });
</script>
<script>
    const wsUrl = "ws://localhost:50000/?user_id=<?php echo $userId; ?>&role_id=<?php echo $roleId; ?>";
    const socket = new WebSocket(wsUrl);


    socket.onopen = function(e) {
      console.log("WebSocket pripojenie nadviazané na: " + wsUrl);
      // Môžete poslať identifikačnú správu, ak to WS server vyžaduje:
      socket.send(JSON.stringify({action: "identify", user_id: <?php echo $userId; ?>, role_id: <?php echo $roleId; ?>}));
    };

    socket.onmessage = function(event) {
      console.log("Správa od WS servera:", event.data);
      try {
        const data = JSON.parse(event.data);
        if (data.status === "blocked") {
            console.log(data);
          alert("Ste zablokovaní. Dôvod: " + data.reason );
          window.location.href = "/projekt/api/logout.php";
        }else{
            
        }

       
        if (data.new_notification_count !== undefined) {
          const notifBadge = document.querySelector("#notificationsDropdown .badge");
          if (notifBadge) {
            notifBadge.textContent = data.new_notification_count;
          }
        }
        if (data.new_message_count !== undefined) {
          const msgBadge = document.querySelector("#messagesDropdown .badge");
          if (msgBadge) {
            msgBadge.textContent = data.new_message_count;
          }
        }
      } catch(e) {
        console.error("Chyba pri parsovaní WS správy:", e);
      }
    };

    socket.onclose = function(e) {
      console.log("WebSocket spojenie zatvorené", e);
    };

    socket.onerror = function(e) {
      console.error("WebSocket chyba:", e);
    };
  </script>

</body>
</html>
