<?php
$host = 'localhost';
$db = 'onsalenow_db';
$user = 'onsale_w';
$pass = 'KJHGUIe8g8JHGll8341vz';

$logDir = '/var/www/onsalenow/application/logs';
$logFile = $logDir . '/logfile.log';

// Create the logs directory if it doesn't exist
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

date_default_timezone_set('Asia/Ho_Chi_Minh'); // Set the timezone
$logMessage = "[" . date("Y-m-d H:i:s") . "] ";
$outputMessage = ""; // To store messages for browser output

try {
    $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_TIMEOUT            => 20, // Set timeout to 20 seconds

    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
    $logMessage .= "Connection successful!\n";
    $outputMessage .= "Connection successful!<br>";

    // Execute a test query
    $stmt = $pdo->query("SELECT * FROM ci_products LIMIT 10");
    if ($stmt) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the first 10 rows of data
        if ($rows) {
            $logMessage .= "Query executed successfully and data fetched!\n";
            $outputMessage .= "Query executed successfully and data fetched!<br>";
            // Optionally, process or display the fetched rows here
        } else {
            $logMessage .= "Query executed successfully but no data found.\n";
            $outputMessage .= "Query executed successfully but no data found.<br>";
        }
    } else {
        $logMessage .= "Query failed to execute.\n";
        $outputMessage .= "Query failed to execute.<br>";
    }
} catch (PDOException $e) {
    $logMessage .= "Connection failed: " . $e->getMessage() . "\n";
    $outputMessage .= "Connection failed: " . $e->getMessage() . "<br>";
}

// Write the log message to the file
file_put_contents($logFile, $logMessage, FILE_APPEND);

// Display the output message in the browser
echo $outputMessage;
?>
