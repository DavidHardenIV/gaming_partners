<?php
session_start();
$logged_in = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find CS:GO Teammates</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="game_styles.css">
</head>
<body>
<header>
    <nav>
        <div class="container">
            <a href="games.php" class="logo">Gaming Partners</a>
            <ul class="nav-links">
                <li><a href="games.php">Home</a></li>
                <li><a href="check_invites.php" class="">Invites</a></li>
                <li><a href="gaming_rooms.php" class="">Gaming Rooms</a></li>
                <?php if (!$logged_in): ?>
                    <li><a href="login.html">Log In</a></li>
                    <li><a href="create-account.html">Create Account</a></li>
                <?php else: ?>
                    <li><a href="javascript:void(0);" onclick="confirmLogout()" class="">Log Out</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<main>
    <section class="game-form">
        <div class="container">
            <h1 class="game-title">Find Teammates for CS:GO</h1>
            <form action="submit_waiting.php" method="POST">
                <input type="hidden" name="game" value="CS:GO">

                <label for="steam_name">Enter Your Steam Name:</label>
                <input type="text" name="steam_name" placeholder="Enter your Steam name" required>

                <label for="players_needed">How many players do you need?</label>
                <select name="players_needed">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>

                <label for="playstyle">Playstyle</label>
                <select name="playstyle">
                    <option value="Casual">Casual</option>
                    <option value="Competitive">Competitive</option>
                    <option value="Deathmatch">Deathmatch</option>
                </select>

                <label for="experience_level">Rank:</label>
                <select name="experience_level">
                    <option value="Silver I">Silver I</option>
                    <option value="Silver II">Silver II</option>
                    <option value="Silver III">Silver III</option>
                    <option value="Silver IV">Silver IV</option>
                    <option value="Silver Elite">Silver Elite</option>
                    <option value="Silver Elite Master">Silver Elite Master</option>
                    <option value="Gold Nova I">Gold Nova I</option>
                    <option value="Gold Nova II">Gold Nova II</option>
                    <option value="Gold Nova III">Gold Nova III</option>
                    <option value="Gold Nova Master">Gold Nova Master</option>
                    <option value="Master Guardian I">Master Guardian I</option>
                    <option value="Master Guardian II">Master Guardian II</option>
                    <option value="Master Guardian Elite">Master Guardian Elite</option>
                    <option value="Distinguished Master Guardian">Distinguished Master Guardian</option>
                    <option value="Legendary Eagle">Legendary Eagle</option>
                    <option value="Legendary Eagle Master">Legendary Eagle Master</option>
                    <option value="Supreme Master First Class">Supreme Master First Class</option>
                    <option value="Global Elite">Global Elite</option>
                </select>

                <label for="additional_notes">Additional Notes:</label>
                <textarea name="additional_notes" placeholder="Map, mode, mic, etc."></textarea>

                <button type="submit" class="btn">Submit Request</button>
            </form>
        </div>
    </section>

    <section class="waiting-lobby">
        <div class="container">
            <h2 class="game-title">Players Currently Waiting</h2>
            <table class="waiting-table">
                <tr>
                    <th>Steam Name</th>
                    <th>Players Needed</th>
                    <th>Playstyle</th>
                    <th>Rank</th>
                    <th>Notes</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
                <?php
                $db = new PDO("sqlite:gaming_partners.db");
                $stmt = $db->prepare("SELECT * FROM waiting_lobby WHERE game = 'CS:GO' ORDER BY created_at DESC");
                $stmt->execute();
                $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($players as $player): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($player['steam_name']); ?></td>
                        <td><?php echo htmlspecialchars($player['players_needed']); ?></td>
                        <td><?php echo htmlspecialchars($player['playstyle']); ?></td>
                        <td><?php echo htmlspecialchars($player['experience_level']); ?></td>
                        <td><?php echo htmlspecialchars($player['additional_notes']); ?></td>
                        <td><?php echo htmlspecialchars($player['created_at']); ?></td>
                        <td>
                            <form action="view_player.php" method="POST">
                                <input type="hidden" name="player_id" value="<?php echo $player['id']; ?>">
                                <button type="submit" class="btn-small">View</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </section>
</main>

<footer>
    <div class="container">
        <p>&copy; 2025 David Harden. All Rights Reserved.</p>
    </div>
</footer>

<script>
function confirmLogout() {
    if (confirm("Are you sure you want to log out?")) {
        window.location.href = "logout.php";
    }
}
</script>
</body>
</html>
