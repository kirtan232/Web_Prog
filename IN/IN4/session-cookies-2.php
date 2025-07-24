<?php
// check if person has logged in previously
session_start();
$processingOK = "not yet";
$firstLogin = "no";

if (isset($_SESSION['authorized'])) {
  // user already logged in
  $processingOK = $_SESSION['authorized'];
} else {
  // user not logged in, so check password
  $password = trim($_POST['password']);
  if ($password == 'Test') {
    // correct password given
    $processingOK = 'ok';
    $_SESSION['authorized'] = 'ok';
    $firstLogin = "yes";
  } else {
    // invalid password
  }
}

// cookie processing for data of last visit and the number of visits
$today = date('l, F j, Y');
$timestamp = date('g:i A');
if (isset($_COOKIE['LAST_VISIT']) && strcmp($_COOKIE['LAST_VISIT'], "") == 0) {
  $lasttime = "";
} else {
  $lasttime = isset($_COOKIE['LAST_VISIT']) ? $_COOKIE['LAST_VISIT'] : "";
}
$LAST_VISIT = $today . " at " . $timestamp;

// set last_visit cookie with date/time, with expiration for 2 full weeks
setcookie("LAST_VISIT", $LAST_VISIT, time() + 3600 * 24 * 14);

if (isset($_COOKIE['VISIT_NUMBER']) && $_COOKIE['VISIT_NUMBER'] == 0) {
  $visitcount = 0;
} else {
  $visitcount = isset($_COOKIE['VISIT_NUMBER']) ? $_COOKIE['VISIT_NUMBER'] : 0;
}

// set visit_number cookie with count, with expiration for 2 full weeks
setcookie("VISIT_NUMBER", 1 + $visitcount, time() + 3600 * 24 * 14);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <META http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
  <title>Some Web Information About You</title>
  <link rev="made" href="mailto:walker@cs..edu"">
</head>

<body>
  <h1>Examples of Cookies and Session Variables, Part 2</h1>

  <?php
  // check authorization
  if ($processingOK != 'ok') {
  ?>
    <p>
      Authorization Failure!
    </p>

    <p>
      Please log in before accessing this page.
    </p>

  <?php
    exit();
  }

  // after authorization check, can assume authorization ok in what follows
  ?>

  <h2>Authorization Succeeded</h2>

  <?php
  if ($firstLogin == "yes") {
  ?>
    <p>
      Your password was correct and  you are now logged in.
    </p>
  <?php
  } else {
  ?>
    <p>
      Welcome back &mdash; You had logged in previously.
    </p>
  <?php
  }
  ?>

  <p>
    Processing can continue.
  </p>

  <h2>History Information</h2>
  <?php
  if ((strcmp($lasttime, "") == 0) && ($visitcount == 0)) {
    print("There is no record when you last visited this page previously");
  } else {
    if ($visitcount == 1) {
      print("You have visited this Web page once previously,<br>");
    } else {
      print("You have visited this Web page $visitcount times.<br>");
    }
    print("and that visit was on $lasttime");
  }
  ?>
</body>
</html>