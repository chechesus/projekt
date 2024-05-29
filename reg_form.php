<!DOCTYPE html>
<html lang="sk">
<?php include 'session.php';?> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Registrácia</title>
</head>

<body>
    <div class="grid-container">  
    <?php include 'menu.php';?> 
    </div>

    <div class="login-box">
        <h2>Registrácia</h2>
        <form action="register.php" method="post" enctype="multipart/form-data" >
            <input type="text" name="username" placeholder="Používateľské meno" required />
            <input type="text" name="nick" placeholder="Prezývka" required />
            <input type="text" name="mail" placeholder="Mailova adresa" required />
            <input type="password" name="password" id="password" placeholder="Heslo" required />
            <progress max="100" value="0" id="meter"></progress>
            <input type="password" name="password_check" placeholder="Zopakujte heslo" required />
            <input type="tel" name="tel" placeholder="Telefónne číslo" required />
            <input type="submit" value="Zaregistrovať ma" />
        </form>
    </div>

    <?php include 'footer.php';?>
    
    <script>
    var code = document.getElementById("password");

    var strengthbar = document.getElementById("meter");

    code.addEventListener("input", function(){
        checkpassword(code.value);
        strengthbar.value = calculateStrength(code.value);
    })

    function calculateStrength(password) {
        var strength = 0;
        if (password.match(/[a-z]+/)) {
            strength += 1;
        }
        if (password.match(/[A-Z]+/)) {
            strength += 1;
        }
        if (password.match(/[0-9]+/)) {
            strength += 1;
        }
        if (password.match(/[$@#&!]+/)) {
            strength += 1;
        }
        if (password.length < 6) {
            return 0;
        }
        if (password.length > 12) {
            return 100;
        }
        return strength * 25;
    }
    </script>
</body>
</html>