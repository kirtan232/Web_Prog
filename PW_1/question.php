<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

include 'questions.php';

// Initialize game state ONLY when starting completely fresh (no session data exists at all)
if (!isset($_SESSION['game_started']) && !isset($_SESSION['current_question']) && !isset($_SESSION['total_winnings'])) {
    $_SESSION['game_started'] = true;
    $_SESSION['current_question'] = 0;
    $_SESSION['total_winnings'] = 0;
    // Reset lifelines for new game
    $_SESSION['hint_used'] = false;
    $_SESSION['audience_used'] = false;
    $_SESSION['phone_used'] = false;
    unset($_SESSION['hidden_answers']);
    unset($_SESSION['audience_poll']);
    unset($_SESSION['phone_friend']);
} else {
    // Ensure all required session variables exist, but DON'T reset existing values
    if (!isset($_SESSION['game_started'])) $_SESSION['game_started'] = true;
}

// Check if all questions have been answered
if ($_SESSION['current_question'] >= count($questions)) {
    header("Location: win.php");
    exit();
}

// Initialize lifeline tracking if not set
if (!isset($_SESSION['hint_used'])) {
    $_SESSION['hint_used'] = false;
}
if (!isset($_SESSION['audience_used'])) {
    $_SESSION['audience_used'] = false;
}
if (!isset($_SESSION['phone_used'])) {
    $_SESSION['phone_used'] = false;
}

// Handle 50/50 hint request
if (isset($_POST['use_hint']) && !$_SESSION['hint_used']) {
    $_SESSION['hint_used'] = true;
    
    $current_question = $questions[$_SESSION['current_question']];
    $correct_answer = $current_question['answer'];
    
    // Get all incorrect answer indices
    $incorrect_indices = [];
    for ($i = 0; $i < count($current_question['options']); $i++) {
        if ($i != $correct_answer) {
            $incorrect_indices[] = $i;
        }
    }
    
    // Randomly select one incorrect answer to keep visible
    $keep_incorrect = $incorrect_indices[array_rand($incorrect_indices)];
    
    // Store which answers to hide
    $hide_indices = [];
    foreach ($incorrect_indices as $index) {
        if ($index != $keep_incorrect) {
            $hide_indices[] = $index;
        }
    }
    
    $_SESSION['hidden_answers'] = $hide_indices;
}

// Handle Ask the Audience request
if (isset($_POST['use_audience']) && !$_SESSION['audience_used']) {
    $_SESSION['audience_used'] = true;
    
    $current_question = $questions[$_SESSION['current_question']];
    $correct_answer = $current_question['answer'];
    
    // Generate audience poll percentages
    $percentages = [0, 0, 0, 0];
    
    // Give correct answer a higher probability (40-70%)
    $correct_percentage = rand(40, 70);
    $percentages[$correct_answer] = $correct_percentage;
    
    // Distribute remaining percentage among other answers
    $remaining = 100 - $correct_percentage;
    $other_answers = [];
    for ($i = 0; $i < 4; $i++) {
        if ($i != $correct_answer) {
            $other_answers[] = $i;
        }
    }
    
    // Randomly distribute remaining percentage
    for ($i = 0; $i < count($other_answers) - 1; $i++) {
        $max_remaining = $remaining - (count($other_answers) - $i - 1);
        $percentage = rand(1, max(1, $max_remaining));
        $percentages[$other_answers[$i]] = $percentage;
        $remaining -= $percentage;
    }
    $percentages[$other_answers[count($other_answers) - 1]] = $remaining;
    
    $_SESSION['audience_poll'] = $percentages;
}

// Handle Phone a Friend request
if (isset($_POST['use_phone']) && !$_SESSION['phone_used']) {
    $_SESSION['phone_used'] = true;
    
    $current_question = $questions[$_SESSION['current_question']];
    $correct_answer = $current_question['answer'];
    
    // Array of friend names and their characteristics
    $friends = [
        ['name' => 'Alex', 'confidence' => 'high', 'accuracy' => 85],
        ['name' => 'Sarah', 'confidence' => 'medium', 'accuracy' => 75],
        ['name' => 'Mike', 'confidence' => 'high', 'accuracy' => 80],
        ['name' => 'Emma', 'confidence' => 'low', 'accuracy' => 65],
        ['name' => 'David', 'confidence' => 'medium', 'accuracy' => 70],
        ['name' => 'Lisa', 'confidence' => 'high', 'accuracy' => 90]
    ];
    
    // Randomly select a friend
    $friend = $friends[array_rand($friends)];
    
    // Determine if friend gives correct answer based on their accuracy
    $gives_correct = (rand(1, 100) <= $friend['accuracy']);
    $suggested_answer = $gives_correct ? $correct_answer : rand(0, 3);
    
    // Generate response based on confidence level
    $confidence_phrases = [
        'high' => ['I\'m pretty sure it\'s', 'I think it\'s definitely', 'I\'m confident it\'s', 'I believe it\'s'],
        'medium' => ['I think it might be', 'I\'m fairly sure it\'s', 'I believe it could be', 'My guess would be'],
        'low' => ['I\'m not sure, but maybe', 'I think it could be', 'I\'m guessing it\'s', 'I\'m not certain, but']
    ];
    
    $phrase = $confidence_phrases[$friend['confidence']][array_rand($confidence_phrases[$friend['confidence']])];
    $answer_letter = chr(65 + $suggested_answer);
    
    $friend_response = $phrase . ' ' . $answer_letter . '.';
    
    // Add some extra uncertainty for low confidence
    if ($friend['confidence'] === 'low') {
        $uncertainty = [' But I\'m really not sure.', ' Don\'t quote me on that!', ' That\'s just a wild guess.'];
        $friend_response .= $uncertainty[array_rand($uncertainty)];
    }
    
    $_SESSION['phone_friend'] = [
        'name' => $friend['name'],
        'response' => $friend_response,
        'confidence' => $friend['confidence']
    ];
}

