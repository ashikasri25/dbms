<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['user_name'];
    $email = $_POST['user_email'];
    $password = $_POST['user_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit();
    }

    // ✅ Check if username or email already exists
    $check_sql = "SELECT * FROM users WHERE EMAIL = ? OR USERNAME = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $email, $name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists!'); window.history.back();</script>";
        exit();
    }

    // ✅ Proceed to insert if unique
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $insert_sql = "INSERT INTO users (USERNAME, EMAIL, PASSWORD) VALUES (?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($insert_stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        header('Location: user_login.php');
        exit();
    } else {
        echo "Error: " . $insert_stmt->error;
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
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
  </style>
</head>
<body>
<script>
    function validateForm() {
      const pass = document.getElementById("user_password").value;
      const confirm = document.getElementById("confirm_password").value;

      if (pass !== confirm) {
        alert("Passwords do not match!");
        return false;
      }
      return true;
    }
  </script>



<div class="container">
  <h2>Create an Account</h2>
  <form action="user_signup.php" method="POST" onsubmit="return validateForm()">
    <label for="user_name" class="label">Username</label>
    <input type="text" name="user_name" id="user_name" class="input-field" required>

    <label for="user_email" class="label">Email</label>
    <input type="email" name="user_email" id="user_email" class="input-field" required>

    <label for="user_password" class="label">Password</label>
    <input type="password" name="user_password" id="user_password" class="input-field" required>

    <label for="confirm_password" class="label">Confirm Password</label>
    <input type="password" name="confirm_password" id="confirm_password" class="input-field" required>

    <button type="submit" class="button">Sign Up</button>
  </form>

  <div class="footer">
    <p>Already have an account? <a href="login.php">Login here</a></p>
  </div>
</div>

</body>
</html>

</body>
</html>
