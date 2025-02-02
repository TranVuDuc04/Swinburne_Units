<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="description" content="Web application development" />
<meta name="keywords" content="PHP" />
<meta name="author" content="Your Name" />
<title>Shopping Form</title>
</head>
<body>
<h1>Shopping Form</h1>
<form action="shoppingsave.php" method="post"> 
    <label for="item">Item:</label>
    <input type="text" id="item" name="item" required /><br />
    <label for="quantity">Quantity:</label>
    <input type="number" id="quantity" name="quantity" required /><br />
    <input type="submit" value="Add to Shopping List" />
</form>
</body>
</html>
