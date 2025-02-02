<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="description" content="Web application development" />
<meta name="keywords" content="PHP" />
<meta name="author" content="Your Name" />
<title>Guest Book</title>
</head>
<body>
<h1>Guest Book</h1>
<?php
$filename = "../../data/lab06/guestbook.txt";

if (file_exists($filename)) {
    $guestbookdata = array();
    $handle = fopen($filename, "r");
    while (!feof($handle)) {
        $onedata = fgets($handle);
        if ($onedata != "") {
            $data = explode(",", trim($onedata));
            $guestbookdata[] = $data;
        }
    }
    fclose($handle);
    
    usort($guestbookdata, function($a, $b) { return strcmp($a[0], $b[0]); });
    
    echo "<table border='1'>";
    echo "<tr><th>Name</th><th>Email</th></tr>";
    foreach ($guestbookdata as $data) {
        echo "<tr><td>" . htmlspecialchars($data[0]) . "</td><td>" . htmlspecialchars($data[1]) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<p>No guest book entries yet.</p>";
}
?>
</body>
</html>
