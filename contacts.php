<?php include 'api/session.php';?> 
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Kontakty</title>
    <!-- Font Awesome icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
<div class="grid-container">
<?php include 'website_elements/menu.php';?> 
</div>
    
<div class="login-box">
            <h2>Kontaktný formulár</h2>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <label for="name">Meno:</label>
                <input type="text" id="name" name="name" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="message">Správa:</label>
                <textarea id="message" name="message" required></textarea>
                <button type="submit" name="submit">Odoslať <i class="fas fa-paper-plane"></i></button>
            </form>
            <?php
            if (isset($_POST['submit'])) {
                $name = $_POST['name'];
                $email = $_POST['email'];
                $message = $_POST['message'];
                $to = 'your_email@example.com'; // replace with your email
                $subject = 'Contact Form Submission';
                $body = "Name: $name\nEmail: $email\nMessage: $message";
                if (mail($to, $subject, $body)) {
                    echo '<p class="success">Správa bola úspešne odoslaná!</p>';
                } else {
                    echo '<p class="error">Chyba pri odosielaní správy.</p>';
                }
            }
            ?>
        </div>

<?php include 'website_elements/footer.php';?>
</body>
</html>