<?php
umask(0007);
$dir = "../../data/lab05";
if (!is_dir($dir)) {
    mkdir($dir, 02770, true); 
}

if (isset($_POST['fName']) && isset($_POST['lName'])) {
    $firstName = addslashes(trim($_POST['fName']));
    $lastName = addslashes(trim($_POST['lName']));

    if (!empty($firstName) && !empty($lastName)) {
        $guestName = $firstName . " " . $lastName;

        $filename = $dir . "/guestbook.txt";
        $handle = fopen($filename, "a"); 

        if ($handle) {
            $data = $guestName . "\n";
            if (fwrite($handle, $data)) {
                echo "<p>Thank you for signing the Guest book.</p>";
            } else {
                echo "<p>Cannot add your name to the Guest book.</p>";
            }
            fclose($handle); 
        } else {
            echo "<p>Cannot open the Guest book for writing.</p>";
        }
    } else {
        echo "<p>Please provide both first and last names. Use your browser's 'Go Back' button to return to the form.</p>";
    }
} else {
    echo "<p>Please provide both first and last names. Use your browser's 'Go Back' button to return to the form.</p>";
}
?>
