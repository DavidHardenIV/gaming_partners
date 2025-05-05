<?php
session_start();

// Database file path
$db_file = 'gaming_partners.db';

try {
    // Connect to the SQLite database
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']); // Remove spaces
        $password = $_POST['password'];

        // Fetch the user from the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username COLLATE NOCASE");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to games page
            header("Location: games.php");
            exit();
        } else {
            // Redirect back to login.html with an error
            header("Location: login.php?error=invalid");
            exit();
        }
    }
} catch (PDOException $e) {
    // Redirect to login.html with a database error
    header("Location: login.php?error=database");
    exit();
}
