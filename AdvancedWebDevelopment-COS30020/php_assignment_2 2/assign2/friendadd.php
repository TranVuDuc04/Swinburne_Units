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

  // check if the user is logged in 
  if (!isset($_SESSION["email"]) || !isset($_SESSION["loggedIn"])) {
    header("Location: login.php"); // redirect to login page if not logged in
    exit(); 
  }

  require_once("settings.php"); 

  // get the user's friend details from the database
  $sql = "SELECT friend_id, profile_name, num_of_friends FROM friends WHERE friend_email = ?";
  $stmt = mysqli_prepare($conn, $sql); 
  mysqli_stmt_bind_param($stmt, "s", $_SESSION["email"]); 
  mysqli_stmt_execute($stmt); 
  $result = mysqli_stmt_get_result($stmt); 
  $row = mysqli_fetch_assoc($result); 

  // store user's profile information i
  $profileName = $row["profile_name"];
  $numOfFriends = $row["num_of_friends"];
  $userId = $row["friend_id"];

  // function to add a friend
  function addFriend($friendId)
  {
    global $conn, $numOfFriends, $userId, $table1, $table2; 

    // insert the new friendship into the friendship table
    $sql = "INSERT INTO $table2 (friend_id1, friend_id2) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "ii", $userId, $friendId); 
    mysqli_stmt_execute($stmt); 

    //update the number of friends for the logged-in user
    $numOfFriends++; 
    $sql = "UPDATE $table1 SET num_of_friends = ? WHERE friend_id = ?";
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "ii", $numOfFriends, $userId); 
    mysqli_stmt_execute($stmt); 

    // get the current number of friends for the newly added friend
    $sql = "SELECT num_of_friends FROM $table1 WHERE friend_id = ?";
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "i", $friendId); 
    mysqli_stmt_execute($stmt); 
    $result = mysqli_stmt_get_result($stmt); 
    $row = mysqli_fetch_assoc($result); 

    //update the number of friends for the new friend
    $numOfFriends2 = $row["num_of_friends"];
    $numOfFriends2++; 
    $sql = "UPDATE $table1 SET num_of_friends = ? WHERE friend_id = ?";
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "ii", $numOfFriends2, $friendId); 
    mysqli_stmt_execute($stmt); 
  }

  //check if the 'addfriend' button has been pressed
  if (isset($_POST["addfriend"])) {
    addFriend($_POST["friendId"]); 
    header("Location: friendadd.php?page={$_POST['page']}"); 
    exit(); 
  }

  // number of friends display per page (10)
  $limit = 10;
  
  //count the total number of friends available to add
  $sql = "SELECT COUNT(f.friend_id) AS total_names FROM $table1 f 
          WHERE f.friend_id != ? 
          AND f.friend_id NOT IN (SELECT mf.friend_id1 FROM $table2 mf WHERE mf.friend_id2 = ?) 
          AND f.friend_id NOT IN (SELECT mf.friend_id2 FROM $table2 mf WHERE mf.friend_id1 = ?)";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "iii", $userId, $userId, $userId); 
  mysqli_stmt_execute($stmt); 
  $result = mysqli_stmt_get_result($stmt); 
  $row = mysqli_fetch_assoc($result); 

  //calculate total number of friends and pages 
  $totalNames = $row["total_names"];
  $totalPages = ceil($totalNames / $limit); 
  $currentPage = isset($_GET['page']) ? max(1, min($_GET['page'], $totalPages)) : 1; //get current page or default to 1
  $offset = ($currentPage - 1) * $limit; 

  //get the list of friends available to add 
  $sql = "SELECT f.friend_id, f.profile_name FROM $table1 f 
          WHERE f.friend_id != ? 
          AND f.friend_id NOT IN (SELECT mf.friend_id1 FROM $table2 mf WHERE mf.friend_id2 = ?) 
          AND f.friend_id NOT IN (SELECT mf.friend_id2 FROM $table2 mf WHERE mf.friend_id1 = ?) 
          LIMIT ?, ?";
  $stmt = mysqli_prepare($conn, $sql); 
  mysqli_stmt_bind_param($stmt, "iiiii", $userId, $userId, $userId, $offset, $limit); 
  mysqli_stmt_execute($stmt); 
  $result = mysqli_stmt_get_result($stmt); 
  ?>

  <div class="main-content">
    <h2 class="page-title"><?php echo $profileName ?>'s Add Friend Page</h2> 
    <h2>Total number of friends: <?php echo $numOfFriends ?></h2> 
    <div>
      <?php
      // check if there are any friends available to add
      if (mysqli_num_rows($result) > 0) {
        echo "<table class='friend-table'>"; // table for displaying friends
        echo "<thead><tr><th>Profile Name</th><th>Mutual Friends</th><th>Action</th></tr></thead>"; 

        while ($row = mysqli_fetch_assoc($result)) { 
          $friendId = $row["friend_id"]; 
          $friendProfileName = $row["profile_name"]; // friend's profile name

          //query to count mutual friends
          $sql2 = "SELECT friend_id, COUNT(*) AS mutual_friend_count FROM $table1 AS f 
                   JOIN $table2 AS mf ON (f.friend_id = mf.friend_id1 AND mf.friend_id2 = {$row['friend_id']}) 
                   OR (f.friend_id = mf.friend_id2 AND mf.friend_id1 = {$row['friend_id']}) 
                   WHERE f.friend_id != ? 
                   AND f.friend_id IN (SELECT friend_id1 FROM $table2 WHERE friend_id2 = $userId 
                   UNION SELECT friend_id2 FROM $table2 WHERE friend_id1 = $userId)";

          $stmt = mysqli_prepare($conn, $sql2); // prepare the mutual friend count query
          mysqli_stmt_bind_param($stmt, "i", $userId); 
          mysqli_stmt_execute($stmt); 
          $result2 = mysqli_stmt_get_result($stmt); 
          $row = mysqli_fetch_assoc($result2); 

          $mutualFriendCount = $row["mutual_friend_count"];
          echo "<tbody>";
          echo "<tr>";
          echo "<td>{$friendProfileName}</td>"; // display friend's profile name
          echo "<td>{$mutualFriendCount} mutual friends</td>"; // display mutual friends 
          echo "<td>
                  <form method='post' action='friendadd.php'> 
                    <input type='hidden' name='friendId' value='{$friendId}'> 
                    <input type='hidden' name='page' value='{$currentPage}'> 
                    <input class='action-button' type='submit' name='addfriend' value='Add as friend'> 
                  </form>
                </td>";
          echo "</tr>";
          echo "</tbody>";
        }
        echo "</table>";

        echo "<div class='pagination'>";
        if ($currentPage > 1) {
          $previousPage = $currentPage - 1; 
          echo "<a href='friendadd.php?page={$previousPage}' class='pagination-link'>Previous</a>&nbsp;"; 
        }
        for ($i = 1; $i <= $totalPages; $i++) {
          $activeClass = ($i == $currentPage) ? 'active' : ''; 
          echo "<a href='friendadd.php?page={$i}' class='pagination-link {$activeClass}'>$i</a>&nbsp;";
        }
        if ($currentPage < $totalPages) {
          $nextPage = $currentPage + 1; 
          echo "<a href='friendadd.php?page={$nextPage}' class='pagination-link'>Next</a>"; 
        }
        echo "</div>";
      } else {
        echo "<p class='message'>No friends to add.</p>"; // if there's no friends available
      }
      mysqli_stmt_close($stmt); 
      mysqli_close($conn); 
      ?>
      <div class="navigation-links">
        <p><a class="link-button" href="friendlist.php"><span>Friend Lists</span></a></p><br> 
        <p><a class="link-button" href="logout.php"><span>Log out</span></a></p> 
      </div>
    </div>
  </div>
</body>

</html>
