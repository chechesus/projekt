<?php 
require 'api/session.php';
require 'inc/header.php';
?> 

<body>
<div class="grid-container">
<?php include 'website_elements/menu.php';?> 
</div>
    
<div class="login-box">
            <h2>Kontaktný formulár</h2>
            <form action="#" method="post">
            <input type="text" name="username" placeholder="Meno" required />
            <input type="text" name="mail" placeholder="Mailova adresa" required />
            <input type="tel" name="tel" placeholder="Telefónne číslo" required />
            <textarea name="text_field" cols="30" rows="10"></textarea>
            <input type="submit" name="submit" value="Poslať" />
            </form>
        </div>

<?php include 'website_elements/footer.php';?>
</body>
</html>