<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="Web application development" />
    <meta name="keywords" content="PHP" />
    <meta name="author" content="Your Name" />
    <title>Guest Book</title>
</head>
<body>
    <h1>Guest Book Entries</h1>
    <?php
    $filename = "../../data/lab05/guestbook.txt";

    if (is_readable($filename)) {
        $content = file_get_contents($filename);
        $content = stripslashes($content);
        $processedContent = strtolower(preg_replace("/[^A-Za-z0-9\n ]/", '', $content));

        echo "<pre>";
        echo $processedContent;
        echo "</pre>";
    } else {
        echo "<p>The Guest book is currently unavailable.</p>";
    }
    ?>
</body>
</html>
