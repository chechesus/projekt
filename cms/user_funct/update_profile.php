<?php

use Google\Service\Dfareporting\UserRole;

header('Content-Type: application/json');
require_once 'C:\xampp\htdocs\projekt\api\session.php';

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate inputs
    $fullName = trim($_POST['fullName'] ?? '');
    $nickname = trim($_POST['nickname'] ?? '');
    $bio      = trim($_POST['bio'] ?? '');
    $password = $_POST['password'] ?? '';
    $profilePic = $_FILES['profilePic'] ?? null;

    // Validate password if provided
    if (!empty($password)) {
        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'error' => 'Heslo musí mať aspoň 6 znakov.']);
            exit;
        }
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    }

    $userId = $_SESSION['userid'];
    $userRole = $_SESSION['role_id'];

    // Update user profile (update password only if provided)
    if (isset($hashedPassword)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, nick = ?, bio = ?, password = ? WHERE id = ? AND role_id = ?");
        $stmt->bind_param("ssssii", $fullName, $nickname, $bio, $hashedPassword, $userId, $userRole);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, nick = ?, bio = ? WHERE id = ? AND role_id = ?");
        $stmt->bind_param("sssii", $fullName, $nickname, $bio, $userId, $userRole);
    }

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'error' => 'Chyba pri aktualizácii údajov.']);
        exit;
    }

    // Process profile picture upload if a file was provided
    if ($profilePic && $profilePic['error'] === UPLOAD_ERR_OK) {
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($profilePic['type'], $allowedTypes)) {
            echo json_encode(['success' => false, 'error' => 'Neplatný formát obrázka.']);
            exit;
        }

        // Generate a unique filename
        $extension = pathinfo($profilePic['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid('profile_', true) . '.' . $extension;

        // Define upload directory and ensure it exists
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $targetFilePath = $uploadDir . $newFileName;
        if (!move_uploaded_file($profilePic["tmp_name"], $targetFilePath)) {
            echo json_encode(['success' => false, 'error' => 'Chyba pri nahrávaní obrázka.']);
            exit;
        }

        // Use the full path to the Python interpreter
        $pythonExecutable = "C:\Users\janko\AppData\Local\Programs\Python\Python313\python.exe"; // Update to your Python path
        $pythonScriptPath = "drive_upload.py";

        if (!file_exists($pythonScriptPath)) {
            error_log("ERROR: Python script not found: " . $pythonScriptPath);
            echo json_encode(['success' => false, 'error' => 'Python script not found.']);
            exit;
        }

        if (!isset($targetFilePath) || !file_exists($targetFilePath)) {
            error_log("ERROR: Target file not found: " . (isset($targetFilePath) ? $targetFilePath : 'undefined'));
            echo json_encode(['success' => false, 'error' => 'Target file not found.']);
            exit;
        }

        // Build the command
        $command = $pythonExecutable . " " . escapeshellarg($pythonScriptPath) . " " . escapeshellarg($targetFilePath);

        // Set up descriptor spec for pipes
        $descriptorspec = [
            0 => ["pipe", "r"],  // stdin is a pipe that the child will read from
            1 => ["pipe", "w"],  // stdout is a pipe that the child will write to
            2 => ["pipe", "w"]   // stderr is a pipe that the child will write to
        ];

        // Open the process
        $process = proc_open($command, $descriptorspec, $pipes);

        if (!is_resource($process)) {
            error_log("ERROR: Could not start Python process.");
            echo json_encode(['success' => false, 'error' => 'Could not start Python process.']);
            exit;
        }

        // Close the child's input immediately (if not used)
        fclose($pipes[0]);

        // Read the output and error streams
        $output = stream_get_contents($pipes[1]);
        $errorOutput = stream_get_contents($pipes[2]);

        // Close the pipes
        fclose($pipes[1]);
        fclose($pipes[2]);

        // Wait for the process to terminate
        $return_value = proc_close($process);

        error_log("DEBUG: Return value: " . $return_value);
        error_log("DEBUG: Python output: " . $output);
        error_log("DEBUG: Python error output: " . $errorOutput);

        $driveUrl = trim($output);
        if (!$driveUrl) {
            error_log("ERROR: driveUrl is empty. Python output: " . $output . " Error output: " . $errorOutput);
            echo json_encode(['success' => false, 'error' => 'Chyba pri nahrávaní obrázka na Google Drive.' . "ERROR: driveUrl is empty. Python output: " . $output . " Error output: " . $errorOutput]);
            exit;
        }

        // Update the profile picture field in the database with the Google Drive URL
        $stmtPic = $conn->prepare("UPDATE users SET profile_picture = ? WHERE id = ? AND role_id = ?");
        $stmtPic->bind_param("sii", $driveUrl, $userId, $userRole);
        if (!$stmtPic->execute()) {
            echo json_encode(['success' => false, 'error' => 'Chyba pri aktualizácii obrázka v databáze.']);
            exit;
        }
    }

    echo json_encode(['success' => true, 'message' => 'Profil bol aktualizovany.']);
    exit;
}

echo json_encode($response);
