<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Web application development" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Your Name" />
    <title>Lab 4 - String Processing</title>
</head>
<body>
    <h1>Web Programming - Lab 4</h1>
    <?php 
    // read the comments for hints on how to answer each item
    if (isset($_POST["strr"])) { // check if form data exists
        $str = $_POST["strr"]; // obtain the form data
        $pattern = "/^[A-Za-z ]+$/"; // set regular expression pattern
        if (preg_match($pattern, $str)) { // check if $str matches the regular expression
            $ans = ""; // initialise variable for the answer
            $len = strlen($str); // obtain length of string $str
            for ($i = 0; $i < $len; $i++) { // checks all characters in $str
                $letter = substr($str, $i, 1); // extract 1 char using substr
                // check using strpos, is_numeric is used as strpos returns a number
                // (position) if found, and false otherwise
                if (strpos("AEIOUaeiou", $letter) === false) {
                    $ans = $ans . $letter; // concatenate letter to answer
                }
            }
            // generate answer after all letters are checked
            echo "<p>The word with no vowels is ", $ans, ".</p>";
        } else { // string contains invalid characters
            echo "<p>Please enter a string containing only letters or spaces.</p>";
        }
    } else { // no input
        echo "<p>Please enter a string from the input form.</p>";
    }
    ?>
</body>
</html>
