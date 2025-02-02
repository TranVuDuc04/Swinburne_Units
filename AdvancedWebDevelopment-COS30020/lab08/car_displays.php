<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="description" content="Web application development" />
<meta name="keywords" content="PHP" />
<meta name="author" content="Vu Duc Tran" />
<title>Cars Display</title>
<style>
    table {
        width: 50%;
        border-collapse: collapse;
        margin: 20px 0;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
</style>
</head>
<body>
<h1>Web Programming - Lab08</h1>

<?php
require_once ("settings.php");

$conn = @mysqli_connect($host, $user, $pswd, $dbnm);

if (!$conn) {
    echo "<p>Database connection failure</p>";
} else {
    $query = "SELECT car_id, make, model, price FROM cars";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        echo "<p>Something went wrong with the query.</p>";
    } else {
        echo "<table>";
        echo "<tr><th>Car ID</th><th>Make</th><th>Model</th><th>Price</th></tr>";

        //fetch and display each row of data
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$row['car_id']}</td>";
            echo "<td>{$row['make']}</td>";
            echo "<td>{$row['model']}</td>";
            echo "<td>{$row['price']}</td>";
            echo "</tr>";
        }

        echo "</table>";
        mysqli_free_result($result);
    }
    mysqli_close($conn);
}
?>

</body>
</html>
