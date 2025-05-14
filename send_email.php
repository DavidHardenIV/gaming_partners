<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'partnersgaming0@gmail.com'; // Your Gmail address
        $mail->Password   = 'lilf zvsw dfut euwn';   // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($_POST['email'], $_POST['name']);
        $mail->addAddress('partnersgaming0@gmail.com'); // Your email to receive messages

        // Content
        $mail->isHTML(false);
        $mail->Subject = $_POST['subject'];
        $mail->Body    = "Name: " . $_POST['name'] . "\n" .
                         "Email: " . $_POST['email'] . "\n\n" .
                         $_POST['message'];

        $mail->send();
        echo "<script>
            alert('Your message has been sent successfully!');
            window.location.href = 'games.php';
        </script>";

    } catch (Exception $e) {
        echo "<script>
            alert('Message could not be sent. Mailer Error: " . $mail->ErrorInfo . "');
            window.location.href = 'games.php';
        </script>";
    }
}
?>
