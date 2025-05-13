<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepared statement to insert new admin into the database
    $stmt = $conn->prepare("INSERT INTO admins (USERNAME, PASSWORD) VALUES (?, ?)");
    if ($stmt === false) {
        die('Error in SQL preparation: ' . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("ss", $username, $hashed_password); // 'ss' for string

    if ($stmt->execute()) {
        // Redirect to the login page after successful creation
        header("Location: admin_login.php");
        exit();
    } else {
        echo "Error creating admin: " . $stmt->error;
    }


    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-size: 14px;
            color: #555;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .message {
            text-align: center;
            font-size: 14px;
            color: red;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create New Admin</h2>
        <form action="admin_create.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" required><br>
            
            <label for="password">Password:</label>
            <input type="password" name="password" required><br>
            
            <input type="submit" value="Create Admin">
        </form>
    </div>
</body>
</html>
