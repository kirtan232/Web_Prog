<?php
session_start();
include 'questions.php';

// Debug output
echo "<h2>Debug Information:</h2>";
echo "<p>POST data received: " . (isset($_POST['answer']) ? "YES" : "NO") . "</p>";
echo "<p>Answer value: " . (isset($_POST['answer']) ? $_POST['answer'] : "NONE") . "</p>";
echo "<p>Session current_question: " . (isset($_SESSION['current_question']) ? $_SESSION['current_question'] : "NOT SET") . "</p>";
echo "<p>Session total_winnings: " . (isset($_SESSION['total_winnings']) ? $_SESSION['total_winnings'] : "NOT SET") . "</p>";
echo "<p>Session game_started: " . (isset($_SESSION['game_started']) ? "YES" : "NO") . "</p>";

if (isset($_POST['answer']) && isset($_SESSION['current_question'])) {
    $current_question_index = $_SESSION['current_question'];
    $current_question = $questions[$current_question_index];
    $correct_answer = $current_question['answer'];
    $user_answer = (int)$_POST['answer'];
    
    echo "<p>Question: " . $current_question['question'] . "</p>";
    echo "<p>Correct answer index: " . $correct_answer . "</p>";
    echo "<p>User answer index: " . $user_answer . "</p>";
    echo "<p>Correct option: " . $current_question['options'][$correct_answer] . "</p>";
    echo "<p>User selected: " . $current_question['options'][$user_answer] . "</p>";
    echo "<p>Is correct: " . ($user_answer === $correct_answer ? "YES" : "NO") . "</p>";
}

echo "<p><a href='question.php'>Back to Question</a></p>";
?>
