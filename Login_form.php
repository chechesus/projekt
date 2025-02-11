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
        <input type="password" name="password" id="password" placeholder="Heslo" require autocomplete="on">
        <i id="togglePassword" class="toggle-icon fas fa-eye"></i>
      </div>


      <div class="checkbox-wrapper-14">
        <input id="s1-14" type="checkbox" name="remember_me" value="1">
        <label for="s1-14">Zapamätať Prihlásenie</label>
      </div>
      <input type="submit" value="Prihlásiť sa">
      <?php // Add CSRF token
      $csrf_token = bin2hex(random_bytes(32));
      $_SESSION['csrf_token'] = $csrf_token;
      ?>
      <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    </form>
    <div class="register-link">
      <a href="reg_form.php" id="show-register">Registrovať nový účet</a>
    </div>
  </div>

  <?php require_once 'website_elements/footer.php'; ?>

  <script type="module">
    import {setupPasswordToggleListeners } from './scripts.js';

document.addEventListener("DOMContentLoaded", function() {//safe ececution after html,css loads
    setupPasswordToggleListeners();
});

    const checkbox = document.getElementById('s1-14');
    checkbox.addEventListener('change', () => {
      if (checkbox.checked) {
        // local uloženie v prehliadači
        localStorage.setItem('identifier', document.getElementsByName('identifier')[0].value);
        localStorage.setItem('password', document.getElementsByName('password')[0].value);
      } else {
        // Remove login credentials from local storage
        localStorage.removeItem('identifier');
        localStorage.removeItem('password');
      }
    });
    
    
  </script>

</body>

</html>