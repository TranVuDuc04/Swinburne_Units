<?php
session_start(); 

if (!isset($_SESSION['randomNumber'])) {
    $_SESSION['randomNumber'] = rand(0, 100);
    $_SESSION['guesses'] = 0; //number of guesses = 0
}

if (isset($_POST['guess'])) {
    $guess = $_POST['guess'];

    if (is_numeric($guess) && $guess >= 0 && $guess <= 100) {
        $_SESSION['guesses']++; 
        if ($guess < $_SESSION['randomNumber']) {
            $message = "Your guess is too low!";
        } elseif ($guess > $_SESSION['randomNumber']) {
            $message = "Your guess is too high!";
        } else {
            $message = "Congratulations! You've guessed the correct number in " . $_SESSION['guesses'] . " tries.";
        }
    } else {
        $message = "Please enter a valid number between 0 and 100.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Guessing Game" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Your Name" />
    <title>Guessing Game</title>
</head>
<body>
    <h1>Guess the Number</h1>
    
    <form method="POST" action="guessinggame.php">
        <label for="guess">Enter your guess (0-100): </label>
        <input type="number" id="guess" name="guess" min="0" max="100" required />
        <button type="submit">Submit</button>
    </form>
    
    <?php
    if (isset($message)) {
        echo "<p>$message</p>";
    }
    if (isset($_SESSION['guesses']) && $_SESSION['guesses'] > 0) {
        echo "<p>Number of guesses: " . $_SESSION['guesses'] . "</p>";
    }
    ?>

    <p><a href="giveup.php">Give Up</a></p>
    <p><a href="startover.php">Start Over</a></p>
</body>
</html>
