<?php
// love_meter_process.php

// Include the database connection file
require_once 'db.php';

// Function to sanitize input data
function sanitize_input($data, $connection) {
    $data = trim($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    // Using mysqli_real_escape_string for additional protection in SQL queries
    $data = $connection->real_escape_string($data);
    return $data;
}

// Check if the form was submitted via POST method
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize input values
    if (isset($_POST['name1']) && isset($_POST['name2'])) {
        $raw_name1 = $_POST['name1'];
        $raw_name2 = $_POST['name2'];

        $name1 = sanitize_input($raw_name1, $connection);
        $name2 = sanitize_input($raw_name2, $connection);
        
        // Validate that both names are not empty after trimming
        if (empty($name1) || empty($name2)) {
            $error_message = "Both names must be provided.";
        }
    } else {
        $error_message = "Form data is missing.";
    }
} else {
    $error_message = "Invalid request method.";
}

if (isset($error_message)) {
    // Display error message and exit if there was a problem with input
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Love Meter Error</title>
    <link rel='stylesheet' type='text/css' href='love_meter.css'>
</head>
<body>
    <h1>Error</h1>
    <section id='result'>
        <p>" . $error_message . "</p>
        <p><a href='love_meter.html'>Go back to Love Meter</a></p>
    </section>
</body>
</html>";
    exit;
}

// Calculate love percentage using random logic (0-100)
$love_percentage = rand(0, 100);

// Prepare an INSERT statement to store the data securely
$query = "INSERT INTO love_results (name1, name2, love_percentage) VALUES (?, ?, ?)";
$stmt = $connection->prepare($query);

if (!$stmt) {
    // Handle statement preparation error
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Love Meter Error</title>
    <link rel='stylesheet' type='text/css' href='love_meter.css'>
</head>
<body>
    <h1>Error</h1>
    <section id='result'>
        <p>Database error: " . $connection->error . "</p>
        <p><a href='love_meter.html'>Go back to Love Meter</a></p>
    </section>
</body>
</html>";
    exit;
}

// Bind parameters to the prepared statement
$stmt->bind_param("ssi", $name1, $name2, $love_percentage);

// Execute the statement
if (!$stmt->execute()) {
    // Handle execution error
    echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Love Meter Error</title>
    <link rel='stylesheet' type='text/css' href='love_meter.css'>
</head>
<body>
    <h1>Error</h1>
    <section id='result'>
        <p>Failed to save data: " . $stmt->error . "</p>
        <p><a href='love_meter.html'>Go back to Love Meter</a></p>
    </section>
</body>
</html>";
    $stmt->close();
    exit;
}

$stmt->close();
$connection->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Love Meter Result</title>
    <link rel="stylesheet" type="text/css" href="love_meter.css">
</head>
<body>
    <h1>Love Meter Result</h1>
    <section id="result">
        <p>The love percentage between <strong><?php echo $name1; ?></strong> and <strong><?php echo $name2; ?></strong> is:</p>
        <h2><?php echo $love_percentage; ?>%</h2>
        <p><a href="love_meter.html">Try Again</a></p>
    </section>
</body>
</html>
