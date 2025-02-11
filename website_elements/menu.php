<div class="logo">
    <a href="index.php">
        <img src="images/logo.jpg" alt="logo" class="logo">
    </a>
</div>

<header>
    <nav>
        <ul>
            <li><a href="index.php">Domov</a></li>
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
        <div>
        <a href="cms/user_dashboard.php">
            <img src="images/user_ico.png" alt="User Icon" class="icon-image">
            <p><?php echo htmlspecialchars($_SESSION["username"]);?></p>
        </a>
        </div>
        <div>
        <a href="api/logout.php" id = "logoutButton">
            <img src="images/logout.png" alt="Logout Icon" class="icon-image">
        </a>
        </div>
    </div>
    <?php else:?>
        <div class="user_ico">
        <a href="Login_form.php" >
            <img src="images/user_ico.png" alt="User Icon" class="icon-image">
           <p>Prihlásiť sa</p> 
        </a>
        </div>
    <?php endif;?>
<script>//odhlasenie
    document.getElementById("logoutButton").addEventListener("click", function(event) {
    event.preventDefault(); 
    window.location.href = "api/logout.php"; 
    });
</script>    

