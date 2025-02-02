<?php
include('dbconnect.php');

// $host = "localhost";
// $username = "root";
// $password = "Phg1212.";
// $db = "archery";

// $conn = mysqli_connect($host, $username, $password, $db);
// if($conn->connect_error){
//     die("connection failed: ". $conn->connect_error);
// }
$conn = getDBConnection();
$roundName = readRoundName($conn);
$competitionName = readCompetitionName($conn);
$archerName = readArcherInfo($conn);
$categoryID = readCategory($conn);
$defaultEquipment = readDefaultEquipment($conn);

//read all rounds
function readRoundName($conn){
    $sql = "Select * from round";
    $result = $conn->query($sql);
    return $result;
}


// read all competition name
function readCompetitionName($conn){
    $sql = "Select name, competitionID from competition";
    $result = $conn->query($sql);
    return $result;
}

// read archer info
function readArcherInfo($conn){
    $sql = "Select name, archerID from archer";
    $result = $conn->query($sql);
    return $result;
}

// read category
function readCategory($conn){
    $sql = "Select archerCatID from archer_category";
    $result = $conn->query($sql);
    return $result;
}

// return default equipment
function readDefaultEquipment($conn){
    $sql = "Select equipmentID, type from equipment";
    $result = $conn->query($sql);
    return $result;
}
?>