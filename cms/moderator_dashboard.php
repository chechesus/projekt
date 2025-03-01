<?php
require_once '../api/session.php';

$userId  = $_SESSION['userid'];
$roleId  = $_SESSION['role_id'];
$userName = $_SESSION['name'] ?? 'Moderátor';

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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">

        <?php require_once 'C:\xampp\htdocs\projekt\cms\includes\nav.php' ?>

        <aside class="app-sidebar">
            <aside class="sidebar">
                <?php require_once '../cms/sidebar-menu/index.php'; ?>
            </aside>
        </aside>

        <main class="app-main"> <!--begin::App Content Header-->
            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid"> <!--begin::Row-->
                    <!-- Obsah Dashboardu -->
                    <div class="app-content p-4">
                        <div class="container-fluid">

                            <!-- Profilové nastavenia -->
                            <div class="card mb-4">
                                <div class="card-header">Môj profil</div>
                                <div class="card-body">
                                    <form id="profileForm" method="POST" action="../cms/user_funct/update_profile.php">
                                        <div class="mb-3">
                                            <label for="fullName" class="form-label">Meno</label>
                                            <input type="text" name="name" id="fullName" class="form-control" value="<?= htmlspecialchars($userName); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($_SESSION['email'] ?? ''); ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Nové heslo</label>
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Vyplňte, ak chcete zmeniť">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Uložiť zmeny</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Komunikácia s adminom (Placeholder) -->
                            <div class="card mb-4">
                                <div class="card direct-chat direct-chat-primary mb-4">
                                    <div class="card-header">
                                        <h3 class="card-title">Chat s moderátormi</h3>
                                        <div class="card-tools">
                                            <span id="messageCount" class="badge text-bg-primary">3</span>
                                            <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse">
                                                <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                                                <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                                            </button> <button type="button" class="btn btn-tool" title="Contacts" data-lte-toggle="chat-pane">
                                                <i class="bi bi-chat-text-fill"></i> </button>
                                            <button type="button" class="btn btn-tool" data-lte-toggle="card-remove">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div> <!-- /.card-header -->
                                    <div class="card-body">
                                        <div class="direct-chat-messages" id="chatbox">
                                            <!-- Sem sa budú dynamicky vkladať správy -->

                                        </div>
                                        <div class="direct-chat-contacts">
                                            <ul class="contacts-list">
                                                <li>
                                                    <input type="hidden" id="receiver_id">
                                                    <input type="hidden" id="receiver_role">

                                                    <a href="#" class="contact-item" data-receiver-id="1">
                                                        <img class="contacts-list-img" src="../images/user_ico.png" alt="User Avatar">
                                                        <div class="contacts-list-info">
                                                            <span class="contacts-list-name"> Count Dracula
                                                                <small class="contacts-list-date float-end"> 2/28/2023 </small>
                                                            </span>
                                                            <span class="contacts-list-msg"> How have you been? </span>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <div class="input-group">
                                            <input type="text" id="message" placeholder="Napíš správu..." class="form-control">
                                            <span class="input-group-text">
                                                <button onclick="sendMessages()" class="btn btn-primary">Odoslať</button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Správa komentárov (Pending) -->

                        </div> <!-- /app-content -->
                    </div> <!-- /app-wrapper -->
                </div>
            </div>
        </main>
    </div>

    

    <script src="/projekt/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script>
</body>

</html>