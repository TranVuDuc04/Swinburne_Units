<?php
$dir = "../../data/lab06";
$filename = $dir . "/guestbook.txt";

if (!is_dir($dir)) {
    mkdir($dir, 02770, true); 
}

if (isset($_POST["name"]) && isset($_POST["email"])) {
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    if (!empty($name) && !empty($email)) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p>Email address is not valid.</p>";
            exit;
        }
        $newdata = true;
        if (file_exists($filename)) {
            $guestbookdata = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($guestbookdata as $entry) {
                list($existingName, $existingEmail) = explode(",", trim($entry));
                if ($name === $existingName || $email === $existingEmail) {
                    $newdata = false;
                    break;
                }
            }
        }

        if ($newdata) {
            $handle = fopen($filename, "a");
            $data = $name . "," . $email . "\n";
            if (fwrite($handle, $data)) {
                echo "<p>Thank you for signing the guest book.</p>";
            } else {
                echo "<p>Cannot add your entry to the guest book.</p>";
            }
            fclose($handle);
        } else {
            echo "<p>Name or email already exists.</p>";
        }
    } else {
        echo "<p>Please fill in both name and email.</p>";
    }
} else {
    echo "<p>Please fill in both name and email.</p>";
}
?>
