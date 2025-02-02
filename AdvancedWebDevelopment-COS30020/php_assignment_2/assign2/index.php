<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Friends System</title>
  <link rel="stylesheet" href="style.css"> 
</head>

<body>
  <div class="container">
    <h1>My Friends System</h1> 
    <ul class="navbar">
      <li><a href="index.php">Home</a></li>
      <li><a href="signup.php">Sign Up</a></li>
      <li><a href="login.php">Login</a></li>
      <li><a href="about.php">About</a></li>
    </ul> 
  </div>

  <div class="container">
    <h1>Home Page</h1> 
    <p>Name: Vu Duc Tran</p> 
    <p>Student ID: 104175614</p> 
    <p>Email: <a class="email" href="mailto:104175614@student.swin.edu.au">104175614@student.swin.edu.au</a></p>
    <p>I declare that this assignment is my individual work. I have not worked collaboratively nor have I copied from any other students' work or from any other source.</p>
  </div>

  <div class="container">
    <?php
    require_once("settings.php"); 

    # create table "friends" if it doesn't exist
    $sql1 = "CREATE TABLE IF NOT EXISTS $table1 (
      friend_id INT NOT NULL AUTO_INCREMENT,
      friend_email VARCHAR(50) NOT NULL,
      password VARCHAR(20) NOT NULL,
      profile_name VARCHAR(30) NOT NULL,
      date_started DATE NOT NULL,
      num_of_friends INT UNSIGNED,
      PRIMARY KEY (friend_id)
    )";

    # execute query to create table "friends"
    if (mysqli_query($conn, $sql1)) {
      echo "<p class='success'>Table $table1 created successfully.</p>";
    } else {
      echo "<p class='error'>Error creating table: " . mysqli_error($conn) . "</p>";
    }

    # create table "myfriends" if it doesn't exist
    $sql2 = "CREATE TABLE IF NOT EXISTS $table2 (
    friend_id1 INT NOT NULL,
    friend_id2 INT NOT NULL
    )";

    # execute query to create table "myfriends"
    if (mysqli_query($conn, $sql2)) {
      echo "<p class='success'>Table $table2 created successfully.</p>";
    } else {
      echo "<p class='error'>Error creating table: " . mysqli_error($conn) . "</p>";
    }

    # check if table "friends" has data
    $sql3 = "SELECT * FROM $table1";
    $result = mysqli_query($conn, $sql3);
    if (mysqli_num_rows($result) > 0) {
      echo "<p class='success'>Table $table1 populated.</p>";
    } else {
      # insert sample data into table "friends"
      $sql4 = "INSERT INTO $table1 (friend_email, password, profile_name, date_started, num_of_friends)
      VALUES
      ('duc1@example.com', 'password1', 'Minh Chau', '2024-08-01', 4),
      ('duc2@example.com', 'password2', 'Manh Dung', '2024-08-02', 4),
      ('duc3@example.com', 'password3', 'Duy Hung', '2024-08-03', 4),
      ('duc4@example.com', 'password4', 'Quang Ngo', '2024-08-04', 4),
      ('duc5@example.com', 'password5', 'Quang Tu', '2024-08-05', 4),
      ('duc6@example.com', 'password6', 'Vu Dung', '2024-08-06', 4),
      ('duc7@example.com', 'password7', 'Hoang Minh', '2024-08-07', 4),
      ('duc8@example.com', 'password8', 'Vu Vinh', '2024-08-08', 4),
      ('duc9@example.com', 'password9', 'Duc Lam', '2024-08-09', 4),
      ('duc10@example.com', 'password10', 'Ngoc Bach', '2024-08-10', 4)";

      # execute query to insert sample data into "friends"
      if (mysqli_query($conn, $sql4)) {
        echo "<p class='success'>Sample data populated to table $table1 successfully.</p>";
      } else {
        echo "<p class='error'>Error inserting data: " . mysqli_error($conn) . "</p>";
      }
    }

    # check if table "myfriends" has data
    $sql5 = "SELECT * FROM $table2";
    $result = mysqli_query($conn, $sql5);
    if (mysqli_num_rows($result) > 0) {
      echo "<p class='success'>Table $table2 populated.</p>";
    } else {
      # insert sample data into table "myfriends"
      $sql4 = "INSERT INTO myfriends (friend_id1, friend_id2)
      VALUES
      (1, 2),
      (2, 3),
      (3, 4),
      (4, 5),
      (5, 6),
      (6, 7),
      (7, 8),
      (8, 9),
      (9, 10),
      (10, 1),
      (1, 3),
      (2, 4),
      (3, 5),
      (4, 6),
      (5, 7),
      (6, 8),
      (7, 9),
      (8, 10),
      (9, 1),
      (10, 2)";

      # execute query to insert sample data into "myfriends"
      if (mysqli_query($conn, $sql4)) {
        echo "<p class='success'>Sample data populated to table $table2 successfully.</p>";
      } else {
        echo "<p class='error'>Error inserting data: " . mysqli_error($conn) . "</p>";
      }
    }

    # close database connection
    mysqli_close($conn);
    ?>
  </div>
</body>

</html>
