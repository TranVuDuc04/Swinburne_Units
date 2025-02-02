<!DOCTYPE html>
<html>
<head>
    <title>Leader Board</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <nav>
        <a href="leaderboard.php">Leader Board</a>
        <a href="index.php">Shooting</a>
        <a href="login.php">Recorder Login</a>
    </nav>
    <h1>Hall of Fame</h1>
    <?php
    // Include the database connection file
    require_once 'dbconnect.php';

    // Get the database connection
    $conn = getDBConnection();

    // SQL query to select archerID and name from the archer table
    $sql = "SELECT s.totalScore, s.competitionID, s.archerID, a.name, c.description
            FROM score s
            INNER JOIN archer a ON s.archerID = a.archerID
            INNER JOIN category c ON s.categoryID = c.categoryID
            ORDER BY s.totalScore DESC
            LIMIT 10";

    $result = $conn->query($sql);
    ?>

    <table>
        <tr>
            <th>Total Score</th>
            <th>Competition ID</th>
            <th>Archer ID</th>
            <th>Name</th>
            <th>Category Description</th>
        </tr>
        <?php
        // Check if the result set is not empty
        if ($result->num_rows > 0) {
            // Output data for each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['totalScore'] . "</td>";
                echo "<td>" . $row['competitionID'] . "</td>";
                echo "<td>" . $row['archerID'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['description'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No records found</td></tr>";
        }
        // Close the database connection
        $conn->close();
        ?>
    </table>
</body>
</html>
