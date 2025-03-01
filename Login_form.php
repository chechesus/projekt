<?php
require_once 'api/session.php';
require 'inc/header.php';
?>

<body>
  <div class="grid-container">
    <?php require_once 'website_elements/menu.php'; ?>
  </div>

  <div class="login-box">
    <h2>Prihlasenie</h2>

    <form id="login-form" action="api/login.php" method="post">
      <input type="text" name="identifier" placeholder="Meno / mail " required autocomplete="on">

      <div class="password-container">
        <input type="password" name="password" id="password" placeholder="Heslo" required autocomplete="on">
        <i id="togglePassword" class="toggle-icon fas fa-eye"></i>
      </div>

      <div class="switch">
        <input id="s1-14" type="checkbox" />
        <label for="s1-14">
          <span class="slider"></span>
          Zapamätať Prihlásenie
        </label>
      </div>




      <input type="submit" value="Prihlásiť sa">
      <br>
      <div class="register-link">
        <a href="reg_form.php" id="show-register">Registrovať nový účet</a>
      </div>
      <?php
      // Add CSRF token
      $csrf_token = bin2hex(random_bytes(32));
      $_SESSION['csrf_token'] = $csrf_token;
      ?>
      <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    </form>

  </div>

  <?php
  require_once 'website_elements/footer.php';
  $_SESSION['loggedin'] = 0;
  $_SESSION['userid'] = 0;
  $_SESSION['role_id'] = 0;
  ?>

  <script>
    // Nastavenie konfigurácie, ak potrebuješ ďalšie JS spracovanie
    window.wsConfig = {
      userId: <?= json_encode($userId ?? "") ?>,
      roleId: <?= json_encode($_SESSION['userid']  ?? '') ?>,
      csrfToken: <?= json_encode($_SESSION['csrf_token'] ?? ''); ?>
    };
  </script>
  <script src="/projekt/scripts.js" type="module">
    import {
      setupPasswordToggleListeners
    } from './scripts.js';

    document.addEventListener("DOMContentLoaded", function() {
      setupPasswordToggleListeners();
    });
  </script>

  <!-- Klientská validácia formulára -->
  <script>
    document.getElementById('login-form').addEventListener('submit', function(e) {
      const identifierInput = document.getElementsByName('identifier')[0];
      const passwordInput = document.getElementsByName('password')[0];

      const identifier = identifierInput.value.trim();
      const password = passwordInput.value.trim();
      let valid = true;
      let errorMessages = [];

      // Ak identifikátor obsahuje "@", overujeme ako e-mail
      if (identifier.indexOf('@') !== -1) {
        // Jednoduchý regex pre email
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(identifier)) {
          valid = false;
          errorMessages.push("Zadajte platnú e-mail adresu.");
        }
      } else {
        // Inak overíme, že identifikátor obsahuje len povolené znaky (písmená, čísla, podčiarknutia) a má aspoň 3 znaky
        const usernameRegex = /^[a-zA-Z0-9_]{3,}$/;
        if (!usernameRegex.test(identifier)) {
          valid = false;
          errorMessages.push("Meno môže obsahovať iba písmená, čísla a podčiarknutia a musí mať aspoň 3 znaky.");
        }
      }

      // Overenie hesla (minimálne 6 znakov)
      if (password.length < 6) {
        valid = false;
        errorMessages.push("Heslo musí obsahovať aspoň 6 znakov.");
      }

      // Ak nie je validácia úspešná, zastavíme odoslanie formulára
      if (!valid) {
        e.preventDefault();
        alert(errorMessages.join("\n"));
        return false;
      }

      // Poznámka: Ochrana proti SQL injection sa rieši na server-side pomocou prepared statements.
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const togglePassword = document.getElementById("togglePassword");
      const passwordField = document.getElementById("password");

      togglePassword.addEventListener("click", () => {
        // Prepnutie typu inputu
        const type = passwordField.type === "password" ? "text" : "password";
        passwordField.type = type;

        // Zmena ikony
        togglePassword.classList.toggle("fa-eye");
        togglePassword.classList.toggle("fa-eye-slash");
      });

      // Automatické skrytie hesla pri písaní
      passwordField.addEventListener("input", () => {
        if (passwordField.type === "text") {
          passwordField.type = "password";
          togglePassword.classList.add("fa-eye");
          togglePassword.classList.remove("fa-eye-slash");
        }
      });
    });
  </script>
</body>

</html>