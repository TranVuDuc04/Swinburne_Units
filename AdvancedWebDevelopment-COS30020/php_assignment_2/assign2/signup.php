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
  require_once("settings.php"); 

  $mail = $profileName = $password = $confirmPassword = null; # initialize variables for user input
  $isMailUnique = $arePasswordsMatch = true; # flags for email uniqueness and password match

  # regex patterns for validating input fields
  $mailForm = "/^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/"; 
  $profileForm = "/^[a-zA-Z ]+$/"; 
  $passwordForm = "/^[a-zA-Z0-9]+$/"; 

  # clean user input
  function cleanInput($input) {
    $input = trim($input); 
    $input = stripslashes($input); 
    $input = htmlspecialchars($input); 
    return $input; # return cleaned input
  }

  # validate form fields against regex patterns
  function validateField($fieldName, $fieldValue, $regex, $errMsg) {
    if (empty($fieldValue)) {
      echo "<span class='error'>$fieldName is empty</span>"; 
    } else if (!preg_match($regex, $fieldValue)) {
      echo "<span class='error'>$errMsg</span>"; 
    } else {
      return $fieldValue; 
    }
    return null; # return null for invalid cases
  }

  # check if the email is unique in the database
  function checkUniqueEmail($email) {
    global $conn, $table1; 
    $sql = "SELECT friend_email FROM $table1 WHERE friend_email = ?"; 
    $stmt = mysqli_prepare($conn, $sql); 
    mysqli_stmt_bind_param($stmt, "s", $email); 
    mysqli_stmt_execute($stmt); 
    mysqli_stmt_store_result($stmt); 
    $numRows = mysqli_stmt_num_rows($stmt); 
    mysqli_stmt_close($stmt); 

    if ($numRows > 0) {
      echo "<span class='error'>Email already exists</span>"; # email exists
      return false; 
    }
    return true; 
  }

  # check if passwords match
  function checkMatchPasswords($password, $confirmPassword) {
    if ($password !== $confirmPassword) {
      echo "<span class='error'>Passwords do not match</span>"; 
      return false;
    }
    return true; 
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

    <h1>Registration Page</h1>
    <form method="post" action="signup.php" class="login-form"> 
      <div class="form-group">
        <label for="email">Email</label> <br>
        <input type="text" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" class="form-input">
        <br>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { # check if form is submitted
          $mail = validateField("Email", cleanInput($_POST['email']), $mailForm, "Email is invalid"); # validate email
          $isMailUnique = checkUniqueEmail($mail); # check email uniqueness
        }
        ?>
      </div>

      <div class="form-group">
        <label for="profileName">Profile Name</label>
        <input type="text" name="profileName" value="<?php echo isset($_POST['profileName']) ? htmlspecialchars($_POST['profileName']) : ''; ?>" class="form-input">
        <br>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
          $profileName = validateField("Profile Name", cleanInput($_POST['profileName']), $profileForm, "Profile Name is invalid"); # validate profile name
        }
        ?>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-input">
        <br>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
          $password = validateField("Password", cleanInput($_POST['password']), $passwordForm, "Password is invalid"); # validate password
        }
        ?>
      </div>

      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" name="confirmPassword" class="form-input">
        <br>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
          $confirmPassword = cleanInput($_POST['confirmPassword']); # clean confirm password input
          $arePasswordsMatch = checkMatchPasswords($password, $confirmPassword); # check if passwords match
        }
        ?>
      </div>

      <?php
      if ($mail && $profileName && $password && $isMailUnique && $arePasswordsMatch) { # check if all validations pass
        $sql = "INSERT INTO $table1 (friend_email, password, profile_name, date_started, num_of_friends) VALUES (?, ?, ?, CURDATE(), 0)"; 
        $stmt = mysqli_prepare($conn, $sql); 
        mysqli_stmt_bind_param($stmt, "sss", $mail, $password, $profileName); 
        if (mysqli_stmt_execute($stmt)) { 
          session_start(); 
          $_SESSION['email'] = $mail; 
          $_SESSION['loggedIn'] = true; 
          header("Location: friendadd.php"); # redirect to friend addition page
          exit(); 
        } else {
          echo "<p class='error' style='color:red'>Error creating account: " . mysqli_error($conn) . "</p>"; # display error if account creation fails
        }
        mysqli_stmt_close($stmt); # close the statement
      }
      ?>

      <p>
        <input type="submit" value="Register" class="link-button"> 
        <a href="signup.php" class="clear-button">Clear</a> 
      </p>
    </form>
  </div>
</body>

</html>
