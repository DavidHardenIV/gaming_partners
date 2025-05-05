<?php
session_start();
$db_file = 'gaming_partners.db';
$conn = new PDO("sqlite:$db_file");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$game = isset($_GET['game']) ? trim($_GET['game']) : 'Valorant'; // Default game

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color: red;'>You must be logged in to enter the waiting lobby.</p>";
    exit();
}

$username = $_SESSION['username'];

// **Add user to waiting list when they enter the page**
$stmt = $conn->prepare("INSERT INTO waiting_lobby (username, game, players_needed, playstyle, experience_level, additional_notes, riot_name, created_at)
                        VALUES (:username, :game, '1', 'Casual', 'Beginner', 'Waiting for teammates...', :riot_name, datetime('now'))
                        ON CONFLICT(username, game) 
                        DO UPDATE SET players_needed = '1', playstyle = 'Casual', experience_level = 'Beginner', 
                                      additional_notes = 'Waiting for teammates...', riot_name = :riot_name, 
                                      created_at = datetime('now')");
$stmt->bindParam(':username', $username);
$stmt->bindParam(':game', $game);
$stmt->bindParam(':riot_name', $riot_name);
$stmt->execute();


// **Fetch all players in the waiting lobby for this game**
$stmt = $conn->prepare("SELECT * FROM waiting_lobby WHERE game = :game ORDER BY created_at DESC");
$stmt->bindParam(':game', $game);
$stmt->execute();
$players = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="David Harden">
    <meta name="description" content="Waiting Lobby for <?php echo htmlspecialchars($game); ?>">
    <title>Waiting Lobby - <?php echo htmlspecialchars($game); ?></title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="game_styles.css">
    <style>
        body { font-family: 'Arial', sans-serif; background-color: #121212; color: #e0e0e0; text-align: center; }
        .container { max-width: 900px; margin: auto; padding: 20px; }
        .waiting-title { color: #4A90E2; font-size: 2rem; margin-bottom: 10px; }
        .waiting-lobby { display: flex; flex-direction: column; gap: 15px; margin-top: 20px; }
        .waiting-card { background: #1f1f1f; padding: 15px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3); text-align: left; position: relative; cursor: pointer; transition: 0.3s; }
        .waiting-card:hover { background: #242424; }
        .waiting-card .header { display: flex; justify-content: space-between; align-items: center; }
        .waiting-card .username { font-size: 1.2rem; color: #4A90E2; font-weight: bold; }
        .waiting-card .playstyle { font-size: 1rem; color: #b0b0b0; }
        .waiting-card .details { display: none; margin-top: 10px; padding-top: 10px; border-top: 1px solid #333; }
        .waiting-card.active .details { display: block; }
        .btn-back { display: inline-block; margin-top: 20px; padding: 10px 15px; background: #4A90E2; color: white; text-decoration: none; border-radius: 5px; transition: 0.3s; }
        .btn-back:hover { background: #357ABD; }
    </style>
</head>
<body>

    <div class="container">
        <h1 class="waiting-title">Waiting Lobby for <?php echo htmlspecialchars($game); ?></h1>
        <p>Players currently looking for teammates:</p>

        <div class="waiting-lobby">
            <?php foreach ($players as $player): ?>
                <div class="waiting-card" onclick="toggleDetails(this)">
                    <div class="header">
                        <p class="username"><?php echo htmlspecialchars($player['username']); ?> (<?php echo htmlspecialchars($player['riot_name']); ?>)</p>
                        <p class="playstyle"><?php echo htmlspecialchars($player['playstyle']); ?></p>
                    </div>
                    <div class="details">
                        <p><strong>Players Needed:</strong> <?php echo htmlspecialchars($player['players_needed']); ?></p>
                        <p><strong>Ranked Rating:</strong> <?php echo htmlspecialchars($player['experience_level']); ?></p>
                        <p><strong>Notes:</strong> <?php echo htmlspecialchars($player['additional_notes']); ?></p>
                        <p><small>Joined: <?php echo htmlspecialchars($player['created_at']); ?></small></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="games.php" class="btn-back" onclick="removeFromLobby()">Back to Games</a>
    </div>

    <script>
        function toggleDetails(card) {
            card.classList.toggle('active');
        }

        // **Remove user from waiting lobby when leaving**
        function removeFromLobby() {
            fetch("remove_from_lobby.php?game=<?php echo urlencode($game); ?>")
                .then(response => console.log("User removed from lobby."));
        }

        // **Automatically remove user if they close tab or refresh**
        window.addEventListener("beforeunload", removeFromLobby);
    </script>

</body>
</html>
