<?php
session_start();
$db = new PDO("sqlite:gaming_partners.db");

$player_id = $_POST['player_id'] ?? null;
if (!$player_id) { echo "No player ID."; exit; }

$stmt = $db->prepare("SELECT * FROM waiting_lobby WHERE id = ?");
$stmt->execute([$player_id]);
$player = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$player) { echo "Player not found."; exit; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Player Profile</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="game_styles.css">
</head>
<body>
    <header>
        <nav>
            <div class="container">
                <a href="games.php" class="logo">Gaming Partners</a>
            </div>
        </nav>
    </header>

    <main>
        <div class="container">
            <h2 class="game-title">Player Profile</h2>
            <div class="invite-card">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($player['riot_name'] ?? $player['epic_name'] ?? $player['steam_name'] ?? $player['pubg_name'] ?? $player['r6_name'] ?? 'Unknown'); ?></p>
                <p><strong>Game:</strong> <?php echo htmlspecialchars($player['game']); ?></p>
                <p><strong>Rank:</strong> <?php echo htmlspecialchars($player['experience_level']); ?></p>
                <p><strong>Playstyle:</strong> <?php echo htmlspecialchars($player['playstyle']); ?></p>
                <p><strong>Notes:</strong> <?php echo htmlspecialchars($player['additional_notes']); ?></p>

                <form action="send_invite.php" method="POST">
                    <input type="hidden" name="receiver_id" value="<?php echo $player['user_id']; ?>">
                    <input type="hidden" name="game" value="<?php echo $player['game']; ?>">
                    <button type="submit" class="btn">Send Invite</button>
                </form>
            </div>

            <a href="games.php" class="btn">Back to Lobby</a>
        </div>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 David Harden. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
