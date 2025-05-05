<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


$db_file = 'gaming_partners.db';

try {
    echo "Connecting to database...<br>";

    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        echo "Form submitted.<br>";

        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirm_password = trim($_POST['confirm-password']);

        if ($password !== $confirm_password) {
            echo "<p style='color: red;'>Error: Passwords do not match.</p>";
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            echo "<p style='color: red;'>Error: This email is already registered.</p>";
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Send Welcome Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'biddave100@gmail.com';       // Your Gmail
            $mail->Password   = 'kkrl bcrc tirk kalb';          // Your Gmail App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('your_email@gmail.com', 'Gaming Partners');
            $mail->addAddress($email, $username);

            $mail->isHTML(true);
            $mail->Subject = 'Welcome to Gaming Partners!';
            $mail->Body    = '
            <html>
              <head>
                <style>
                  .email-container {
                    font-family: Arial, sans-serif;
                    background-color: #111;
                    color: #f4f4f4;
                    padding: 20px;
                    border-radius: 10px;
                  }
                  .header {
                    font-size: 24px;
                    font-weight: bold;
                    color: #00ffcc;
                  }
                  .button {
                    background-color: #00ffcc;
                    color: #111;
                    padding: 10px 20px;
                    text-decoration: none;
                    border-radius: 8px;
                    display: inline-block;
                    margin-top: 20px;
                  }
                </style>
              </head>
              <body>
                <div class="email-container">
                  <div class="header">🎮 Welcome to Gaming Partners, ' . htmlspecialchars($username) . '!</div>
                  <p>Thanks for creating an account. You\'re officially part of a growing community of gamers looking to squad up and win together!</p>
                  <p>Start finding teammates for your favorite games like Fortnite, Call of Duty, Valorant, and more.</p>
                  <p>As we grow, you’ll be among the first to access features when we launch online.</p>
                  <p style="margin-top: 30px;">Let’s dominate the lobby.<br><strong>- The Gaming Partners Team</strong></p>
                </div>
              </body>
            </html>
            ';
            $mail->send();
            echo "Welcome email sent.<br>";
        } catch (Exception $e) {
            echo "Email failed: {$mail->ErrorInfo}<br>";
        }

        $conn = null;
        header("Location: login.php");
        exit();
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
} finally {
    $conn = null;
}
?>
