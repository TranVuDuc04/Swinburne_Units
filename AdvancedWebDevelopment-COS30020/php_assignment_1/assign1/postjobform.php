<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job Vacancy</title>
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
    <h1>Post Job Vacancy</h1>
    <form action="postjobprocess.php" method="POST">

        <div class="form-group">
            <label for="position_id">Position ID: </label>
            <input type="text" id="position_id" name="position_id">
        </div>

        <div class="form-group">
            <label for="title">Title: </label>
            <input type="text" id="title" name="title">
        </div>

        <div class="form-group">
            <label for="description">Description: </label>
            <textarea id="description" name="description"></textarea>
        </div>

        <div class="form-group">
            <label for="closing_date">Closing Date: </label>
            <input type="text" id="closing_date" name="closing_date" value="<?php echo date('d/m/y'); ?>">
        </div>

        <div class="form-group">
    <label>Position: </label>
    <div class="radio-group">
        <input type="radio" id="full_time" name="position" value="full time">
        <label for="full_time">Full Time</label>
        <input type="radio" id="part_time" name="position" value="part time">
        <label for="part_time">Part Time</label>
    </div>
</div>

<div class="form-group">
    <label>Contract: </label>
    <div class="radio-group">
        <input type="radio" id="ongoing" name="contract" value="on-going">
        <label for="ongoing">On-going</label>
        <input type="radio" id="fixed_term" name="contract" value="fixed term">
        <label for="fixed_term">Fixed term</label>
    </div>
</div>

<div class="form-group">
    <label>Location</label>
    <div class="radio-group">
        <input type="radio" id="on_site" name="location" value="on site">
        <label for="on_site">On site</label>
        <input type="radio" id="remote" name="location" value="remote">
        <label for="remote">Remote</label>
    </div>
</div>

<div class="form-group">
    <label>Accept Application by</label>
    <div class="checkbox-group">
        <input type="checkbox" id="post" name="accept_by[]" value="Post">
        <label for="post">Post</label>
        <input type="checkbox" id="email" name="accept_by[]" value="Email">
        <label for="email">Email</label>
    </div>
</div>


        <input type="submit" value="Submit">

    </form>

    <br>
    <a href="index.php">Return to Home</a>
</div>
</body>
</html>
