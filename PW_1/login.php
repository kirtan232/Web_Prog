<?php
session_start();
if (isset($_POST['username']) && trim($_POST['username']) !== '') {
    $_SESSION['username'] = trim($_POST['username']);
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Who Wants to Be a Millionaire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="homepage">
    <div class="container">
        <h1 class="glow">Login</h1>
        <form method="post" style="margin-top:30px;">
            <input type="text" name="username" placeholder="Enter your name" required style="padding:10px; font-size:1.1em; border-radius:6px; border:1px solid #ccc;">
            <button type="submit" class="start-btn" style="margin-left:10px;">Login</button>
        </form>
    </div>
</body>
</html>
