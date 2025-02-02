<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New VIP Member</title>
</head>
<body>
    <h1>Add New VIP Member</h1>
    <form action="member_add.php" method="post">
        <label for="fname">First Name:</label>
        <input type="text" name="fname" required><br>

        <label for="lname">Last Name:</label>
        <input type="text" name="lname" required><br>

        <label for="gender">Gender (M/F):</label>
        <input type="text" name="gender" maxlength="1" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone" required><br>

        <input type="submit" value="Add Member">
    </form>
</body>
</html>
