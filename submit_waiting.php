<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$db_file = 'gaming_partners.db';

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = isset($_SESSION['username']) ? $_SESSION['username'] : "Guest";
        $game = isset($_POST['game']) ? trim($_POST['game']) : "Unknown"; // Get game name
        $players_needed = $_POST['players_needed'];
        $playstyle = $_POST['playstyle'];
        $experience_level = $_POST['experience_level'];
        $additional_notes = trim($_POST['additional_notes']);

        // Insert into waiting lobby
        $stmt = $conn->prepare("INSERT INTO waiting_lobby (username, game, players_needed, playstyle, experience_level, additional_notes)
                                VALUES (:username, :game, :players_needed, :playstyle, :experience_level, :additional_notes)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':game', $game);
        $stmt->bindParam(':players_needed', $players_needed);
        $stmt->bindParam(':playstyle', $playstyle);
        $stmt->bindParam(':experience_level', $experience_level);
        $stmt->bindParam(':additional_notes', $additional_notes);
        $stmt->execute();

        // Redirect to the waiting lobby for that specific game
        header("Location: waiting_lobby.php?game=" . urlencode($game));
        exit();
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}
?>
