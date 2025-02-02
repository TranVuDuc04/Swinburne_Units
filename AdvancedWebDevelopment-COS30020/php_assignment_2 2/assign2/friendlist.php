<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Friends System</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="navbar">
    <h1>My Friends System</h1> 
  </div>

  <?php
  session_start(); 

  // check if the user is logged in using session variables, if not return to login page
  if (!isset($_SESSION["email"]) || !isset($_SESSION["loggedIn"])) {
    header("Location: login.php");
    exit(); 
  }

  require_once("settings.php"); 

  // get the profile name and number of friends for the logged-in user
  $sql = "SELECT friend_id, profile_name, num_of_friends FROM friends WHERE friend_email = ?";
  $stmt = mysqli_prepare($conn, $sql); 
  mysqli_stmt_bind_param($stmt, "s", $_SESSION["email"]);
  mysqli_stmt_execute($stmt); 
  $result = mysqli_stmt_get_result($stmt); 
  $row = mysqli_fetch_assoc($result); 

  // store the profile name, number of friends, and user ID in variables
  $profileName = $row["profile_name"];
  $numOfFriends = $row["num_of_friends"];
  $userId = $row["friend_id"];

  // get the list of friends of the logged-in user
  $sql = "SELECT f.friend_id, f.profile_name
          FROM $table1 f JOIN $table2 mf 
          ON f.friend_id = mf.friend_id1 OR f.friend_id = mf.friend_id2
          WHERE (mf.friend_id1 = ? OR mf.friend_id2 = ?) 
          AND f.friend_id != ?";
  $stmt = mysqli_prepare($conn, $sql); 
  mysqli_stmt_bind_param($stmt, "iii", $userId, $userId, $userId); 
  mysqli_stmt_execute($stmt); 
  $result = mysqli_stmt_get_result($stmt); 

  // delete a friend from the friend's list
  function deleteFriend($friendId)
  {
    global $conn, $numOfFriends, $userId, $table1, $table2; 
    $sql = "DELETE FROM $table2 WHERE (friend_id1 = ? AND friend_id2 = ?) OR (friend_id1 = ? AND friend_id2 = ?)";
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "iiii", $userId, $friendId, $friendId, $userId); 
    mysqli_stmt_execute($stmt); 

    $numOfFriends--; // decrement the number of friends
    $sql = "UPDATE $table1 SET num_of_friends = ? WHERE friend_id = ?";
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "ii", $numOfFriends, $userId); 
    mysqli_stmt_execute($stmt); 

    // current number of friends for the friend being removed
    $sql = "SELECT num_of_friends FROM $table1 WHERE friend_id = ?";
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "i", $friendId); 
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt); 
    $row = mysqli_fetch_assoc($result); 

    $numOfFriends2 = $row["num_of_friends"]; 
    $numOfFriends2--; 

    $sql = "UPDATE $table1 SET num_of_friends = ? WHERE friend_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $numOfFriends2, $friendId); 
    mysqli_stmt_execute($stmt); 
  }

  // check if the 'unfriend' button has been pressed
  if (isset($_POST["unfriend"])) {
    deleteFriend($_POST["friendId"]); 
    // redirect to the friendlist page after unfriending
    header("Location: friendlist.php");
    exit(); 
  }
  ?>

  <div class="main-content">
    <h2 class="page-title"><?php echo $profileName ?>'s Friend List</h2> 
    <h2>Total number of friends: <?php echo $numOfFriends ?></h2> 
    <div>
    <?php
      // check if any friends are found
      if (mysqli_num_rows($result) > 0) {
        echo "<table class='friend-table'>"; 
        echo "<thead><tr><th>Profile Name</th><th>Action</th></tr></thead>"; 
        while ($row = mysqli_fetch_assoc($result)) { 
          $friendId = $row["friend_id"]; 
          $friendProfileName = $row["profile_name"]; 
          echo "<tbody>";
          echo "<tr>";
          echo "<td>{$friendProfileName}</td>"; 
          echo "<td>
                  <form method='post' action='friendlist.php'> 
                    <input type='hidden' name='friendId' value='{$friendId}'> 
                    <input class='action-button' type='submit' name='unfriend' value='Unfriend'> 
                  </form>
                </td>";
          echo "</tr>";
          echo "</tbody>";
        }
        echo "</table>"; //end table
      } else {
        echo "<p class='nofriend'>No friend found.</p>"; // If you have no friends
      }

      mysqli_stmt_close($stmt); 
      mysqli_close($conn); // close the database connection
      ?>
      <div class="navigation-links">
        <p><a class="link-button" href="friendadd.php"><span>Add Friends</span></a></p><br> 
        <p><a class="link-button" href="logout.php"><span>Log out</span></a></p> 
      </div>
    </div>
  </div>
</body>

</html>
