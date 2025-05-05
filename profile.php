<?php
session_start();
$db_file = 'gaming_partners.db';
$conn = new PDO("sqlite:$db_file");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$username = $_SESSION['username'] ?? '';
if (!$username) {
    echo "<p>You must <a href='login.php'>log in</a> to view your profile.</p>";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $bio = trim($_POST['bio']);
    $games = trim($_POST['games']); // Comma-separated list

    $stmt = $conn->prepare("INSERT INTO user_profiles (username, bio, games)
                            VALUES (:username, :bio, :games)
                            ON CONFLICT(username) DO UPDATE SET bio = :bio, games = :games");

    $stmt->execute([
        ':username' => $username,
        ':bio' => $bio,
        ':games' => $games
    ]);
}

// Fetch profile
$stmt = $conn->prepare("SELECT bio, games FROM user_profiles WHERE username = :username");
$stmt->execute([':username' => $username]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

$bio = $profile['bio'] ?? '';
$games = $profile['games'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link rel="stylesheet" href="Styles/style_profile.css"> <!-- Forces browser to load latest styles -->
</head>
<body>

<header>
        <nav>
            <div class="container">
                <a href="games.php" class="logo">Gaming Partners</a>
                <ul class="nav-links">
                    <li><a href="games.php">Home</a></li>
                    <li><a href="contact.html">Request Game/Questions</a></li>
                    <li><a href="readme.html">Read Me</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <div class="profile-container">
        <h1 class="section-title">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

        <form method="POST" action="profile.php" class="profile-form">
            <div class="form-group">
                <label for="games">Games You Like (comma-separated):</label>
                <input type="text" name="games" id="games" class="form-control" value="<?php echo htmlspecialchars($games); ?>">
            </div>

            <div class="form-group">
                <label for="bio">About You:</label>
                <textarea name="bio" id="bio" rows="5" class="form-control"><?php echo htmlspecialchars($bio); ?></textarea>
            </div>

            <button type="submit" class="btn-primary">Save Profile</button>
        </form>
    </div>
</body>
</html>
