<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Job Vacancy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="">
            <ul class="navbar-nav">
                <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="postjobform.php" class="nav-link">Post Job</a></li>
                <li class="nav-item"><a href="searchjobform.php" class="nav-link">Search Jobs</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
            </ul>
        </div>
    </nav>
<div class="container">
    <h1>Search Job Vacancy</h1>

    <form action="searchjobprocess.php" method="GET">
        <label for="title">Job Title:</label>
        <input type="text" id="title" name="title"><br><br>

        <label for="position">Position:</label>
        <input type="text" id="position" name="position"><br><br>

        <label for="contract">Contract:</label>
        <input type="text" id="contract" name="contract"><br><br>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location"><br><br>

        <label for="accept_by">Application Type:</label><br>
        <input type="checkbox" id="post" name="accept_by[]" value="Post">
        <label for="post">Post</label><br>
        <input type="checkbox" id="email" name="accept_by[]" value="Email">
        <label for="email">Email</label><br><br>

        <input type="submit" value="Search">
    </form>

    <br>
    <a href="index.php">Return to Home</a>
</div>
</body>
</html>
