<?php
session_start();
$db = new PDO("sqlite:gaming_partners.db");

$invite_id = $_POST['invite_id'];
$action = $_POST['action'];

$stmt = $db->prepare("SELECT * FROM invites WHERE id = ?");
$stmt->execute([$invite_id]);
$invite = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$invite || $invite['receiver_id'] != $_SESSION['user_id']) {
    echo "Invalid invite."; exit;
}

if ($action === "accept") {
    // Mark invite accepted
    $db->prepare("UPDATE invites SET status = 'accepted' WHERE id = ?")->execute([$invite_id]);

    // Remove both from waiting_lobby
    $db->prepare("DELETE FROM waiting_lobby WHERE user_id IN (?, ?)")->execute([$invite['sender_id'], $invite['receiver_id']]);

    // Create gaming room
    $players = implode(',', [$invite['sender_id'], $invite['receiver_id']]);
    $db->prepare("INSERT INTO gaming_rooms (game, players) VALUES (?, ?)")->execute([$invite['game'], $players]);

} elseif ($action === "decline") {
    $db->prepare("UPDATE invites SET status = 'declined' WHERE id = ?")->execute([$invite_id]);
}

header("Location: games.php");
exit;
