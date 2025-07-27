<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$total_winnings = isset($_SESSION['total_winnings']) ? $_SESSION['total_winnings'] : 0;

// Save to leaderboard if score > 0
if ($total_winnings > 0) {
    $entry = $_SESSION['username'] . '|' . $total_winnings . "\n";
    file_put_contents('leaderboard.txt', $entry, FILE_APPEND | LOCK_EX);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>You Win!</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="gameover">
    <div class="center">
        <h1>Congratulations!</h1>
        <p>You answered all questions correctly and won $<?php echo number_format($total_winnings); ?>!</p>
        <p>You are now a millionaire!</p>
        <a href="index.php" class="start-btn">Play Again</a>
    </div>
</body>
</html>
