<?php
session_start();

// Database file path
$db_file = 'gaming_partners.db';

try {
    // Connect to the SQLite database
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // Fetch the user from the database
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            session_regenerate_id(true); // Prevent session fixation attacks
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: games.html"); // Redirect to the home screen
            exit();
        } else {
            // Redirect back to login with an error
            $_SESSION['login_error'] = "Invalid username or password.";
            header("Location: login.html");
            exit();
        }
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log the error
    header("Location: error.html"); // Redirect to a generic error page
    exit();
}
?>
