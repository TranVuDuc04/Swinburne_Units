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
    <h1>Web Programming Form - Lab 4</h1>
    <form action="perfectpalindrome.php" method="post">
        <label for="inputString">Enter a word or phrase:</label>
        <input type="text" id="inputString" name="inputString" required>
        <br /><br />
        <input type="submit" value="Check for Perfect Palindrome" />
    </form>
</body>
</html>