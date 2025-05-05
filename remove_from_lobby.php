<?php
session_start();
$db_file = 'gaming_partners.db';
$conn = new PDO("sqlite:$db_file");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$game = isset($_GET['game']) ? trim($_GET['game']) : 'Valorant';
$username = $_SESSION['username'] ?? '';

if (!empty($username)) {
    $stmt = $conn->prepare("DELETE FROM waiting_lobby WHERE username = :username AND game = :game");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':game', $game);
    $stmt->execute();
}

$conn = null;
?>
