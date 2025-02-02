<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Web application development" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Your Name" />
    <title>TITLE</title>
</head>
<body>
    <h1>Web Programming Form - Lab 5</h1>
    <fieldset>
    <legend>Enter your details to sign our guest book</legend>
    <form action="guestbooksave.php" method="post">
        <label for="fName">First name </label>
        <input type="text" id="fName" name="fName" />
        <br /><br />
        <label for="lName">Last name </label>
        <input type="text" id="lName" name="lName" />
        <br /><br />
        <input type="submit" value="Submit" />
    </form>
    </fieldset>
    <br /><br />
    <a href="guestbookshow.php">Show Guest Book</a>
</body>
</html>
