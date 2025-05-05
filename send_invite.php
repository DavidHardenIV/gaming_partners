<?php
session_start();
$db = new PDO("sqlite:gaming_partners.db");

$sender_id = $_SESSION['user_id'];
$receiver_id = $_POST['receiver_id'] ?? null;
$game = $_POST['game'] ?? null;

if (!$receiver_id || !$game) {
    echo "Missing data."; exit;
}

$stmt = $db->prepare("INSERT INTO invites (sender_id, receiver_id, game, status) VALUES (?, ?, ?, 'pending')");
$stmt->execute([$sender_id, $receiver_id, $game]);

header("Location: games.php");
exit;
