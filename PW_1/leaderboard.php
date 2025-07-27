<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
// Read leaderboard from file
$leaderboard = [];
$filename = 'leaderboard.txt';
if (file_exists($filename)) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($name, $score) = explode('|', $line);
        $leaderboard[] = ['name' => $name, 'score' => (int)$score];
    }
    // Sort by score descending
    usort($leaderboard, function($a, $b) { return $b['score'] <=> $a['score']; });
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Leaderboard - Who Wants to Be a Millionaire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="homepage">
    <div class="container">
        <h1 class="glow">Leaderboard</h1>
        <table style="margin: 0 auto; color: #f8e473; font-size: 1.2em; background: rgba(0,0,0,0.7); border-radius: 10px; padding: 20px;">
            <tr><th style="color: gold;">Rank</th><th style="color: gold;">Name</th><th style="color: gold;">Winnings</th></tr>
            <?php if (count($leaderboard) === 0): ?>
                <tr><td colspan="3">No scores yet!</td></tr>
            <?php else: ?>
                <?php foreach ($leaderboard as $i => $entry): ?>
                    <tr>
                        <td><?php echo $i+1; ?></td>
                        <td><?php echo htmlspecialchars($entry['name']); ?></td>
                        <td>$<?php echo number_format($entry['score']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
        <a href="index.php" class="start-btn" style="margin-top:30px; display:block;">Back to Home</a>
        <a href="logout.php" class="start-btn" style="background:#ff4444; color:#fff; margin-top:15px; display:block;">Logout</a>
    </div>
</body>
</html>
