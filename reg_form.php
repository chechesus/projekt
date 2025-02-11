<?php 
require 'api/session.php';
require 'inc/header.php';
?>

<body>
    <div class="grid-container">  
    <?php require 'website_elements/menu.php';?> 
    </div>

    <div class="login-box">
        <h2>Registrácia</h2>

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

            <input type="tel" name="tel" placeholder="Telefónne číslo"/>
            <input type="submit" value="Zaregistrovať ma" />
        </form>
    </div>


    <?php require 'website_elements/footer.php';?>
    
    <script type="module">
    import { setupPasswordStrengthChecker, setupPasswordToggleListeners } from './scripts.js';

    document.addEventListener("DOMContentLoaded", function() {//safe ececution after html,css loads
        setupPasswordStrengthChecker();
        setupPasswordToggleListeners();
    });
    </script>
    
</body>
</html>