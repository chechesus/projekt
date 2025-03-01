<?php
require_once 'C:\xampp\htdocs\projekt\api\session.php';
require_once '../../auth/auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Vlaky - Adminský panel</title>
  <link rel="icon" href="./../../../images/logo.jpg">
  <meta name="csrf-token" content="<?= $_SESSION['csrf_token']; ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Fonts a štýly -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css" integrity="sha256-dSokZseQNT08wYEWiz5iLI8QPlKxG+TswNRD8k35cpg=" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" integrity="sha256-Qsx5lrStHZyR9REqhUF8iQt73X06c8LGIUPzpOhwRrI=" crossorigin="anonymous">
  <link rel="stylesheet" href="/projekt/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php require_once 'C:\xampp\htdocs\projekt\cms\includes\nav.php'; ?>
    <aside class="app-sidebar">
      <aside class="sidebar">
        <?php require_once '/xampp/htdocs/projekt/cms/sidebar-menu/index.php'; ?>
      </aside>
    </aside>
    <main class="app-main">
      <div class="app-content">
        <div class="container-fluid">
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
                  <div class="col-md-2">
                    <button class="btn btn-primary" onclick="searchUsers()">Vyhľadať</button>
                  </div>
                  <div class="col-md-2">
                    <button class="btn btn-success" onclick="openAddUserModal()">Pridať používateľa</button>
                  </div>
                </div>
              </div>
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
                    <!-- Dynamicky načítané riadky -->
                  </tbody>
                </table>
              </div>
              <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-end">
                  <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
              </div>
            </div> <!-- /.card -->
          </div>
        </div>
      </div>
    </main>
  </div>

  <!-- Modal pre vymazanie používateľa -->
  <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content" style="padding: 20px;">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUserModalLabel">Vymazať používateľa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvoriť"></button>
        </div>
        <div class="modal-body">
          <p>Ste si istý, že chcete vymazať tohto používateľa?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušiť</button>
          <button type="button" class="btn btn-danger" onclick="deleteUser()">Potvrdiť vymazanie</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal pre úpravu používateľa s live validáciou -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editUserForm">
      <div class="modal-content" style="padding: 20px;">
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Upraviť používateľa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvoriť"></button>
        </div>
        <div class="modal-body">
          <!-- Kontajner pre chybové hlásenia -->
          <div id="editUserErrorMessages" style="color: red; margin-bottom: 10px;"></div>
          <input type="hidden" id="editUserId" name="user_id">
          <div class="mb-3">
            <label for="editName" class="form-label">Meno</label>
            <input type="text" class="form-control" id="editName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="editNick" class="form-label">Nick</label>
            <input type="text" class="form-control" id="editNick" name="nick">
          </div>
          <div class="mb-3">
            <label for="editEmail" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="editEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="editTel" class="form-label">Telefón</label>
            <input type="text" class="form-control" id="editTel" name="tel">
          </div>
          <div class="mb-3">
            <label for="editRoleId" class="form-label">ID role</label>
            <select class="form-control" id="editRoleId" name="role_id">
              <option value="1">Admin</option>
              <option value="3">Moderátor</option>
              <option value="2">Používateľ</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="editPassword" class="form-label">Nové heslo (nepovinné)</label>
            <div class="password-container" style="position: relative;">
              <input type="password" class="form-control" id="editPassword" name="password">
              <i id="toggleEditPassword" class="toggle-icon fas fa-eye" style="cursor: pointer; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
            </div>
            <!-- Progress bar pre silu hesla -->
            <progress max="100" value="0" id="editPasswordMeter" style="width: 100%;"></progress>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
          <button type="button" class="btn btn-primary" onclick="updateUser()">Uložiť zmeny</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- JavaScript pre live validáciu v modále pre úpravu používateľa -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const editPasswordField = document.getElementById("editPassword");
  const editPasswordMeter = document.getElementById("editPasswordMeter");
  const editEmailField = document.getElementById("editEmail");
  const editNickField = document.getElementById("editNick");
  const toggleEditPasswordIcon = document.getElementById("toggleEditPassword");
  const editErrorContainer = document.getElementById("editUserErrorMessages");

  // Pomocné funkcie pre spracovanie chýb
  function addEditError(message) {
    if (!editErrorContainer.innerHTML.includes(message)) {
      editErrorContainer.innerHTML += `<div class="error">${message}</div>`;
    }
  }

  function removeEditError(message) {
    const errors = Array.from(document.querySelectorAll("#editUserErrorMessages .error"));
    errors.forEach(err => {
      if (err.textContent === message) {
        err.remove();
      }
    });
  }

  // Funkcia na kontrolu sily hesla
  function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) {
      if (/[a-z]/.test(password)) strength++;
      if (/[A-Z]/.test(password)) strength++;
      if (/[0-9]/.test(password)) strength++;
      if (/[@$!%*?&#]/.test(password)) strength++;
    }
    return (strength / 4) * 100;
  }

  // Event listener pre heslo
  editPasswordField.addEventListener("input", function() {
    const password = this.value;
    const strength = checkPasswordStrength(password);
    editPasswordMeter.value = strength;
    let errors = [];
    if (password && password.length < 8) {
      errors.push("Heslo musí mať minimálne 8 znakov.");
    }
    if (password && !/[a-z]/.test(password)) {
      errors.push("Heslo musí obsahovať aspoň jedno malé písmeno.");
    }
    if (password && !/[A-Z]/.test(password)) {
      errors.push("Heslo musí obsahovať aspoň jedno veľké písmeno.");
    }
    if (password && !/[0-9]/.test(password)) {
      errors.push("Heslo musí obsahovať aspoň jedno číslo.");
    }
    if (password && !/[@$!%*?&#]/.test(password)) {
      errors.push("Heslo musí obsahovať aspoň jeden špeciálny znak (@, $, !, %, *, ?, & alebo #).");
    }
    // Aktualizácia chybového kontajnera
    editErrorContainer.innerHTML = "";
    errors.forEach(error => addEditError(error));
  });

  // Kontrola formátu e-mailu
  editEmailField.addEventListener("input", function() {
    const email = this.value.trim();
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      addEditError("E-mailová adresa musí obsahovať @ a mať platný formát.");
    } else {
      removeEditError("E-mailová adresa musí obsahovať @ a mať platný formát.");
    }
  });

  // Asynchrónna kontrola prezývky (predpokladáme endpoint check_nick.php)
  editNickField.addEventListener("input", async function() {
    const nick = this.value.trim();
    if (nick) {
      try {
        const response = await fetch(`check_nick.php?nick=${encodeURIComponent(nick)}`);
        const { exists } = await response.json();
        if (exists) {
          addEditError("Tento nick je už obsadený.");
        } else {
          removeEditError("Tento nick je už obsadený.");
        }
      } catch (error) {
        console.error("Chyba pri overovaní nicku:", error);
      }
    }
  });

  // Prepínanie viditeľnosti hesla
  toggleEditPasswordIcon.addEventListener("click", function() {
    const type = editPasswordField.type === "password" ? "text" : "password";
    editPasswordField.type = type;
    toggleEditPasswordIcon.classList.toggle("fa-eye");
    toggleEditPasswordIcon.classList.toggle("fa-eye-slash");
  });
});
</script>

