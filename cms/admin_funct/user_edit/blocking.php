<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
require_once '../../auth/auth.php';
?>
<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Vlaky - Adminský panel</title><!--begin::Primary Meta Tags-->
    <link rel="icon" href="/projekt/images/logo.jpg">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token']; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous"><!--end::Fonts--><!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous"><!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous"><!--end::Third Party Plugin(Bootstrap Icons)--><!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="/projekt/style.css"><!--end::Required Plugin(AdminLTE)--><!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous"><!-- jsvectormap -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->

        <aside class="app-sidebar">
            <aside class="sidebar">
                <?php require_once '/xampp/htdocs/projekt/cms/sidebar-menu/index.php'; ?>
            </aside>
        </aside>
        <main class="app-main"> <!--begin::App Content Header-->
            <div class="app-content"> <!--begin::Container-->
                <div class="container-fluid"> <!--begin::Row-->
                    <div class="col-md-12">
                        <div class="card mb-8">
                            <div class="card-header">
                                <h3 class="card-title">Zoznam používateľov</h3>
                            </div>

                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-4">
                                        <input type="text" id="searchUser" class="form-control" placeholder="Vyhľadať meno, e-mail alebo ID">
                                    </div>
                                    <div class="col-md-3">
                                        <select id="roleFilter" class="form-control">
                                            <option value="">Všetky role</option>
                                            <option value="1">Admin</option>
                                            <option value="3">Moderátor</option>
                                            <option value="2">Používateľ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select id="blockFilter" class="form-control">
                                            <option value="">Všetci</option>
                                            <option value="blocked">Len blokovaní</option>
                                            <option value="active">Len aktívni</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-primary" onclick="searchUsers()">Vyhľadať</button>
                                    </div>
                                </div>
                            </div> <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered" id="userTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">ID</th>
                                            <th>Meno</th>
                                            <th>E-mail</th>
                                            <th>Dátum registrácie</th>
                                            <th>Posledná aktivita</th>
                                            <th>ID role</th>
                                            <th>Aktivita</th>
                                            <th>Akcia</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div> <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                <ul class="pagination pagination-sm m-0 float-end">
                                    <li class="page-item"> <a class="page-link" href="#">&laquo;</a> </li>
                                    <li class="page-item"> <a class="page-link" href="#">1</a> </li>
                                    <li class="page-item"> <a class="page-link" href="#">&raquo;</a> </li>
                                </ul>
                            </div>
                        </div> <!-- /.card -->
                    </div> <!-- /.col --> <!--end::Container-->
                </div> <!--end::App Content-->
            </div>

        </main> <!--end::App Main--> <!--begin::Footer-->

    </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <!-- Modal na zablokovanie používateľa -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <div class="modal fade" id="blockUserModal" tabindex="-1" aria-labelledby="blockUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="padding: 20px;">
                <div class="modal-header">
                    <h5 class="modal-title" id="blockUserModalLabel">Zablokovať používateľa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Vyberte dĺžku blokovania:</p>
                    <select id="blockDuration" class="form-control">
                        <option value="1">1 deň</option>
                        <option value="7">1 týždeň</option>
                        <option value="30">1 mesiac</option>
                        <option value="permanent">Natrvalo</option>
                        <option value="custom">Vybrať dátum a čas</option>
                    </select>
                    <br>
                    <div id="datetimeContainer" style="display: none;">
                        <label for="datetime">Dátum a čas:</label>
                        <input type="datetime-local" id="datetime" name="datetime">
                    </div>
                </div>
                <div class="modal-body">
                    <h3>Dôvod pre blokovanie:</h3>
                    <textarea name="block_reason" id="reason_blk" rows="4" cols="50">

                </textarea>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušiť</button>
                    <button onclick="BlockUser()" type="button" class="btn btn-danger" id="confirmBlockBtn">Potvrdiť</button>
                </div>
            </div>
        </div>
    </div>
    <script type="module" src="/projekt/scripts.js"></script>

    <!-- Externé knižnice -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/browser/overlayscrollbars.browser.es6.min.js" integrity="sha256-H2VM7BKda+v2Z4+DRy69uknwxjyDRhszjXFhsL4gD3w=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha256-whL0tQWoY1Ku1iskqPFvmZ+CHsvmRWx/PIoEvIeWh4I=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha256-YMa+wAM6QkVyz999odX7lPRxkoYAan8suedu4k2Zur8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" integrity="sha256-ipiJrswvAR4VAx/th+6zWsdeYmVae0iJuiR+6OqHJHQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js" integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js" integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>
</body>
<script>
    // Add this debounce function at the top of your script
function debounce(func, timeout = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => { func.apply(this, args); }, timeout);
    };
}

