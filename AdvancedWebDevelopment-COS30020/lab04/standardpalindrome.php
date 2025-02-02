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
    <h1>Standard Palindrome Checker</h1>
    <?php
    if (isset($_POST["inputStringg"])) {
        $inputString = $_POST["inputStringg"];

        $charactersToRemove = [' ', '.', ',', '!', '?', "'", '"', '-', '_', '(', ')', '’'];

        $processedString = strtolower(str_replace($charactersToRemove, '', $inputString));

        $reversedString = strrev($processedString);

        if (strcmp($processedString, $reversedString) === 0) {
            echo "<p>The text you entered: '$inputString' is a standard palindrome!</p>";
        } else {
            echo "<p>The text you entered: '$inputString' is NOT a standard palindrome.</p>";
        }
    } else {
        echo "<p>Please enter a word or phrase.</p>";
    }
    ?>
</body>
</html>