<!-- Modal pre pridanie nového používateľa -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="padding: 20px;">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserModalLabel">Pridať nového používateľa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zatvoriť"></button>
      </div>
      <div class="modal-body">
        <!-- Kontajner pre chyby validácie -->
        <div id="addUserErrorMessages" style="color: red; margin-bottom: 10px;"></div>
        <form id="addUserForm">
          <div class="mb-3">
            <label for="addName" class="form-label">Meno</label>
            <input type="text" class="form-control" id="addName" name="name" required>
          </div>
          <div class="mb-3">
            <label for="addNick" class="form-label">Nick</label>
            <input type="text" class="form-control" id="addNick" name="nick">
          </div>
          <div class="mb-3">
            <label for="addEmail" class="form-label">E-mail</label>
            <input type="email" class="form-control" id="addEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="addTel" class="form-label">Telefón</label>
            <input type="text" class="form-control" id="addTel" name="tel">
          </div>
          <div class="mb-3">
            <label for="addRoleId" class="form-label">ID role</label>
            <select class="form-control" id="addRoleId" name="role_id">
              <option value="2">Používateľ</option>
              <option value="3">Moderátor</option>
              <option value="1">Admin</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="addPassword" class="form-label">Heslo</label>
            <div class="password-container">
              <input type="password" class="form-control" id="addPassword" name="password" required>
              <i id="toggleAddPassword" class="toggle-icon fas fa-eye" style="cursor:pointer;"></i>
            </div>
            <!-- Progress bar pre silu hesla -->
            <progress max="100" value="0" id="addPasswordMeter" style="width: 100%;"></progress>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavrieť</button>
        <button type="button" class="btn btn-primary" onclick="addUser()">Uložiť</button>
      </div>
    </div>
  </div>
