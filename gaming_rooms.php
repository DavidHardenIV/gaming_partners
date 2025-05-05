<?php
session_start();
$db = new PDO("sqlite:gaming_partners.db");

// Fetch all gaming rooms
$stmt = $db->query("SELECT * FROM gaming_rooms ORDER BY game ASC, created_at DESC");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by game
$grouped_rooms = [];
foreach ($rooms as $room) {
    $game = $room['game'];
    if (!isset($grouped_rooms[$game])) {
        $grouped_rooms[$game] = [];
    }
    $grouped_rooms[$game][] = $room;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gaming Rooms</title>
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
        <h2 class="game-title">Active Gaming Rooms</h2>

        <?php if (count($grouped_rooms) === 0): ?>
            <p>No active gaming rooms at the moment.</p>
        <?php else: ?>
            <?php foreach ($grouped_rooms as $game => $rooms): ?>
                <div class="game-room-group">
                    <h3><?php echo htmlspecialchars($game); ?> (<?php echo count($rooms); ?> Room<?php echo count($rooms) > 1 ? 's' : ''; ?>)</h3>

                    <?php foreach ($rooms as $room): ?>
                        <?php
                        $player_ids = explode(',', $room['players']);
                        $usernames = [];

                        foreach ($player_ids as $pid) {
                            $user_stmt = $db->prepare("SELECT username FROM users WHERE id = ?");
                            $user_stmt->execute([$pid]);
                            $user = $user_stmt->fetch(PDO::FETCH_ASSOC);
                            $usernames[] = $user ? $user['username'] : "User#$pid";
                        }

                        $player_list = implode(', ', $usernames);
                        ?>

                        <div class="invite-card">
                            <p><strong>Players:</strong> <?php echo $player_list; ?></p>
                            <p><strong>Created:</strong> <?php echo htmlspecialchars($room['created_at']); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- <a href="games.php" class="btn">Back to Lobby</a> -->
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; 2025 David Harden. All Rights Reserved.</p>
    </div>
</footer>
</body>
</html>
