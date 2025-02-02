<?php
$dir = "../../data/jobs";
$filename = $dir . "/positions.txt";

//error message
$error_message = '';

//check if the form has been submitted
if ($_GET) {
    $search_title = isset($_GET['title']) ? trim($_GET['title']) : '';
    $search_position = isset($_GET['position']) ? trim($_GET['position']) : '';
    $search_contract = isset($_GET['contract']) ? trim($_GET['contract']) : '';
    $search_location = isset($_GET['location']) ? trim($_GET['location']) : '';
    $search_application_type = isset($_GET['accept_by']) ? $_GET['accept_by'] : [];

    //check if the file exists
    if (!file_exists($filename)) {
        $error_message .= "Job vacancies file not found.<br>";
    }

    if ($error_message) {
        echo "<p>$error_message</p>";
        echo '<a href="searchjobform.php">Return to Search Job Vacancy</a><br>';
        echo '<a href="index.php">Return to Home</a>';
        exit;
    }

    $job_vacancies = [];
    $handle = fopen($filename, "r");
    if ($handle === false) {
        die("<p>Failed to open file for reading.</p>");
    }

    while (($line = fgets($handle)) !== false) {
        $fields = explode("\t", trim($line));
        $match = true;

        //search title
        if ($search_title && stripos($fields[1], $search_title) === false) {
            $match = false;
        }
        //search position
        if ($search_position && stripos($fields[4], $search_position) === false) {
            $match = false;
        }
        //search contract
        if ($search_contract && stripos($fields[5], $search_contract) === false) {
            $match = false;
        }
        //search location
        if ($search_location && stripos($fields[6], $search_location) === false) {
            $match = false;
        }

        //search application type
        if (!empty($search_application_type)) {
            if (in_array('Post', $search_application_type) && stripos($fields[7], 'Yes') === false) {
                $match = false;
            }
            if (in_array('Email', $search_application_type) && stripos($fields[8], 'Yes') === false) {
                $match = false;
            }
        }

        if ($match) {
            $closing_date = $fields[3];
            $date_parts = explode('/', $closing_date);
            if (count($date_parts) == 3) {
                $day = (int)$date_parts[0];
                $month = (int)$date_parts[1];
                $year = (int)$date_parts[2];
                $year += ($year < 100) ? 2000 : 0;

                //check if the date is valid 
                if (checkdate($month, $day, $year)) {
                    $timestamp = mktime(0, 0, 0, $month, $day, $year);
                    if ($timestamp >= strtotime('today')) {
                        $job_vacancies[] = [
                            'fields' => $fields,
                            'timestamp' => $timestamp
                        ];
                    }
                }
            }
        }
    }

    fclose($handle);

    //sort job in descending order (closing date)
    usort($job_vacancies, function($a, $b) {
        return $b['timestamp'] - $a['timestamp'];
    });
    $found = !empty($job_vacancies);

    echo '<h2>Search Results:</h2>';
    echo '<table border="1" cellpadding="5">';
    echo '<tr>
            <th>Position ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Closing Date</th>
            <th>Position</th>
            <th>Contract</th>
            <th>Location</th>
            <th>Accept Application By</th>
          </tr>';

    foreach ($job_vacancies as $job) {
        $fields = $job['fields'];
        echo '<tr>';
        for ($i = 0; $i < 7; $i++) {
            echo '<td>' . htmlspecialchars($fields[$i]) . '</td>';
        }

        //post, email or both
        $accept_method = '';
        if ($fields[7] === 'Yes') {
            $accept_method .= 'Post';
        }
        if ($fields[8] === 'Yes') {
            if (!empty($accept_method)) {
                $accept_method .= ', ';
            }
            $accept_method .= 'Email';
        }
        echo '<td>' . htmlspecialchars($accept_method) . '</td>';

        echo '</tr>';
    }

    if (!$found) {
        echo '<tr><td colspan="8">No job vacancies found matching the specified criteria.</td></tr>';
    }

    echo '</table>';
}
?>

<br>
<a href="searchjobform.php">Return to Search Job Vacancy</a><br>
<a href="index.php">Return to Home</a>
