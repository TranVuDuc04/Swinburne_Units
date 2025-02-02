<?php
require 'main.php';
session_start();  // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handling round initialization
    if (isset($_POST['roundID'])) {
        $roundID = $_POST['roundID'];
        $competitionID = $_POST['competitionID'];
        $archerID = $_POST['archerID'];
        $equipmentID = $_POST['equipmentID'];
        // name
        $competitionName = $_POST['competitionName'];
        $archerName = $_POST['archerName'];
        $equipmentName = $_POST['equipmentName'];
        $roundName = $_POST['roundName'];

        // Display the received data
        echo "$roundName <br>";
        echo "Competition: " . $competitionName . "<br>";
        echo "$archerName - $equipmentName<br>";

        // Retrieve categoryID based on the selected archerID from the archer table
        $sqlCat = "SELECT categoryID FROM archer WHERE archerID = '$archerID'";
        $resultCat = $conn->query($sqlCat);
        if ($resultCat->num_rows > 0) {
            $rowCat = $resultCat->fetch_assoc();
            $categoryID = $rowCat['categoryID'];

            // Insert the data into the archer_category table
            $sqlInsert = "INSERT INTO archer_category (archerID, equipmentID, competitionID, categoryID)
                          VALUES ('$archerID', '$equipmentID', '$competitionID', '$categoryID')";
            if ($conn->query($sqlInsert)) {
                // Get the last inserted ID and store it in session
                $archerCatID = $conn->insert_id;
                $_SESSION['archerCatID'] = $archerCatID;

                echo "Inserted into archer category successfully.";
            } else {
                echo "Error inserting into archer category: " . $conn->error;
            }

            // Store the data in session variables for later use
            $_SESSION['roundID'] = $roundID;
            $_SESSION['competitionID'] = $competitionID;
            $_SESSION['archerID'] = $archerID;
            $_SESSION['categoryID'] = $categoryID;
        } else {
            echo "No category found for the selected archer.";
        }
    }
}
?>
