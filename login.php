<?php
session_start();
require_once 'routes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        if (login($_POST['username'], $_POST['password'])) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } elseif (isset($_POST['register'])) {
        if (register($_POST['username'], $_POST['password'])) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Registration failed. Username may already exist.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dawn of Discovery</title>
    <link rel="stylesheet" href="public/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    <div id="login-container">
        <h1>Dawn of Discovery</h1>
        <form method="post" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
            <button type="submit" name="register">Register</button>
        </form>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>