// Modify the searchUsers function to accept parameters
/*async function searchUsers() {
    const searchQuery = document.getElementById('searchUser').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const blockFilter = document.getElementById('blockFilter').value;
    const tbody = document.querySelector("#userTable tbody");
    
    try {
        const response = await fetch('get_users.php');
        const users = await response.json();
        
        // Filter users
        const filteredUsers = users.filter(user => {
            const matchesSearch = user.name.toLowerCase().includes(searchQuery) || 
                                 user.email.toLowerCase().includes(searchQuery) || 
                                 user.ID.toString().includes(searchQuery);
            const matchesRole = !roleFilter || user.role_id === roleFilter;
            const matchesBlock = blockFilter === 'blocked' ? user.blocked === 1 :
                               blockFilter === 'active' ? user.blocked === 0 : true;
            
            return matchesSearch && matchesRole && matchesBlock;
        });

        // Update table
        tbody.innerHTML = filteredUsers.map(user => `
            <tr class="align-middle">
                <td>${user.ID}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.created}</td>
                <td>${user.last_logg}</td>
                <td>${user.role_id}</td>
                <td>${user.blocked ? 'Zablokovaný' : 'Aktívny'}</td>
                <td>
                    <button class="btn btn-sm ${user.blocked ? 'btn-success' : 'btn-danger'}"
                        onclick="handleBlockClick(${user.ID}, ${user.blocked})">
                        ${user.blocked ? 'Odblokovať' : 'Zablokovať'}
                    </button>
                </td>
            </tr>
        `).join('');

    } catch (error) {
        console.error('Error:', error);
    }
}*/

