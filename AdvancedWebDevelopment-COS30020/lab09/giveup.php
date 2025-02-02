<?php
session_start(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Give Up" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Your Name" />
    <title>Give Up</title>
</head>
<body>
    <h1>You Gave Up!</h1>
    
    <?php
    if (isset($_SESSION['randomNumber'])) {
        echo "<p>The correct number was: " . $_SESSION['randomNumber'] . "</p>";
    }
    ?>
    
    <p><a href="startover.php">Start Over</a></p>
</body>
</html>
