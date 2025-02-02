<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Friends System</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php
  require_once("settings.php"); # import settings for database connection

  $inputEmail = $inputPassword = $msg1 = $msg2 = $msg3 = $msg4 = null; # initialize variables

  function validateField($fieldName, $fieldValue) {
    if (empty($fieldValue)) {
      echo "<span class='error'>$fieldName is empty</span>";
    } else {
      return $fieldValue;
    }
    return null;
  }

  function sanitizeInput($input) {
    $input = trim($input); # remove extra spaces
    $input = stripslashes($input); # remove backslashes
    $input = htmlspecialchars($input); # convert special characters to html entities
    return $input;
  }
  ?>
  
  <div class="container">
    <h1>My Friends System</h1> 
    <ul class="navbar">
      <li><a href="index.php">Home</a></li>
      <li><a href="signup.php">Sign Up</a></li>
      <li><a href="login.php">Login</a></li>
      <li><a href="about.php">About</a></li>
    </ul> 

    <h1>Log in page</h1>
    <form method="post" action="login.php" class="login-form"> 
      <div class="form-group">
        <label for="email">Email</label> <br>
        <input type="text" name="email" class="form-input" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $inputEmail = validateField("Email", sanitizeInput($_POST['email'])); # sanitize input and validate email
        }
        ?>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-input"> 
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $inputPassword = validateField("Password", sanitizeInput($_POST['password'])); # sanitize input and validate password
        }
        ?>
      </div>

      <?php
      if ($inputEmail && $inputPassword) {
        $sql = "SELECT friend_email, password FROM $table1 WHERE friend_email = ?"; # query to check email
        $stmt = mysqli_prepare($conn, $sql); # prepare the statement
        mysqli_stmt_bind_param($stmt, "s", $inputEmail); # bind email parameter
        mysqli_stmt_execute($stmt); # execute query
        mysqli_stmt_store_result($stmt); # store result

        if (mysqli_stmt_num_rows($stmt) > 0) {
          mysqli_stmt_bind_result($stmt, $dbEmail, $dbPassword); # bind result variables
          mysqli_stmt_fetch($stmt); # fetch result
          if ($dbPassword === $inputPassword) {
            session_start(); # set session variables and redirect to friendlist.php
            $_SESSION['email'] = $inputEmail;
            $_SESSION['loggedIn'] = true;
            header("Location: friendlist.php"); # redirect
            exit(); # stop further execution
          } else {
            echo "<p class='error'>Incorrect email or password</p>";
          }
        } else {
          echo "<p class='error'>Incorrect email or password</p>";
        }

        mysqli_stmt_close($stmt); # close statement
      }
      ?>

      <p>
        <input type="submit" value="Log in" class="link-button"> 
        <a href="login.php" class="clear-button">Clear</a>
      </p>
    </form>
  </div>
</body>

</html>
