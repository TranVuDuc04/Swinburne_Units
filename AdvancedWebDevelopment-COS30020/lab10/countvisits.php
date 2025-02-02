<?php
require_once('hitcounter.php');
require_once('../../data/lab10/mykeys.inc.php');

$counter = new HitCounter($host, $username, $password, $dbname, 'hitcounter');
echo "This page has received: " . $counter->getHits() . "hits" , "<br>";
$counter->setHits();
$counter->closeConnection();
?>
