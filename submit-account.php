<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$db_file = 'gaming_partners.db';

try {
    echo "Connecting to database...<br>"; // Debug message

    // Connect to SQLite
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Database connected successfully.<br>"; // Debug message

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "Form submitted.<br>"; // Debug message

        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm-password']);

        // Password confirmation check
        if ($password !== $confirm_password) {
            echo "<p style='color: red;'>Error: Passwords do not match.</p>";
            exit();
        }

        echo "Password confirmed.<br>"; // Debug message

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        echo "Checking if email exists...<br>"; // Debug message

        // Check if email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            echo "<p style='color: red;'>Error: This email is already registered.</p>";
            exit();
        }

        echo "Email is unique. Proceeding with registration...<br>"; // Debug message

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        echo "User inserted successfully.<br>"; // Debug message

        // Close connection to prevent database lock issues
        $conn = null;

        echo "Redirecting to login page...<br>"; // Debug message

        // Redirect to login page after successful registration
        header("Location: login.html");
        exit();
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
} finally {
    $conn = null; // Ensures connection closes
}

?>
