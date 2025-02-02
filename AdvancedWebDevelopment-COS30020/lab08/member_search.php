<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search VIP Member</title>
</head>
<body>
    <h1>Search VIP Member by Last Name</h1>
    <form action="member_search.php" method="post">
        <label for="lname">Last Name:</label>
        <input type="text" name="lname" required>
        <input type="submit" value="Search">
    </form>

    <?php
    require_once("settings.php");

    if (isset($_POST['lname'])) {
        // Connect to the database
        $conn = @mysqli_connect($host, $user, $pswd, $dbnm);

        if (!$conn) {
            echo "<p>Database connection failure</p>";
        } else {
            $lname = $_POST['lname'];
            $query = "SELECT member_id, fname, lname, email FROM vipmembers WHERE lname LIKE '%$lname%'";
            $result = mysqli_query($conn, $query);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<table border='1'>
                            <tr>
                                <th>Member ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                            </tr>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['member_id']}</td>
                                <td>{$row['fname']}</td>
                                <td>{$row['lname']}</td>
                                <td>{$row['email']}</td>
                              </tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>No members found with that last name.</p>";
                }
            } else {
                echo "<p>Error searching members: " . mysqli_error($conn) . "</p>";
            }

            mysqli_close($conn);
        }
    }
    ?>
</body>
</html>
