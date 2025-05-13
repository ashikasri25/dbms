<?php
$host = "localhost"; // Your host (usually 'localhost')
$dbname = "event_data"; // Database name
$username = "root"; // MySQL username (use your own)
$password = ""; // MySQL password (leave blank for XAMPP or use your password)

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Display error if the connection fails
}
echo "successfull";
?>
