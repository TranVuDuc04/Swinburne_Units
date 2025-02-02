<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="description" content="Web application development" />
<meta name="keywords" content="PHP" />
<meta name="author" content="Your Name" />
<title>Guest Book Form</title>
</head>
<body>
<h1>Guest Book Form</h1>
<form action="guestbooksave.php" method="post">
    <label for="name">Name:</label>
    <input type="text" id="name" name="name"/><br />
    <label for="email">Email:</label>
    <input type="text" id="email" name="email" /><br />
    <input type="submit" value="Sign" />
    <input type="reset" value="Reset Form" />
</form>
<p><a href="guestbookshow.php">View Guest Book</a></p>
</body>
</html>
