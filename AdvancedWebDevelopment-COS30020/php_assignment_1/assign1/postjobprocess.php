<?php
$dir = "../../data/jobs";
$filename = $dir . "/positions.txt";

if (!is_dir($dir)) {
    if (!mkdir($dir, 02770, true)) {
        die("<p>Failed to create directory. Please check permissions.</p>");
    }
}

//error message
$error_message = '';

//validate position ID
$position_id = isset($_POST['position_id']) ? trim($_POST['position_id']) : '';
if (strlen($position_id) !== 5 || !preg_match('/^ID\d{3}$/', $position_id)) {
    $error_message .= "Invalid Position ID. Must start with 'ID' followed by 3 digits and be exactly 5 characters long.<br>";
}

//validate title
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
if (strlen($title) > 10 || !preg_match('/^[\w\s,.!]*$/', $title)) {
    $error_message .= "Invalid Title. Must be up to 10 alphanumeric characters including spaces, comma, period, and exclamation point.<br>";
}
if (strlen($title) == 0) {
    $error_message .= "Title is mandatory.<br>";
}

//validate description
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
if (strlen($description) > 250) {
    $error_message .= "Description exceeds 250 characters.<br>";
}
if (strlen($description) == 0) {
    $error_message .= "Description is mandatory.<br>";
}

$closing_date = isset($_POST['closing_date']) ? trim($_POST['closing_date']) : '';
//validate the date format
if (preg_match('/^(\d{1,2})\/(\d{1,2})\/\d{2}$/', $closing_date, $matches)) {
    $day = (int)$matches[1];
    $month = (int)$matches[2];

    //check if the day and month are valid in real life (for example 30/02/24 is not valid)
    if ($month < 1 || $month > 12 || $day < 1 || $day > 31) {
        $error_message .= "Invalid day or month.<br>";
    } elseif (($month == 4 || $month == 6 || $month == 9 || $month == 11) && $day > 30) {
        $error_message .= "Month has only 30 days.<br>";
    } elseif ($month == 2 && $day > 29) {
        $error_message .= "February has only 29 days.<br>";
    } elseif ($month == 2 && $day > 28) {
        $error_message .= "February has only 28 days in a non-leap year.<br>";
    }
} else {
    $error_message .= "Invalid date format. Must be in dd/mm/yy format.<br>";
}

//validate position
$position = isset($_POST['position']) ? trim($_POST['position']) : '';
if (!in_array($position, ['full time', 'part time'])) {
    $error_message .= "Invalid Position. Must be either 'Full Time' or 'Part Time'.<br>";
}

//validate contract
$contract = isset($_POST['contract']) ? trim($_POST['contract']) : '';
if (!in_array($contract, ['fixed term', 'on-going'])) {
    $error_message .= "Invalid Contract. Must be either 'Fixed Term' or 'On-going'.<br>";
}

//validate location
$location = isset($_POST['location']) ? trim($_POST['location']) : '';
if (!in_array($location, ['on site', 'remote'])) {
    $error_message .= "Invalid Location. Must be either 'On site' or 'Remote'.<br>";
}

//validate application type
$accept_by = isset($_POST['accept_by']) ? $_POST['accept_by'] : [];

if (empty($accept_by)) {
    $error_message .= "At least one option must be selected for 'Accept Application by'.<br>";
}

//check if position ID is unique
if (file_exists($filename)) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES);
    foreach ($lines as $line) {
        $fields = explode("\t", $line);
        if ($fields[0] === $position_id) {
            $error_message .= "Position ID already exists.<br>";
            break;
        }
    }
}

if ($error_message) {
    echo "<p>$error_message</p>";
    echo '<a href="index.php">Return to Home</a><br>';
    echo '<a href="postjobform.php">Post Job Vacancy</a>';
    exit;
}

$accept_by_post = in_array('Post', $accept_by) ? 'Yes' : 'No';
$accept_by_email = in_array('Email', $accept_by) ? 'Yes' : 'No';

$record = implode("\t", [
    $position_id,
    $title,
    $description,
    $closing_date,
    $position,
    $contract,
    $location,
    $accept_by_post,
    $accept_by_email
]) . "\n";

//open, write to the file and close
$handle = fopen($filename, "a");
if ($handle === false) {
    die("<p>Failed to open file for writing.</p>");
}
if (fwrite($handle, $record) === false) {
    echo "<p>Cannot save job vacancy.</p>";
} else {
    echo "<p>Job vacancy successfully saved.</p>";
}
fclose($handle);

echo '<a href="searchjobform.php">Search Job Vacancy here</a><br>';
echo '<a href="index.php">Return to Home</a>';
?>
