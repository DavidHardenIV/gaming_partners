<?php
// Ensure session_start() only runs if a session isn't already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$logged_in = isset($_SESSION['user_id']); // Check if user is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="David Harden">
    <meta name="description" content="Games Page">
    <title>Find Gaming Teammates</title>
    <link rel="stylesheet" href="styles.css?v=2">  <!-- Forces browser to load latest styles -->
</head>
<body>

    <!-- Pop-up Modal for Login Requirement -->
    <?php if (!$logged_in): ?>
    <div id="loginPopup" class="popup-overlay">
        <div class="popup-content">
            <h2>Log In Required</h2>
            <p>You need to log in to find teammates.</p>
            <a href="login.php" class="btn-special">Log In</a>
            <a href="create-account.html" class="btn-close">Create Account</a>
        </div>
    </div>
    <?php endif; ?>

    <!-- Navigation -->
    <header>
        <nav>
            <div class="container">
                <a href="games.php" class="logo">Gaming Partners</a>
                <ul class="nav-links">
                    <li><a href="games.php">Home</a></li>
                    <li><a href="contact.html">Request Game/Questions</a></li>
                    <li><a href="readme.html">Read Me</a></li>
                    <!-- <li><a href="profile.php">Profile</a></li> -->
                    <?php if (!$logged_in): ?>
                        <li><a href="login.php">Log In</a></li>
                        <li><a href="create-account.html">Create Account</a></li>
                    <?php else: ?>
                        <li><a href="javascript:void(0);" onclick="confirmLogout()" class="">Log Out</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="games-grid">
            <div class="container">
                <h1>Games</h1>
                <p>Explore a selection of games to find teammates and connect with others!</p>
                <div class="games-intro">
                    <p>Click on the game you want to play to find teammates!</p>
                <div class="games-wrapper">
                    <!-- Game Cards Start -->
                    <div class="game-card"><img src="images/fort.jpg" alt="Fortnite"><h3>Fortnite</h3><a href="fortnite.php" class="btn">Find Teammates</a></div>
                    <div class="game-card"><img src="images/league.jpg" alt="League of Legends"><h3>League of Legends</h3><a href="league.php" class="btn">Find Teammates</a></div>
                    <div class="game-card"><img src="images/val.jpg" alt="Valorant"><h3>Valorant</h3><a href="valorant.php" class="btn">Find Teammates</a></div>
                    <div class="game-card"><img src="images/csgo.jpg" alt="CS:GO"><h3>CS:GO</h3><a href="csgo.php" class="btn">Find Teammates</a></div>
                    <div class="game-card"><img src="images/apex.jpg" alt="Apex Legends"><h3>Apex Legends</h3><a href="apex.php" class="btn">Find Teammates</a></div>
                    <div class="game-card"><img src="images/pubg.jpg" alt="PUBG: Battlegrounds"><h3>PUBG: Battlegrounds</h3><a href="pubg.php" class="btn">Find Teammates</a></div>
                    <div class="game-card"><img src="images/rain.png" alt="Rainbow Six Siege"><h3>Rainbow Six Siege</h3><a href="rainbowsix.php" class="btn">Find Teammates</a></div>      
                </div> 
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2025 David Harden. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let popup = document.getElementById("loginPopup");
        let closeBtn = document.getElementById("closePopup");

        if (popup) {
            popup.style.display = "flex"; // Show the pop-up

            closeBtn.addEventListener("click", function () {
                popup.style.display = "none"; // Hide the pop-up when "Close" is clicked
            });
        }
    });

    function confirmLogout() {
        if (confirm("Are you sure you want to log out?")) {
            window.location.href = "logout.php";
        }
    }
    </script>

</body>
</html>
