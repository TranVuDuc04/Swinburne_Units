<?php
mysqli_report(MYSQLI_REPORT_OFF); # turn off mysqli error reporting

# swin server credentials
$host = "feenix-mariadb.swin.edu.au";
$user = "s104175614";
$pswd = "Badinh22092004.";
$dbnm = "s104175614_db";

# tables used in the system
$table1 = "friends";
$table2 = "myfriends";

# connect to the database
$conn = @mysqli_connect($host, $user, $pswd, $dbnm);
if (!$conn) {
  exit(); # stop further execution
}
?>
