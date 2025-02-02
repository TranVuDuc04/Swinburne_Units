<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $host = $_POST['host'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $dbname = $_POST['databasename'];

    $directory = '../../data/lab10';

    if (!is_dir($directory)) {
        if (!mkdir($directory, 0777, true)) {
            die("Error: Unable to create the directory '$directory'. Please check permissions.");
        }
    }

    $keysFile = "$directory/mykeys.inc.php";
    $keysContent = "<?php\n\$host='$host';\n\$username='$username';\n\$password='$password';\n\$dbname='$dbname';\n?>";

    if (file_put_contents($keysFile, $keysContent) === false) {
        die("Error: Unable to write to file '$keysFile'. Please check permissions.");
    }

    $conn = new mysqli($host, $username, $password);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $createDBSQL = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($createDBSQL) === TRUE) {
        $conn->select_db($dbname);
    } else {
        die("Error creating database: " . $conn->error);
    }

    $createTableSQL = "CREATE TABLE IF NOT EXISTS `hitcounter` (
                        `id` SMALLINT NOT NULL PRIMARY KEY,
                        `hits` SMALLINT NOT NULL
                       )";

    if ($conn->query($createTableSQL) === TRUE) {
        $conn->query("INSERT INTO hitcounter VALUES (1, 0) ON DUPLICATE KEY UPDATE `hits` = `hits`");
        echo "Setup completed successfully.";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $conn->close();
}
?>

<html>
<body>
  <form method="post" action="setup.php">
    Host: <input type="text" name="host" value="feenix-mariadb.swin.edu.au" required><br>
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    Database Name: <input type="text" name="databasename" required><br>
    <input type="submit" name="submit" value="Set Up">
  </form>
</body>
</html>
