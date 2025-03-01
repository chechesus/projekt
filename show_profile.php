<?php
require_once './api/session.php';
require_once 'C:\xampp\htdocs\projekt\stats.php';
?>
<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Vlaky - Adminský panel</title><!--begin::Primary Meta Tags-->
    <link rel="icon" href="/images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="/projekt/style.css"><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous"><!-- jsvectormap -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
    <style>
        .profile-picture {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }

        .comment-picture {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }

        .comment {
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->
        <?php require_once 'C:\xampp\htdocs\projekt\cms\includes\nav.php' ?>
        <aside class="app-sidebar">
            <aside class="sidebar">
                <?php require_once 'C:\xampp\htdocs\projekt\cms\sidebar-menu\index.php'; ?>
            </aside>
        </aside>
        <main class="app-main"> <!--begin::App Content Header-->
            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid"> <!--begin::Row-->
                    <div class="container my-4">
                        <div id="profile"></div>
                        <hr>
                        <div id="comments">
                            <h3>Profil</h3>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div> <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Získame parameter "id" z URL (napr. profile.html?id=123)
        const urlParams = new URLSearchParams(window.location.search);
        const userId = urlParams.get('id');

        if (!userId) {
            document.getElementById('profile').innerHTML = '<div class="alert alert-danger">Neplatné ID používateľa.</div>';
        } else {
            fetch('profile.php?id=' + userId)
                .then(response => response.json())
                .then(data => {
                    // Ak API vráti chybu, zobrazíme ju
                    if (data.error) {
                        document.getElementById('profile').innerHTML = '<div class="alert alert-danger">' + data.error + '</div>';
                        return;
                    }

                    const user = data.user;
                    const comments = data.comments;

                    // Vytvorenie HTML pre profil používateľa
                    let profileHTML = `
            <div class="card mb-4">
              <div class="card-body d-flex align-items-center">
                <img src="${user.profile_picture ? user.profile_picture : 'default_profile.png'}" alt="Profilová fotka" class="profile-picture me-4">
                <div>
                  <h2>${user.name}</h2>
                  ${user.email ? `<p>Email: ${user.email}</p>` : ''}
                </div>
              </div>
            </div>
          `;
                    document.getElementById('profile').innerHTML = profileHTML;

                    // Vytvorenie HTML pre komentáre
                    let commentsHTML = '';
                    if (comments.length === 0) {
                        commentsHTML = '<p>Žiadne komentáre.</p>';
                    } else {
                        comments.forEach(comment => {
                            commentsHTML += `
                <div class="comment d-flex">
                  <img src="${comment.profile_picture ? comment.profile_picture : 'default_profile.png'}" alt="Profilová fotka" class="comment-picture me-3">
                  <div>
                    <h5>${comment.username}</h5>
                    <p>${comment.comment_text}</p>
                    <small class="text-muted">${new Date(comment.created_at).toLocaleString()}</small>
                  </div>
                </div>
              `;
                        });
                    }
                    document.getElementById('comments').innerHTML += commentsHTML;
                })
                .catch(error => {
                    console.error('Chyba pri načítaní profilu:', error);
                    document.getElementById('profile').innerHTML = '<div class="alert alert-danger">Chyba pri načítaní profilu.</div>';
                });
        }
    </script>
</body>

</html>