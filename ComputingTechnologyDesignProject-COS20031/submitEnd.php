<?php
require 'main.php';
session_start();  // Start the session

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve the previously stored IDs from session
    $roundID = $_SESSION['roundID'];
    $competitionID = $_SESSION['competitionID'];
    $archerID = $_SESSION['archerID'];
    $archerCatID = $_SESSION['archerCatID'] ;

    $arrow1 = isset($_POST['arrow1']) ? intval($_POST['arrow1']) : 0;
    $arrow2 = isset($_POST['arrow2']) ? intval($_POST['arrow2']) : 0;
    $arrow3 = isset($_POST['arrow3']) ? intval($_POST['arrow3']) : 0;
    $arrow4 = isset($_POST['arrow4']) ? intval($_POST['arrow4']) : 0;
    $arrow5 = isset($_POST['arrow5']) ? intval($_POST['arrow5']) : 0;
    $arrow6 = isset($_POST['arrow6']) ? intval($_POST['arrow6']) : 0;
    $totalScore = isset($_POST['totalScore']) ? intval($_POST['totalScore']) : 0;

    // Insert the data into the score table
    $sqlInsert = "INSERT INTO score (roundID, competitionID, archerID, categoryID, arrow1, arrow2, arrow3, arrow4, arrow5, arrow6, totalScore)
                  VALUES ('$roundID', '$competitionID', '$archerID', '$archerCatID', '$arrow1', '$arrow2', '$arrow3', '$arrow4', '$arrow5', '$arrow6', '$totalScore')";
    if ($conn->query($sqlInsert) === TRUE) {
        echo "Scores inserted successfully.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
