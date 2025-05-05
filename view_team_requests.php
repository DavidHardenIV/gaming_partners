<?php
session_start();
$db_file = '../gaming_partners.db';

try {
    $conn = new PDO("sqlite:$db_file");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query("SELECT * FROM team_requests ORDER BY created_at DESC");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teammate Requests</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <h1>Active Teammate Requests</h1>
    <ul>
        <?php foreach ($requests as $req): ?>
            <li>
                <strong><?php echo htmlspecialchars($req['game']); ?></strong> - 
                <?php echo htmlspecialchars($req['players_needed']); ?> Players | 
                <?php echo htmlspecialchars($req['playstyle']); ?> | 
                <?php echo htmlspecialchars($req['experience_level']); ?> 
                <br>
                <em><?php echo htmlspecialchars($req['additional_info']); ?></em>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
