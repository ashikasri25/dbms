<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email and password from the POST array
    if (isset($_POST['user_email']) && isset($_POST['user_password']) && !empty($_POST['user_email']) && !empty($_POST['user_password'])) {
        $email = $_POST['user_email'];  // Get the email from the form
        $password = $_POST['user_password'];  // Get the password from the form

        // Query to check if the email exists
        $sql = "SELECT * FROM users WHERE EMAIL = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User found, fetch data
            $user = $result->fetch_assoc();

            // Verify the password (Ensure 'PASSWORD' is the correct column name)
            if (password_verify($password, $user['PASSWORD'])) {  
                // Password is correct, set session and redirect
                $_SESSION['user_id'] = $user['id'];  // Assuming 'id' is the user identifier
                header('Location: index.php');  // Redirect to index.php after successful login
                exit();
            } else {
                $error = "Invalid login credentials.";
            }
        } else {
            $error = "No user found with this email.";
        }
    } else {
        $error = "Please enter both email and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f6fa;
      padding: 50px;
      color: #333;
    }
    .container {
      max-width: 500px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    h2 {
      color: #2c3e50;
      text-align: center;
    }
    .label {
      font-weight: bold;
      margin-top: 10px;
    }
    .input-field {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .button {
      width: 100%;
      padding: 12px;
      background-color: #3498db;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 18px;
      cursor: pointer;
    }
    .button:hover {
      background-color: #2980b9;
    }
    .footer {
      text-align: center;
      margin-top: 20px;
    }
    .error {
      color: red;
      text-align: center;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Login</h2>
  
  <?php if (isset($error)): ?>
    <div class="error"><?= $error ?></div>
  <?php endif; ?>
  
  <form action="user_login.php" method="POST">
    <label for="user_email" class="label">Email</label>
    <input type="email" name="user_email" id="user_email" class="input-field" required>

    <label for="user_password" class="label">Password</label>
    <input type="password" name="user_password" id="user_password" class="input-field" required>

    <button type="submit" class="button">Login</button>
  </form>

  <div class="footer">
    <p>Don't have an account? <a href="user_signup.php">Sign up here</a></p>
  </div>
</div>

</body>
</html>
