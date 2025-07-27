<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

include 'questions.php';

// Initialize session if not set
if (!isset($_SESSION['game_started'])) {
    $_SESSION['game_started'] = true;
    $_SESSION['current_question'] = 0;
    $_SESSION['total_winnings'] = 0;
}

// Check if form was submitted with an answer
if (!isset($_POST['answer'])) {
    header("Location: question.php");
    exit();
}

$current_question_index = $_SESSION['current_question'];
$user_answer = (int)$_POST['answer'];
$correct_answer = $questions[$current_question_index]['answer'];
$prize_amount = $questions[$current_question_index]['prize'];

// Check if answer is correct
if ($user_answer === $correct_answer) {
    // Correct answer!
    $_SESSION['total_winnings'] += $prize_amount;
    $_SESSION['current_question']++;
    
    // Debug output
    echo "<!-- DEBUG result.php: After increment - current_question = " . $_SESSION['current_question'] . ", total_winnings = " . $_SESSION['total_winnings'] . " -->";
    
    // Clear question-specific data for next question (but keep lifeline usage)
    if (isset($_SESSION['hidden_answers'])) {
        unset($_SESSION['hidden_answers']);
    }
    if (isset($_SESSION['audience_poll'])) {
        unset($_SESSION['audience_poll']);
    }
    if (isset($_SESSION['phone_friend'])) {
        unset($_SESSION['phone_friend']);
    }
    
    // Force session to be saved immediately
    session_commit();
    
    // Check if this was the last question
    if ($_SESSION['current_question'] >= count($questions)) {
        header("Location: win.php");
        exit();
    }
    
    // Show correct answer page with option to continue
    $next_question_number = $_SESSION['current_question'] + 1;
    $next_prize = isset($questions[$_SESSION['current_question']]) ? $questions[$_SESSION['current_question']]['prize'] : 0;
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Correct Answer!</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="game-body">
        <div class="game-container">
            <div class="money-circle">
                $<?php echo number_format($_SESSION['total_winnings']); ?>
            </div>
            
            <div class="result-message">
                <h1 style="color: #00ff00;">CORRECT!</h1>
                <p>You just won $<?php echo number_format($prize_amount); ?>!</p>
                <p>Your total winnings: $<?php echo number_format($_SESSION['total_winnings']); ?></p>
                
                <?php if ($_SESSION['current_question'] < count($questions)): ?>
                    <p>Next question <?php echo $next_question_number; ?> is worth $<?php echo number_format($next_prize); ?></p>
                    <div class="continue-options">
                        <a href="question.php" class="continue-btn">Continue Playing</a>
                        <a href="gameover.php" class="walk-away-btn">Walk Away with $<?php echo number_format($_SESSION['total_winnings']); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    // Wrong answer - game over
    header("Location: gameover.php");
    exit();
}
?>
