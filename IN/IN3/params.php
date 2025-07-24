<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
  <title>Form Data</title>
</head>
<body>

<h1>Raw Form Data</h1>
<pre>
<?php
   print_r($_POST);  // this prints the associative array for debugging
?>
</pre>

<!-- Extract each form item from posted data -->

<h1>Form input values</h1>
<p>Your Name: <?php print $_POST["name"] ?></p>
<p><Address></Address>: <?php print $_POST["address"] ?></p>
<p>Gender: <?php print $_POST["gender"] ?></p>
<p>Date of birth: <?php print $_POST["birthday"] ?></p>
<p>Credit Card: <?php print $_POST["ccard"] ?></p>


<!-- Run thru all elements of the array of posted data -->

<h1>All Form Data</h1>

<?php
    foreach($_POST as $key=>$val)
    {
        print "<p>$key = $val\n</p>";
    }
?>

</body>
</html>