// Update event listeners
document.addEventListener("DOMContentLoaded", () => {
    // Initial load
    searchUsers();
    
    // Real-time filtering
    const searchInput = document.getElementById('searchUser');
    const roleFilter = document.getElementById('roleFilter');
    const blockFilter = document.getElementById('blockFilter');
    
    const processChange = debounce(() => searchUsers());
    
    searchInput.addEventListener('input', processChange);
    roleFilter.addEventListener('change', processChange);
    blockFilter.addEventListener('change', processChange);
});

    document.getElementById("blockDuration").addEventListener("change", function() {
        const datetimeContainer = document.getElementById("datetimeContainer");
        if (this.value === "custom") {
            datetimeContainer.style.display = "block"; // Zobraziť dátum a čas
        } else {
            datetimeContainer.style.display = "none"; // Skryť dátum a čas
        }
    });

    async function unblockUser(userId) {
        let csrf_token = "<?= $_SESSION['csrf_token']; ?>";

        fetch('unblock_user.php', {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `user_id=${encodeURIComponent(userId)}&role_id=${encodeURIComponent(document.querySelector(`#userTable tr td[id="role_id"]`).textContent.trim())}&csrf_token=${encodeURIComponent(csrf_token)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    populateTable(); // Najskôr aktualizácia tabuľky
                    alert('Používateľ bol úspešne odblokovaný.'); // Potom alert
                } else {
                    alert('Chyba pri odblokovaní používateľa.');
                }
            })
            .catch(error => console.error('Chyba pri odosielaní požiadavky:', error));
    }

    function BlockUser() {
        var blockDuration = document.getElementById('blockDuration').value;
        var reason = document.getElementById('reason_blk').value.trim();
        var userId = selectedUserId;
        var datetimeInput = document.getElementById("datetime");
        var selectedDateTime = datetimeInput.value;
        var customDate = selectedDateTime; // Ensure this is passed if custom date is selected

        if (!userId) {
            alert("Chyba: ID používateľa je neplatné.");
            return;
        }

        let csrf_Token = '<?= $_SESSION['csrf_token']; ?>';

        // Debugging - Výpis do konzoly
        console.log("Odosielam:", {
            user_id: userId,
            role_id: document.querySelector(`#userTable tr td[id="role_id"]`).textContent.trim(),
            blocked_by_role: "<?= $_SESSION['role_id']; ?>",
            blocked_by_id: <?= $_SESSION['userid']; ?>,
            reason: reason,
            duration: blockDuration,
            custom_date: customDate,
            csrf_token: csrf_Token
        });


        fetch("blockUser.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "Access-Control-Allow-Origin": "*"
                },
                body: `user_id=${encodeURIComponent(userId)}
            &role_id=${encodeURIComponent(document.querySelector(`#userTable tr td[id="role_id"]`).textContent.trim())}
            &blocked_by_role=${encodeURIComponent("<?= $_SESSION['role_id']; ?>")}
            &blocked_by_id=${encodeURIComponent(<?= $_SESSION['userid']; ?>)}
            &reason=${encodeURIComponent(reason)}
            &duration=${encodeURIComponent(blockDuration)}
            &custom_date=${encodeURIComponent(customDate)}
            &csrf_token=${encodeURIComponent(csrf_Token)}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP chyba! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log("Odpoveď servera:", data);
                if (data.status === "success") {
                    populateTable();
                    alert("Používateľ bol úspešne zablokovaný.");
                } else {
                    alert("Chyba: " + data.message);
                }
            })
            .catch(error => {
                console.error("Chyba pri odosielaní požiadavky:", error);
                alert("Chyba pri komunikácii so serverom. Skontrolujte pripojenie alebo skúste znova.");
            });
    }



    async function searchUsers() {
        console.log("BlockUser function called");
        const searchQuery = document.getElementById('searchUser').value; // Hľadaný text (meno, email, ID)
        const roleFilter = document.getElementById('roleFilter').value; // Filtrovanie podľa role
        const blockFilter = document.getElementById('blockFilter').value; // Filtrovanie podľa blokovania
        const csrf_Token = '<?= $_SESSION['csrf_token']; ?>'; // CSRF token zo session
        const tbody = document.querySelector("#userTable tbody");

        tbody.innerHTML = ""; // Vyčistenie tabuľky

        // Vytvorenie tela požiadavky pre POST
        const formData = new FormData();
        formData.append('searchQuery', searchQuery);
        formData.append('roleFilter', roleFilter);
        formData.append('blockFilter', blockFilter);
        formData.append('csrf_token', csrf_Token);

        try {
            const response = await fetch('search_users.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            data.forEach(user => {
                let row = document.createElement('tr');
                row.innerHTML = `
                <tr class="align-middle">
                    <td id="id">${user.ID}</td>
                    <td id="name">${user.name}</td>
                    <td id="email">${user.email}</td>
                    <td id="created">${user.created}</td>
                    <td id="last_logg">${user.last_logg}</td>
                    <td id="role_id">${user.role_id}</td>
                    <td id="blocked">${user.blocked == 0 ? 'Aktívny' : 'Zablokovaný'}</td>
                    <td>
                        <button class="btn btn-sm ${user.blocked == 0 ? 'btn-danger' : 'btn-success'}"
                            onclick="handleBlockClick(${user.ID}, ${user.blocked})">
                            ${user.blocked == 0 ? 'Zablokovať' : 'Odblokovať'}
                        </button>
                    </td>
                </tr>
            `;
                tbody.appendChild(row);
            });

        } catch (error) {
            console.error('Chyba pri načítaní údajov:', error);
        }
    }


    async function populateTable() {
        const tbody = document.querySelector("#userTable tbody");
        tbody.innerHTML = ""; // Vyčistenie tabuľky

        try {
            const response = await fetch("get_users.php");
            if (!response.ok) {
                throw new Error(`Chyba HTTP: ${response.status}`);
            }

            const users = await response.json();
            console.log("Načítané dáta:", users);

            users.forEach(user => {
                const row = `
        <tr class="align-middle">
            <td id="id">${user.ID} </td>
            <td id="name">${user.name} </td>
            <td id="email">${user.email} </td>
            <td id="created">${user.created} </td>
            <td id="last_logg">${user.last_logg} </td>
            <td id="role_id">${user.role_id} </td>
            <td id="blocked">${user.blocked == 0 ? 'Aktívny' : 'Zablokovaný'} </td>
            <td>
                <button class="btn btn-sm ${user.blocked == 0 ? 'btn-danger' : 'btn-success'}"
                    onclick="handleBlockClick(${user.ID}, ${user.blocked})">
                    ${user.blocked == 0 ? 'Zablokovať' : 'Odblokovať'}
                </button>
            </td>
        </tr>
    `;
                tbody.insertAdjacentHTML("beforeend", row);
            });

        } catch (error) {
            console.error("Chyba pri načítavaní dát:", error);
        }
    }


    async function handleBlockClick(userId, blockedStatus) {
        if (blockedStatus === 0) {
            openBlockModal(userId); // Ak je používateľ aktívny, otvorí sa modal
        } else {
            unblockUser(userId); // Ak je používateľ zablokovaný, odblokuje ho bez otvorenia modalu
        }
    }
    let selectedUserId = null;

    function openBlockModal(userId) {
        selectedUserId = userId; // Uložíme ID používateľa
        var modal = new bootstrap.Modal(document.getElementById('blockUserModal'));
        modal.show();
    }

    document.getElementById("confirmBlockBtn").addEventListener("click", function() {
        let duration = document.getElementById("blockDuration").value;
        console.log(`Používateľ ${selectedUserId} bude zablokovaný na ${duration}`);
        // Skryť modal po potvrdení
        var modal = bootstrap.Modal.getInstance(document.getElementById('blockUserModal'));
        modal.hide();
    });
    document.addEventListener("DOMContentLoaded", () => {
        populateTable();
    });
</script>

</html>