<?php
require_once("settings.php"); // Connection credentials

// Create a connection to the MySQL database
$conn = @mysqli_connect($host, $user, $pswd, $dbnm);

if (!$conn) {
    echo "<p>Database connection failure</p>";
} else {
    // Create the table if it doesn't exist
    $createTableQuery = "CREATE TABLE IF NOT EXISTS vipmembers (
        member_id INT AUTO_INCREMENT PRIMARY KEY,
        fname VARCHAR(40),
        lname VARCHAR(40),
        gender VARCHAR(1),
        email VARCHAR(40),
        phone VARCHAR(20)
    )";

    if (!mysqli_query($conn, $createTableQuery)) {
        echo "<p>Error creating table: " . mysqli_error($conn) . "</p>";
    }

    // Insert member data into the table
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $insertQuery = "INSERT INTO vipmembers (fname, lname, gender, email, phone) 
                    VALUES ('$fname', '$lname', '$gender', '$email', '$phone')";

    if (mysqli_query($conn, $insertQuery)) {
        echo "<p>New VIP member added successfully!</p>";
    } else {
        echo "<p>Error adding member: " . mysqli_error($conn) . "</p>";
    }

    mysqli_close($conn);
}
?>
