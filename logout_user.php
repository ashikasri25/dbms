<?php
// Start the session
session_start();

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Logging Out</title>
    <script>
        alert("Logged out!");
        window.location.href = "index.php"; // or "user_signup.php"
    </script>
</head>
<body>
</body>
</html>
