<div class="logo">
    <a href="index.php">
        <img src="/projekt/images/logo.jpg" alt="logo" class="logo">
    </a>
</div>

<header class="primary">
    <nav>
        <ul>
            <li><a href="/projekt/index.php">Domov</a></li>
            <li><a href="/projekt/articles-list.php">Články</a></li>
            <li><a href="/projekt/gallery/gallery.php">Galéria</a></li>
            <li><a href="/projekt/contacts.php">Kontakt</a></li>
        </ul>
    </nav>
</header>
<div>

</div>

<?php if (isset($_SESSION["loggedin"])): ?>
    <?php
    // Determine the URL to redirect to based on the user's role
    $redirectUrl = "/projekt/index.php"; // default value
    if (isset($_SESSION['role_id'])) {
        switch ($_SESSION['role_id']) {
            case 1:
                $redirectUrl = "/projekt/cms/admin.php";
                break;
            case 2:
                $redirectUrl = "/projekt/cms/user_dashboard.php";
                break;
            case 3:
                $redirectUrl = "/projekt/cms/moderator_dashboard.php";
                break;
        }
    }
    ?>
    <div class="user_ico">
        <div>
            <!-- The href is set so that even if JavaScript fails, the link works -->
            <a href="<?php echo $redirectUrl; ?>" onclick="redirectToPage(); return false;">
                <img src="/projekt/images/user_ico.png" alt="User Icon" class="icon-image">
                <p><?php echo htmlspecialchars($_SESSION["name"]); ?></p>
            </a>
            <script>
                // Attach the function to the global window object
                window.redirectToPage = function() {
                    window.location.href = "<?php echo $redirectUrl; ?>";
                }
            </script>
        </div>
        <div>
            <a href="/projekt/api/logout.php" id="logoutButton">
                <img src="/projekt/images/logout.png" alt="Logout Icon" class="icon-image">
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="user_ico">
        <a href="Login_form.php">
            <img src="/projekt/images/user_ico.png" alt="User Icon" class="icon-image">
            <p>Prihlásiť sa</p>
        </a>
    </div>
<?php endif; ?>

<script>
    // Ensure the logout functionality only runs if wsConfig is defined and indicates the user is logged in.
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof wsConfig !== "undefined" && wsConfig.loggedin === 1) {
            var logoutBtn = document.getElementById("logoutButton");
            if (logoutBtn) {
                logoutBtn.addEventListener("click", function(event) {
                    event.preventDefault();
                    window.location.href = "/projekt/api/logout.php";
                });
            }
        }
    });
</script>
