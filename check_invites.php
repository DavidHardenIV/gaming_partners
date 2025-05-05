<?php
session_start();
$db = new PDO("sqlite:gaming_partners.db");

$user_id = $_SESSION['user_id'];
$stmt = $db->prepare("SELECT * FROM invites WHERE receiver_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$invites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Incoming Game Invites</title>
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
            <h2 class="game-title">Incoming Game Invites</h2>

            <?php if (count($invites) === 0): ?>
                <p>No pending invites at this time.</p>
            <?php else: ?>
                <?php foreach ($invites as $invite): ?>
                    <div class="invite-card">
                        <p><strong>Game:</strong> <?php echo htmlspecialchars($invite['game']); ?></p>
                        <p><strong>From User ID:</strong> <?php echo $invite['sender_id']; ?></p>
                        <form action="handle_invite.php" method="POST" class="inline-form">
                            <input type="hidden" name="invite_id" value="<?php echo $invite['id']; ?>">
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="btn-small">Accept</button>
                        </form>
                        <form action="handle_invite.php" method="POST" class="inline-form">
                            <input type="hidden" name="invite_id" value="<?php echo $invite['id']; ?>">
                            <input type="hidden" name="action" value="decline">
                            <button type="submit" class="btn-small">Decline</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

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
