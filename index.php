<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dawn of Discovery</title>
    <link rel="stylesheet" href="public/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div id="game-container">
        <div id="status-bar"></div>
        <div id="main-display">
            <div id="system-info"></div>
            <div id="player-info"></div>
        </div>
        <div id="action-menu"></div>
    </div>
    <script src="public/js/game.js"></script>
</body>
</html>