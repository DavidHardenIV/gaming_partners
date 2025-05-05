<?php
session_start();
session_destroy(); // Destroy session to log out the user
header("Location: games.php"); // Redirect to games.php after logout
exit();
?>
