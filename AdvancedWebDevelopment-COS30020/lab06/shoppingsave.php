<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="description" content="Web application development" />
<meta name="keywords" content="PHP" />
<meta name="author" content="Your Name" />
<title>Shopping List</title>
</head>
<body>
<h1>Shopping List</h1>
<?php
if (isset($_POST["item"]) && isset($_POST["quantity"])) { 
    $item = $_POST["item"]; 
    $qty = $_POST["quantity"]; 
    $filename = "../../data/shop.txt"; 
    $alldata = array(); 
    
    if (file_exists($filename)) {
        $itemdata = array(); 
        $handle = fopen($filename, "r"); 
        while (!feof($handle)) { 
            $onedata = fgets($handle); 
            if ($onedata != "") { 
                $data = explode(",", trim($onedata)); 
                $alldata[] = $data; 
                $itemdata[] = $data[0]; 
            }
        }
        fclose($handle); 
        $newdata = !in_array($item, $itemdata); 
    } else {
        $newdata = true; 
    }
    
    if ($newdata) {
        $handle = fopen($filename, "a"); 
        $data = $item . "," . $qty . "\n"; 
        fputs($handle, $data);
        fclose($handle);
        $alldata[] = array($item, $qty); 
        echo "<p>Shopping item added</p>";
    } else {
        echo "<p>Shopping item already exists</p>";
    }
    
    usort($alldata, function($a, $b) { return strcmp($a[0], $b[0]); }); 
    echo "<p>Shopping List</p>";
    foreach ($alldata as $data) { 
        echo "<p>", "Item: ", htmlspecialchars($data[0]), " , Quanity: ", htmlspecialchars($data[1]), "</p>";
    }
} else { 
    echo "<p>Please enter item and quantity in the input form.</p>";
}
?>
</body>
</html>
