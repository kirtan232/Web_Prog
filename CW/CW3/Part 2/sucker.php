<?php
$name = $_POST["name"] ?? "";   // Get values from POST request
$section = $_POST["section"] ?? "";
$card = $_POST["card"] ?? "";
$cardtype = $_POST["cardtype"] ?? "";


// Check if any field is missing.
if (empty($name) || empty($section) || empty($card) || empty($cardtype)) {

// And If any field is empty, It will show this part.
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">   <!-- I keeped the links just because it was on the given format, I learned what they do, but I don't think we need them-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Sorry</title>
    <link href="buyagrade.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <h1>Sorry</h1>
    <p>You didn't fill out the form completely. <a href="buyagrade.html">Try again?</a></p> <!-- Clicking try again wil take the user back to the buyagrade.html(form page).-->
</body>
</html>

<?php
    exit;
}

// Save the submission to a file so it will get the content from the "form" and save it to the suckers.txt file.
$record = "\n$name;$section;$card;$cardtype";   //  In this format 
file_put_contents("suckers.txt", $record, FILE_APPEND);
$contents = file_get_contents("suckers.txt"); 
?>

<!-- This part will be displayed if everyting was field.-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">  <!-- I keeped the links just because it was on the given format, I learned what they do, but I don't think we need them-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Buy Your Way to a Better Education!</title>
    <link href="buyagrade.css" type="text/css" rel="stylesheet" />
</head>

<body>
    <h1>Thanks, sucker!</h1>
    <p>Your information has been recorded.</p>

    <dl>
        <dt>Name:</dt>
        <dd><?= htmlspecialchars($name) ?></dd>

        <dt>Assignment:</dt>
        <dd><?= htmlspecialchars($section) ?></dd>

        <dt>Credit Card:</dt>
        <dd><?= htmlspecialchars($card) ?> (<?= htmlspecialchars($cardtype) ?>)</dd>  <!-- This format = card_nummber(cardtype)-->
    </dl>

    <h3>Here are all the suckers who have submitted here:</h3> 
    <pre><?= htmlspecialchars($contents) ?></pre>   <!-- Will print the contents of the suckers.txt file where the data is stored. -->
</body>
</html>
