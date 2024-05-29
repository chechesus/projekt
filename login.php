<?php
require_once 'config.php';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    echo '<p>Problem s pripojením k databáze</p>';
}

// Get user input
$identifier = filter_input(INPUT_POST, 'identifier', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

// Prepare a statement to check if the user exists
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM uzivatelia WHERE name like ? OR mail like ? ");
$stmt->bind_param("ss", $identifier, $identifier);
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close();

// Check if the user exists
if ($count >= 1) {
    // Prepare a statement to retrieve the user's information
    $stmt = $conn->prepare("SELECT password, name FROM uzivatelia WHERE name = ? OR mail = ?");
    $stmt->bind_param("ss", $identifier, $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // Verify the password
    if (password_verify($password, $user["password"])) {    
        // Set session variables or cookies to store user information
        session_start();
        $_SESSION["loggedin"] = true;
        $_SESSION["userid"] = $user["id"];
        $_SESSION["username"] = $user["name"];
        $success = true;

        // Redirect to the "my account" page
        header('Location: index.php');
        exit;
    } else {
        // Display an error message if the password is incorrect
        echo '<p>Nesprávne heslo </p>';
        echo '<script>
        setTimeout(function(){
                window.location.href = "Login_form.php";
            }, 2000);     
        </script>';
    }    
} else {//the user doesn't exist
    echo '<p>Pre takýto účet neexistuje záznam, prosím zaregistrujte sa!</p>';
    echo '<p>Za malú chvíľu budete presmerovaný na registráciu</p>';
    echo '<script>
            setTimeout(function(){
                window.location.href = "reg_form.php";
            }, 2000);     
        </script>';
}

// Close the database connection
$conn->close();
exit;