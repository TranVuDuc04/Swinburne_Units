<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Web application development" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Your Name" />
    <title>Lab 4 - Form Submission</title>
</head>
<body>
    <h1>Perfect Palindrome Checker</h1>
    <?php
    if (isset($_POST["inputString"])) {
        $inputString = $_POST["inputString"];
        $processedString = strtolower(preg_replace("/[^A-Za-z0-9]/", '', $inputString)); 
        $reversedString = strrev($processedString);
        if (strcmp($processedString, $reversedString) === 0) {
            echo "<p>The text you entered: '$inputString' is a perfect palindrome!</p>";
        } else {
            echo "<p>The text you entered: '$inputString' is not a perfect palindrome.</p>";
        }
    } else {
        echo "<p>Please enter a word or phrase.</p>";
    }
    ?>
</body>
</html>
