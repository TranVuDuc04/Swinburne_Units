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
    <form action="shoppingsave.php" method="post">
        <label for="item">Enter an item name: </label>
        <input type="text" id="item" name="item" required />
        <br /><br />
        <label for="quantity">Enter a quantity: </label>
        <input type="text" id="quantity" name="quantity" required />
        <br /><br />
        <input type="submit" value="Submit" />
    </form>
</body>
</html>
