<?php
session_start();
$db_file = 'gaming_partners.db'; // Path to database

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $game = $_POST['game'];
        $players_needed = $_POST['players_needed'];
        $playstyle = $_POST['playstyle'];
        $experience_level = $_POST['experience_level'];
        $additional_info = trim($_POST['additional_info']);
        $user_id = $_SESSION['user_id'] ?? null;

        // Insert into the database
        $stmt = $conn->prepare("INSERT INTO team_requests (user_id, game, players_needed, playstyle, experience_level, additional_info) VALUES (:user_id, :game, :players_needed, :playstyle, :experience_level, :additional_info)");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':game', $game);
        $stmt->bindParam(':players_needed', $players_needed);
        $stmt->bindParam(':playstyle', $playstyle);
        $stmt->bindParam(':experience_level', $experience_level);
        $stmt->bindParam(':additional_info', $additional_info);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Request submitted successfully!</p>";
        } else {
            echo "<p style='color: red;'>Failed to submit request.</p>";
        }
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . $e->getMessage() . "</p>";
}
?>
