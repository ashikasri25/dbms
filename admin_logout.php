<?php
session_start(); // Start the session

// Destroy the session to log out
session_destroy();

// Redirect to login page
header("Location: admin_login.php");
exit();
?>
