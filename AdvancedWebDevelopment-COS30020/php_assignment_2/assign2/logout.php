<?php
//clear all session variables
session_start();

//unset all session variables
$_SESSION = array();

//destroy the session
session_destroy();

//redirect to home page
header('Location: index.php');
exit();
