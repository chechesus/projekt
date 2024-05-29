<div class="logo">
    <a href="index.php">
        <img src="images/logo.jpg" alt="logo" class="logo">
    </a>
</div>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Domov</a></li>
            <?php if (isset($_SESSION["username"])):?>
                <li><a><?php echo htmlspecialchars($_SESSION["username"]);?></a></li>
            <?php else:?>
                <li><a href="Login_form.php">Prihásiť sa </a></li>
            <?php endif;?>
            <li><a href="content.php">Príspevky</a></li>
            <li><a href="galery.php">Galéria</a></li>
            <li><a href="contacts.php">Kontakt</a></li>
        </ul>
    </nav>
</header>
<div>

</div>
    <?php if (isset($_SESSION["username"])):?>
        <div class="user_ico">
        <img src="images/user_ico.png" alt="User Icon" class="icon-image">
        <a href="logout.php">
            <img src="images/logout.png" alt="Logout Icon" class="icon-image">
        </a>
        </div>
    <?php else:?>
        <h2 id = "reg_msg">Neprihlásený</h2>
    <?php endif;?>
<script>
    document.getElementById("logoutButton").addEventListener("click", function(event) {
    event.preventDefault(); 
    window.location.href = "logout.php"; 
    });
</script>    

