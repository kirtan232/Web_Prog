<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>How to Play - Who Wants to Be a Millionaire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="homepage">
    <div class="container">
        <h1 class="glow">How to Play</h1>
        <ul style="text-align:left; max-width:500px; margin:0 auto 40px auto; color:#f8e473; font-size:1.1em;">
            <li>Click <b>Start Game</b> to begin.</li>
            <li>Answer each multiple-choice question by clicking on the correct answer.</li>
            <li>Each correct answer increases your total winnings and moves you to the next question.</li>
            <li>If you answer incorrectly, the game ends and you keep your current winnings.</li>
            <li>After each correct answer, you can choose to continue or walk away with your winnings.</li>
            <li>Answer all questions correctly to win the top prize!</li>
        </ul>
        <a href="index.php" class="start-btn" style="margin-top:30px; display:block;">Back to Home</a>
    </div>
</body>
</html>
