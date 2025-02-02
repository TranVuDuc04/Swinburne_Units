<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('dbconnect.php');
$conn = getDBConnection();
// Database connection
// $servername = "localhost";
// $username = "root";
// $password = "fivesurvive";
// $dbname = "archery";

// $conn = new mysqli($servername, $username, $password, $dbname);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Function to sanitize input data - prevent SQL injection
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recorderID = sanitize_input($_POST["recorderID"]);
    $password = sanitize_input($_POST["password"]);

    // Prepare and bind the SQL statement - prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM recorder WHERE recorderID = ?");
    $stmt->bind_param("s", $recorderID);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_password = $row["password"];

        // Verify password
        if ($password == $stored_password) {
            // Redirect to archer.php with success parameter
            header("Location: archer.php?success=true");
            exit;
        } else {
            $error = "Password is incorrect!";
        }
    } else {
        $error = "Invalid Recorder ID!";
    }

    // Close statement
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style2.css">
<title>Recorder Login</title>
</head>
<body>
    <nav>
        <a href="leaderboard.php">Leader Board</a>
        <a href="index.php">Shooting</a>
        <a href="login.php">Recorder Login</a>
    </nav>
<section class="container2">
    <h1>Recorder Login</h1>
    <br>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="recorderID">Recorder ID:</label>
            <input type="text" id="recorderID" name="recorderID"><br>
        </div>
        <div class="form-group">
            <br><label for="password">Password:</label>
            <input type="text" id="password" name="password">
            <br>
        </div>
        <br><button class="btn" type="submit">Login</button>
        <br>
        <!-- <?php if (isset($error)) { echo "<p style='color: red;'>$error</p>"; } ?> -->
    </form>
</section>

<div id="popup" class="popup"></div>

<script>
    // Function to show pop-up message
    function showMessage(message, isSuccess) {
        var popup = document.getElementById("popup");
        popup.textContent = message;
        popup.style.display = "block";
        if (isSuccess) {
            popup.style.backgroundColor = "green";
        } else {
            popup.style.backgroundColor = "red";
        }
        // Hide the pop-up after 3 seconds
        setTimeout(function() {
            popup.style.display = "none";
        }, 3000);
    }

    <?php if (isset($error)) { ?>
        // Show error message if login was unsuccessful
        showMessage("<?php echo $error; ?>", false);
    <?php } ?>

    <?php if (isset($_GET["success"]) && $_GET["success"] == "true") { ?>
        // Show success message if login was successful
        showMessage("Login successful!", true);
    <?php } ?>
</script>

</body>
</html>
