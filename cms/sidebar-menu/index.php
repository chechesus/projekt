<!DOCTYPE html>
<!-- Coding By CodingNepal - youtube.com/@codingnepal -->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sidebar Menu | CodingNepal</title>
  <link rel="stylesheet" href="sidebar-menu/style.css">
  <!-- Linking Google fonts for icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
</head>
<body>
  <aside class="sidebar">
    <!-- Sidebar header -->
    <header class="sidebar-header">
      <a href="#" class="header-logo">
        <img src="../images/logo.jpg" alt="CodingNepal">
      </a>
      <button class="toggler sidebar-toggler">
        <span class="material-symbols-rounded">chevron_left</span>
      </button>
      <button class="toggler menu-toggler">
        <span class="material-symbols-rounded">menu</span>
      </button>
    </header>

    <nav class="sidebar-nav">
      <!-- Primary top nav -->
      <ul class="nav-list primary-nav">
        <li class="nav-item">
          <a href="#" class="nav-link">
            <span class="nav-icon material-symbols-rounded">dashboard</span>
            <span class="nav-label">Domov</span>
          </a>
          <span class="nav-tooltip">Dashboard</span>
        </li>
        <li class="nav-item">
          <a href="admin_funct\google-calendar.php" class="nav-link">
            <span class="nav-icon material-symbols-rounded">calendar_today</span>
            <span class="nav-label">Kalendár</span>
          </a>
          <span class="nav-tooltip">Calendar</span>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <span class="nav-icon material-symbols-rounded">notifications</span>
            <span class="nav-label">Oznámenia</span>
          </a>
          <span class="nav-tooltip">Notifications</span>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <span class="nav-icon material-symbols-rounded">insert_chart</span>
            <span class="nav-label">Štatistika</span>
          </a>
          <span class="nav-tooltip">Analytics</span>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <span class="nav-icon material-symbols-rounded">block</span>
            <span class="nav-label">Blokovanie používateľov</span>
          </a>
          <span class="nav-tooltip">User blocking</span>
        </li>
        <li class="nav-item">
          <a href="admin_funct/user_edit/edit.php" class="nav-link">
            <span class="nav-icon material-symbols-rounded">edit_note </span>
            <span class="nav-label">Úprava používateľov</span>
          </a>
          <span class="nav-tooltip">User eddit</span>
        </li>
      </ul>

      <!-- Secondary bottom nav -->
      <ul class="nav-list secondary-nav">
        <li class="nav-item">
          <a href="#" class="nav-link">
            <span class="nav-icon material-symbols-rounded">account_circle</span>
            <span class="nav-label">Profil</span>
          </a>
          <span class="nav-tooltip">Profile</span>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <span class="nav-icon material-symbols-rounded">settings</span>
            <span class="nav-label">Nastavenia</span>
          </a>
          <span class="nav-tooltip">Settings</span>
        </li>
        <br>
        <li class="nav-item">
          <a href="../api/logout.php" class="nav-link">
            <span class="nav-icon material-symbols-rounded">logout</span>
            <span class="nav-label">Odhlásiť sa</span>
          </a>
          <span class="nav-tooltip">Logout</span>
        </li>
      </ul>
    </nav>
  </aside>

  <!-- Script -->
  <script src="../website_elements/sidebar-menu/script.js"></script>
</body>
</html>