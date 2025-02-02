<?php

function getDBConnection(){
    $host = "localhost";
    $username = "";
    $password = "";
    $db = "";

    $conn = mysqli_connect($host, $username, $password, $db);
    if($conn->connect_error){
        die("connection failed: ". $conn->connect_error);
    }
    return $conn;
}

?>