</div>

<!-- JavaScript pre priebežnú validáciu vo vnútri modálu -->
<script>
  // Pomocné funkcie na pridávanie a odstraňovanie chýb
  function addModalError(message) {
    const errorContainer = document.getElementById("addUserErrorMessages");
    if (!errorContainer.innerHTML.includes(message)) {
      errorContainer.innerHTML += `<div class="error">${message}</div>`;
    }
  }

  function removeModalError(message) {
    const errors = Array.from(document.querySelectorAll("#addUserErrorMessages .error"));
    errors.forEach(err => {
      if (err.textContent === message) {
        err.remove();
      }
    });
  }

  // Funkcia na kontrolu sily hesla (podobne ako vo vašej registračnej forme)
  function checkPasswordStrength(password) {
    let strength = 0;
    if (password.length >= 8) {
      if (/[a-z]/.test(password)) strength++;
      if (/[A-Z]/.test(password)) strength++;
      if (/[0-9]/.test(password)) strength++;
      if (/[@$!%*?&#]/.test(password)) strength++;
    }
    return (strength / 4) * 100;
  }

  // Nastavenie event listenerov pre heslo, e-mail a nick vo vnútri modálu
  document.addEventListener("DOMContentLoaded", function() {
    const addPasswordField = document.getElementById("addPassword");
    const addPasswordMeter = document.getElementById("addPasswordMeter");
    const addEmailField = document.getElementById("addEmail");
    const addNickField = document.getElementById("addNick");
    const toggleAddPasswordIcon = document.getElementById("toggleAddPassword");

    // Kontrola sily hesla
    addPasswordField.addEventListener("input", function() {
      const password = this.value;
      const strength = checkPasswordStrength(password);
      addPasswordMeter.value = strength;
      const errors = [];
      if (password.length < 8) {
        errors.push("Heslo musí mať minimálne 8 znakov.");
      }
      if (!/[a-z]/.test(password)) {
        errors.push("Heslo musí obsahovať aspoň jedno malé písmeno.");
      }
      if (!/[A-Z]/.test(password)) {
        errors.push("Heslo musí obsahovať aspoň jedno veľké písmeno.");
      }
      if (!/[0-9]/.test(password)) {
        errors.push("Heslo musí obsahovať aspoň jedno číslo.");
      }
      if (!/[@$!%*?&#]/.test(password)) {
        errors.push("Heslo musí obsahovať aspoň jeden špeciálny znak (@, $, !, %, *, ?, & alebo #).");
      }
      // Vymažeme staré chybové hlásenia
      document.getElementById("addUserErrorMessages").innerHTML = "";
      errors.forEach(error => {
        addModalError(error);
      });
    });

    // Kontrola formátu e-mailu
    addEmailField.addEventListener("input", function() {
      const email = this.value.trim();
      if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        addModalError("E-mailová adresa musí obsahovať @ a mať platný formát.");
      } else {
        removeModalError("E-mailová adresa musí obsahovať @ a mať platný formát.");
      }
    });

    // Kontrola, či prezývka existuje (predpokladáme endpoint check_nick.php)
    addNickField.addEventListener("input", async function() {
      const nick = this.value.trim();
      if (nick) {
        try {
          const response = await fetch(`check_nick.php?nick=${encodeURIComponent(nick)}`);
          const { exists } = await response.json();
          if (exists) {
            addModalError("Tento nick je už obsadený.");
          } else {
            removeModalError("Tento nick je už obsadený.");
          }
        } catch (error) {
          console.error("Chyba pri overovaní nicku:", error);
        }
      }
    });

    // Funkcia na prepínanie viditeľnosti hesla
    toggleAddPasswordIcon.addEventListener("click", function() {
      const type = addPasswordField.type === "password" ? "text" : "password";
      addPasswordField.type = type;
      toggleAddPasswordIcon.classList.toggle("fa-eye");
      toggleAddPasswordIcon.classList.toggle("fa-eye-slash");
    });
  });
</script>

  <!-- Načítanie Bootstrap JS a ďalších knižníc -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/projekt/scripts.js"></script>
  <script>
    // Funkcia na otvorenie modálu pre pridanie používateľa
    function openAddUserModal() {
      const addModalEl = document.getElementById('addUserModal');
      if (!addModalEl) {
        console.error("addUserModal element nebol nájdený.");
        return;
      }
      const addModal = new bootstrap.Modal(addModalEl);
      addModal.show();
    }

    // Funkcia na otvorenie modálu pre vymazanie používateľa
    function openDeleteModal(userId) {
      selectedUserId = userId;
      const deleteModalEl = document.getElementById('deleteUserModal');
      if (!deleteModalEl) {
        console.error("deleteUserModal element nebol nájdený.");
        return;
      }
      const deleteModal = new bootstrap.Modal(deleteModalEl);
      deleteModal.show();
    }

    // Globalná premenná pre vybrané ID používateľa (na vymazanie)
    let selectedUserId = null;

    // Funkcia na vymazanie používateľa (volá sa z modálu)
    async function deleteUser() {
      if (!selectedUserId) {
        alert("Nebolo vybrané ID používateľa.");
        return;
      }
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      try {
        const response = await fetch("delete_user.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `user_id=${encodeURIComponent(selectedUserId)}&csrf_token=${encodeURIComponent(csrfToken)}`
        });
        const result = await response.json();
        if (result.status === "success") {
          alert("Používateľ bol úspešne vymazaný.");
          const deleteModalEl = document.getElementById('deleteUserModal');
          const deleteModal = bootstrap.Modal.getInstance(deleteModalEl);
          if(deleteModal) { deleteModal.hide(); }
          populateTable();
        } else {
          alert("Chyba pri vymazávaní používateľa: " + (result.message || ""));
        }
      } catch (error) {
        console.error("Chyba pri deleteUser:", error);
        alert("Chyba pri komunikácii so serverom.");
      }
    }

    // Funkcia na otvorenie modálu pre úpravu používateľa
    function openEditModal(userId) {
      // Predpokladáme, že detail používateľa načítame z endpointu "get_user.php"
      fetch("get_single_user.php?user_id=" + userId)
        .then(response => response.json())
        .then(user => {
          // Predvyplníme formulár modálu
          document.getElementById("editUserId").value = user.ID;
          document.getElementById("editName").value = user.name;
          document.getElementById("editNick").value = user.nick || "";
          document.getElementById("editEmail").value = user.email;
          document.getElementById("editTel").value = user.tel;
          document.getElementById("editRoleId").value = user.role_id;
          document.getElementById("editPassword").value = "";
          // Otvoríme modál
          var editModalEl = document.getElementById("editUserModal");
          var editModal = new bootstrap.Modal(editModalEl);
          editModal.show();
        })
        .catch(error => console.error("Chyba pri načítaní používateľa:", error));
    }

    // Funkcia na aktualizáciu používateľa
    async function updateUser() {
      const form = document.getElementById("editUserForm");
      const formData = new FormData(form);
      const meta = document.querySelector('meta[name="csrf-token"]');
      if (meta) {
        const csrfToken = meta.getAttribute('content');
        formData.append('csrf_token', csrfToken);
      }
      try {
        const response = await fetch("update_user.php", {
          method: "POST",
          body: formData
        });
        const result = await response.json();
        if (result.status === "success") {
          alert("Používateľ bol úspešne aktualizovaný.");
          var editModalEl = document.getElementById("editUserModal");
          var editModal = bootstrap.Modal.getInstance(editModalEl);
          if(editModal) { editModal.hide(); }
          populateTable();
        } else {
          alert("Chyba pri aktualizácii používateľa: " + (result.message || ""));
        }
      } catch (error) {
        console.error("Chyba pri updateUser:", error);
        alert("Chyba pri komunikácii so serverom.");
      }
    }

    // Funkcie na načítanie používateľov a aktualizáciu tabuľky
    async function populateTable() {
      const tbody = document.querySelector("#userTable tbody");
      tbody.innerHTML = "";
      try {
        const response = await fetch("get_users.php");
        if (!response.ok) {
          throw new Error(`Chyba HTTP: ${response.status}`);
        }
        const users = await response.json();
        users.forEach(user => {
          const row = `
            <tr class="align-middle">
              <td>${user.ID}</td>
              <td>${user.name}</td>
              <td>${user.email}</td>
              <td>${user.created}</td>
              <td>${user.last_logg}</td>
              <td>${user.role_id}</td>
              <td>${user.blocked == 0 ? 'Aktívny' : 'Zablokovaný'}</td>
              <td>
                <button class="btn btn-sm btn-primary" onclick="openEditModal(${user.ID})">Upraviť</button>
                <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${user.ID})">Vymazať</button>
              </td>
            </tr>
          `;
          tbody.insertAdjacentHTML("beforeend", row);
        });
      } catch (error) {
        console.error("Chyba pri načítavaní dát:", error);
      }
    }

    async function searchUsers() {
      const searchQuery = document.getElementById('searchUser').value;
      const roleFilter = document.getElementById('roleFilter').value;
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const tbody = document.querySelector("#userTable tbody");
      tbody.innerHTML = "";
      const formData = new FormData();
      formData.append('searchQuery', searchQuery);
      formData.append('roleFilter', roleFilter);
      formData.append('csrf_token', csrfToken);
      try {
        const response = await fetch('search_users.php', {
          method: 'POST',
          body: formData
        });
        const data = await response.json();
        data.forEach(user => {
          let row = document.createElement('tr');
          row.innerHTML = `
            <td>${user.ID}</td>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.created}</td>
            <td>${user.last_logg}</td>
            <td>${user.role_id}</td>
            <td>${user.blocked == 0 ? 'Aktívny' : 'Zablokovaný'}</td>
            <td>
              <button class="btn btn-sm btn-primary" onclick="openEditModal(${user.ID})">Upraviť</button>
              <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${user.ID})">Vymazať</button>
            </td>
          `;
          tbody.appendChild(row);
        });
      } catch (error) {
        console.error('Chyba pri načítaní údajov:', error);
      }
    }

    document.addEventListener("DOMContentLoaded", () => {
      populateTable();
    });
  </script>
</body>
</html>
