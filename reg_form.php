<?php
require 'api/session.php';
require 'inc/header.php';
?>

<body>
    <div class="grid-container">
        <?php require 'website_elements/menu.php'; ?>
    </div>

    <div class="login-box">
        <h2>Registrácia</h2>
        <div id="error-messages" style="color: red;"></div>

        <form action="api/register.php" method="post" enctype="multipart/form-data">
            <input type="text" name="username" placeholder="Používateľské meno" required />
            <input type="text" name="nick" placeholder="Prezývka" required />
            <input type="text" name="mail" placeholder="Mailova adresa" required />

            <div class="password-container">
                <input type="password" name="password" id="password" placeholder="Heslo" required />
                <i id="togglePassword" class="toggle-icon fas fa-eye"></i>
            </div>

            <progress max="100" value="0" id="meter"></progress>

            <div class="password-container">
                <input type="password" name="password_check" id="password_check" placeholder="Zopakujte heslo" required />
                <i id="togglePasswordCheck" class="toggle-icon fas fa-eye"></i>
            </div>

            <input type="tel" name="tel" placeholder="Telefónne číslo" />
            <input type="submit" value="Zaregistrovať ma" />
        </form>
    </div>


    <?php require 'website_elements/footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    setupPasswordToggleListeners();
    setupPasswordStrengthChecker();
});
    function setupPasswordStrengthChecker() {
  try {
    var code = document.getElementById("password");
    var strengthbar = document.getElementById("meter");

    if (!code || !strengthbar) {
      console.error("Chýba element s ID 'password' alebo 'meter'.");
      return;
    }

    code.addEventListener("input", function () {
      strengthbar.value = calculateStrength(code.value);
    });

    function calculateStrength(password) {
      var strength = 0;

      if (password.length > 5) {
        if (password.match(/[a-z]/)) strength += 1;
        if (password.match(/[A-Z]/)) strength += 1;
        if (password.match(/[0-9]/)) strength += 1;
        if (password.match(/[$@#&!]/)) strength += 1;
        if (password.length > 12) strength += 1;
      }

      return (strength / 5) * 100; //vratenie percent 0-100
    }
  } catch (error) {}
}
function togglePasswordVisibility(passwordFieldId, toggleIconId) {
  const passwordField = document.getElementById(passwordFieldId);
  const toggleIcon = document.getElementById(toggleIconId);

  const type = passwordField.type === "password" ? "text" : "password";
  passwordField.type = type;

  // Toggle the eye icon
  toggleIcon.classList.toggle("fa-eye");
  toggleIcon.classList.toggle("fa-eye-slash");
}

function setupPasswordToggleListeners() {
  const passwordToggle = document.getElementById("togglePassword");
  const passwordCheckToggle = document.getElementById("togglePasswordCheck");

  passwordToggle.addEventListener("click", function () {
    togglePasswordVisibility("password", "togglePassword");
  });

  passwordCheckToggle.addEventListener("click", function () {
    togglePasswordVisibility("password_check", "togglePasswordCheck");
  });
}
document.getElementById("password").addEventListener("input", function() {
  const password = this.value;
  const strength = calculateStrength(password);

  if (strength < 60) {
    this.setCustomValidity("Heslo musí byť silnejšie. Použite väčšie písmená, číslice a špeciálne znaky.");
  } else {
    this.setCustomValidity(""); // Resetujeme hlášku
  }
});

function calculateStrength(password) {
  let strength = 0;

  if (password.length >= 8) {
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[@$!%*?&#]/.test(password)) strength++;
  }

  return (strength / 4) * 100;
}
const emailInput = document.querySelector('input[name="mail"]');
emailInput.addEventListener('input', () => {
  const email = emailInput.value.trim();
  if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    addError('E-mailová adresa musí obsahovať @ a mať platný formát.');
  } else {
    removeError('E-mailová adresa musí obsahovať @ a mať platný formát.');
  }
});
const telInput = document.querySelector('input[name="tel"]');
telInput.addEventListener('input', () => {
  const tel = telInput.value.trim();
  if (tel && (tel.length < 8 || tel.length > 15)) {
    addError('Telefónne číslo musí mať dĺžku medzi 8 a 15 znakmi.');
  } else {
    removeError('Telefónne číslo musí mať dĺžku medzi 8 a 15 znakmi.');
  }
});
const usernameInput = document.querySelector('input[name="username"]');
usernameInput.addEventListener('input', async () => {
  const username = usernameInput.value.trim();
  if (username) {
    const response = await fetch(`check_username.php?username=${encodeURIComponent(username)}`);
    const { exists } = await response.json();
    if (exists) {
      addError('Tento používateľ už existuje.');
    } else {
      removeError('Tento používateľ už existuje.');
    }
  }
});
const errorContainer = document.getElementById('error-messages');

function addError(message) {
  if (!errorContainer.innerHTML.includes(message)) {
    errorContainer.innerHTML += `<div class="error">${message}</div>`;
  }
}

function removeError(message) {
  const errors = Array.from(document.querySelectorAll('.error'));
  errors.forEach(err => {
    if (err.innerHTML === message) {
      err.remove();
    }
  });
}
function checkPasswordStrength(password) {
  let errors = [];

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

  return errors;
}
document.getElementById("password").addEventListener("input", function() {
  const password = this.value;
  const passwordErrors = checkPasswordStrength(password);

  // Zobrazíme všetky nesplnené požiadavky
  const errorContainer = document.getElementById("error-messages");
  errorContainer.innerHTML = ""; // Vymažeme staré hlásenia
  passwordErrors.forEach(error => {
    const errorElement = document.createElement("div");
    errorElement.classList.add("error");
    errorElement.textContent = error;
    errorContainer.appendChild(errorElement);
  });

  // Ak všetky požiadavky splnené, odstránime hlášky
  if (passwordErrors.length === 0) {
    errorContainer.innerHTML = "";
  }
});

</script>
</body>

</html>