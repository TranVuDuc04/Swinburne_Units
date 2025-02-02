<!DOCTYPE html>
<html>
<head>
  <title>Archer Information</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <!--Bootstrap-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
     rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
     crossorigin="anonymous">
</head>
<body>
  <nav>
    <a href="archer.php">Archer Info</a>
    <a href="leaderboard.php">Leader Board</a>
    <a href="index.php">Shooting</a>
  </nav>
  <!-- Archer Information Form -->
  <div class="container">
    <h2>Archer Information</h2>
    <form method="post" action="" onsubmit="return validateForm()">
      <div class="mb-3">
        <label for="name">Full name:</label>
        <input type="text" name="name" id="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label for="gender">Gender:</label>
        <select name="gender" id="gender" class="form-select" required>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="age">Age:</label>
        <input type="number" name="age" id="age" class="form-control" required>
      </div>
      <input type="submit" name="add" value="Submit" class="btn btn-primary">
    </form>
  </div>
  <script>
    function validateForm() {
      const name = document.getElementById("name").value;
      const namePattern = /^[A-Za-z ]+$/;
      if (!namePattern.test(name) || name.length < 1 || name.length > 25) {
        alert("Name should only contain letters and spaces, and be between 1 to 25 characters long.");
        return false;
      }
      let age = document.getElementById("age").value;
      if (isNaN(age) || age < 0 || age > 80) {
        alert("Please enter a valid age between 0 and 80.");
        return false;
      }
      return true;
    }
  </script>
</body>
</html>
<?php
include('dbconnect.php');
function sanitizeInput($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}
function validateInput($name, $gender, $age) {
  $namePattern = "/^[A-Za-z ]+$/";
  if (!preg_match($namePattern, $name) || strlen($name) < 1 || strlen($name) > 25) {
    return "Invalid name. Name should only contain letters and spaces, and be between 1 to 25 characters long.";
  }
  if (!in_array($gender, ['Male', 'Female'])) {
    return "Invalid gender.";
  }
  if (!filter_var($age, FILTER_VALIDATE_INT) || $age < 0 || $age > 80) {
    return "Invalid age. Age should be a number between 0 and 80.";
  }
  return true;
}
function addArcherInfo($name, $gender, $age) {
  $conn = getDBConnection();
  $stmt = $conn->prepare("INSERT INTO archer (name, gender, age) VALUES (?, ?, ?)");
  $stmt->bind_param("ssi", $name, $gender, $age);
  if ($stmt->execute()) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $stmt->error;
  }
  $stmt->close();
  $conn->close();
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {
  $name = sanitizeInput($_POST["name"]);
  $gender = sanitizeInput($_POST["gender"]);
  $age = sanitizeInput($_POST["age"]);
  $validationResult = validateInput($name, $gender, $age);
  if ($validationResult === true) {
    addArcherInfo($name, $gender, $age);
  } else {
    echo $validationResult;
  }
}
function displayArcherInfo() {
  $conn = getDBConnection();
  $sql = "SELECT archerID, name, gender, age FROM archer";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    echo "<div class='container mt-4'><table class='table table-striped table-hover'><tr><th>archerID</th><th>name</th><th>gender</th><th>age</th></tr></div>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr><td>".$row["archerID"]."</td><td>".$row["name"]."</td><td>".$row["gender"]."</td><td>".$row["age"]."</td></tr>";
    }
    echo "</table>";
  } else {
    echo "0 results";
  }
  $conn->close();
}
displayArcherInfo();
?>
