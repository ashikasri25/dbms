<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "event_db"; // replace with your actual DB name

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 echo  "Connected Successfully";
?>
