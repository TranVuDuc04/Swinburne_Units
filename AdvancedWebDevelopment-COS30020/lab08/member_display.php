<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display VIP Members</title>
</head>
<body>
    <h1>VIP Members List</h1>
    <?php
    require_once("settings.php");

    // Connect to the database
    $conn = @mysqli_connect($host, $user, $pswd, $dbnm);

    if (!$conn) {
        echo "<p>Database connection failure</p>";
    } else {
        $query = "SELECT member_id, fname, lname FROM vipmembers";
        $result = mysqli_query($conn, $query);

        if ($result) {
            echo "<table border='1'>
                    <tr>
                        <th>Member ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                    </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['member_id']}</td>
                        <td>{$row['fname']}</td>
                        <td>{$row['lname']}</td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>Error retrieving members: " . mysqli_error($conn) . "</p>";
        }

        mysqli_close($conn);
    }
    ?>
</body>
</html>