$current_question = $questions[$_SESSION['current_question']];
$question_number = $_SESSION['current_question'] + 1;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Question <?php echo $question_number; ?> - Who Wants to Be a Millionaire</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="game-body">
    <div class="game-container">
        <div class="money-circle">
            $<?php echo number_format($_SESSION['total_winnings']); ?>
        </div>

        <div class="question-info">
            <h2>Question <?php echo $question_number; ?> of <?php echo count($questions); ?></h2>
            <p>For $<?php echo number_format($current_question['prize']); ?></p>
        </div>

        <div class="question-text">
            <?php echo $current_question['question']; ?>
        </div>

        <!-- Lifelines Container -->
        <div class="lifelines-container">
            <!-- 50/50 Hint Button -->
            <?php if (!$_SESSION['hint_used']): ?>
                <div class="lifeline-item">
                    <form method="post" style="display: inline;">
                        <button type="submit" name="use_hint" class="hint-btn">
                            50:50
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="lifeline-item">
                    <span class="lifeline-used">50:50 Used</span>
                </div>
            <?php endif; ?>

            <!-- Ask the Audience Button -->
            <?php if (!$_SESSION['audience_used']): ?>
                <div class="lifeline-item">
                    <form method="post" style="display: inline;">
                        <button type="submit" name="use_audience" class="audience-btn">
                            Ask Audience
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="lifeline-item">
                    <span class="lifeline-used">Audience Used</span>
                </div>
            <?php endif; ?>

            <!-- Phone a Friend Button -->
            <?php if (!$_SESSION['phone_used']): ?>
                <div class="lifeline-item">
                    <form method="post" style="display: inline;">
                        <button type="submit" name="use_phone" class="phone-btn">
                            Phone Friend
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="lifeline-item">
                    <span class="lifeline-used">Phone Used</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Audience Poll Results -->
        <?php if (isset($_SESSION['audience_poll'])): ?>
            <div class="audience-poll">
                <h3>Audience Poll Results:</h3>
                <div class="poll-results">
                    <?php 
                    $poll = $_SESSION['audience_poll'];
                    for ($i = 0; $i < 4; $i++): 
                    ?>
                        <div class="poll-bar">
                            <div class="poll-label"><?php echo chr(65 + $i); ?>:</div>
                            <div class="poll-bar-container">
                                <div class="poll-bar-fill" style="width: <?php echo $poll[$i]; ?>%"></div>
                                <span class="poll-percentage"><?php echo $poll[$i]; ?>%</span>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Phone a Friend Response -->
        <?php if (isset($_SESSION['phone_friend'])): ?>
            <div class="phone-response">
                <h3>📞 Your friend <?php echo $_SESSION['phone_friend']['name']; ?> says:</h3>
                <div class="friend-message <?php echo $_SESSION['phone_friend']['confidence']; ?>-confidence">
                    "<?php echo $_SESSION['phone_friend']['response']; ?>"
                </div>
            </div>
        <?php endif; ?>

        <form method="post" action="result.php" class="answer-grid">
            <?php 
            $hidden_answers = isset($_SESSION['hidden_answers']) ? $_SESSION['hidden_answers'] : [];
            foreach ($current_question['options'] as $index => $option): 
                $is_hidden = in_array($index, $hidden_answers);
            ?>
                <button type="submit" name="answer" value="<?php echo $index; ?>" 
                        class="answer-btn <?php echo $is_hidden ? 'hidden-answer' : ''; ?>"
                        <?php echo $is_hidden ? 'disabled' : ''; ?>>
                    <span class="label">
                        <?php echo chr(65 + $index); ?>:
                    </span> 
                    <?php echo $is_hidden ? '[Hidden]' : $option; ?>
                </button>
            <?php endforeach; ?>
        </form>
    </div>
</body>
</html>
