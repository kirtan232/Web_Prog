<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
// Reset all game session variables
unset($_SESSION['game_started']);
unset($_SESSION['current_question']);
unset($_SESSION['total_winnings']);
// Reset hint tracking
unset($_SESSION['hint_used']);
unset($_SESSION['hidden_answers']);
// Also clear any old session variables that might exist
unset($_SESSION['score']);
unset($_SESSION['q']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Who Wants to Be a Millionaire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="homepage">
    <div class="container">
        <img src="images/logo.png" alt="Millionaire Logo" style="width:200px; margin-bottom: 20px;">
        <h1 class="glow">Who Wants to Be a Millionaire</h1>
        <p class="intro-text">Think you're smart? Try answering all the questions to win $1,000,000.</p>
        <a href="question.php" class="start-btn">Start Game</a>
        <a href="howtoplay.php" class="start-btn" style="background:#4444ff; color:#fff; margin-top:15px;">How to Play</a>
        <a href="leaderboard.php" class="start-btn" style="background:#00ccff; color:#000; margin-top:15px;">Leaderboard</a>
        <a href="logout.php" class="start-btn" style="background:#ff4444; color:#fff;  display: block; width: 200px; margin: 2em auto;">Logout</a>
    </div>
</body>
</html>
