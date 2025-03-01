<?php
require_once __DIR__ . '/../../api/session.php'; // upravte cestu podľa štruktúry projektu

// 1) Kontrola prihlásenia
$userId = $_SESSION['userid'] ?? null;
$roleId = $_SESSION['role_id'] ?? null;
if (!$userId || !$roleId) {
  header("Location: login.php");
  exit;
}

// 2) Určenie tabuľky podľa role_id
switch ($roleId) {
  case 1:
    $tableName = "acces.admins";
    break;
  case 3:
    $tableName = "acces.moderators";
    break;
  case 2:
  default:
    $tableName = "data.users";
    break;
}

// 3) Načítanie údajov o používateľovi
$stmtUser = $conn->prepare("SELECT name, profile_picture FROM {$tableName} WHERE id = ?");
$stmtUser->bind_param("i", $userId);
$stmtUser->execute();
$userResult = $stmtUser->get_result();
$userData = $userResult->fetch_assoc();
$stmtUser->close();

$fullName   = $userData['name'] ?? 'Neznámy používateľ';
$profilePic = $userData['profile_picture'] ?? 'default.png';

// Ak uchovávate len názov súboru, pridajte cestu (ak je potrebné).
if (!preg_match('/^https?:\/\//', $profilePic)) {
  $profilePic = "http://localhost/projekt/cms/user_funct/uploads/" . $profilePic;
}

// Konverzia profilového obrázka na data URI
$imageContent = @file_get_contents($profilePic);
if ($imageContent === false) {
  $imageContent = file_get_contents('C:\xampp\htdocs\projekt\images\user_ico.png');
}
$finfo      = new finfo(FILEINFO_MIME_TYPE);
$mimeType   = $finfo->buffer($imageContent);
$base64Image = base64_encode($imageContent);
$dataUri    = "data:" . $mimeType . ";base64," . $base64Image;

// Inicializácia počtov – tieto budú aktualizované cez WS
$notificationCount = 0;
$messageCount = 0;
?>
<!-- Začiatok navigácie -->
<nav class="navbar navbar-expand-lg">
  <div class="container-fluid">
    <!-- Navigačné odkazy a vyhľadávanie -->
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <!-- Vyhľadávací formulár -->
      <form class="d-flex" method="POST" action="/projekt/cms/includes/search_articles.php">
        <input class="form-control me-2" type="search" name="q" placeholder="Hľadať články" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Hľadať</button>
      </form>

      <!-- Dynamické prvky – správy, notifikácie, používateľ -->
      <ul class="navbar-nav ms-3">
        <!-- Správy Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-bs-toggle="dropdown" href="#">
            <i class="bi bi-chat-text"></i>
            <span id="messageBadge" class="navbar-badge badge text-bg-danger"><?= $messageCount ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="messagesDropdown">
            <li><span class="dropdown-item">Správy načítané cez WebSocket</span></li>
            <li><a class="dropdown-item text-center" href="messages.php">Zobraziť všetky správy</a></li>
          </ul>
        </li>

        <!-- Notifikácie Dropdown -->
        <li class="nav-item dropdown ms-2">
          <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell-fill"></i>
            <span id="notificationBadge" class="badge bg-warning"><?= $notificationCount ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
            <li class="dropdown-header">Notifikácie (<span id="notifCountText"><?= $notificationCount ?></span>)</li>
            <li><span class="dropdown-item">Notifikácie načítané cez WebSocket</span></li>
            <li><a class="dropdown-item text-center" href="notifications.php">Zobraziť všetky notifikácie</a></li>
          </ul>
        </li>

        <!-- Používateľ Dropdown -->
        <li class="nav-item dropdown ms-2">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="<?= htmlspecialchars($dataUri); ?>" alt="Profilová fotka" class="rounded-circle user-image" width="30" height="30">
            <span class="ms-2 d-none d-md-inline"><?= htmlspecialchars($fullName); ?></span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="user_dashboard.php">Môj profil</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="logout.php">Odhlásiť sa</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>