<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $ccard = trim($_POST['ccard'] ?? '');

    $entry = "Name: $name\nAddress: $address\nCredit Card: $ccard\n----------------------\n";

    file_put_contents("signup_data.txt", $entry, FILE_APPEND | LOCK_EX);

    echo "<h2>Signup successful!</h2>";
    echo "<p><a href='form.html'>Go back</a></p>";
} else {
    echo "<h2>Invalid Request</h2>";
}
?>
