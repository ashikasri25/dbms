<?php
session_start(); // Start session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_data";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You need to log in to register for an event.");
}

// Store event_id from GET in session and redirect (to clean URL)
if (isset($_GET['id'])) {
    $_SESSION['event_id'] = intval($_GET['id']);
    header("Location: register.php"); // Removes ?id=... from URL
    exit();
}

// Get event_id from session
$event_id = $_SESSION['event_id'] ?? null;
if (!$event_id) {
    die("Event ID is missing.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));

    // Insert attendee into DB
    $stmt1 = $conn->prepare("INSERT INTO attendees (name, email, phone) VALUES (?, ?, ?)");
    $stmt1->bind_param("sss", $name, $email, $phone);

    if ($stmt1->execute()) {
        $attendee_id = $conn->insert_id; // Get ID after insert

        // ✅ Store attendee ID in session (used later to fetch all bookings)
        $_SESSION['attendee_id'] = $attendee_id;

        $reg_date = date('Y-m-d');

        // Insert registration
        $stmt2 = $conn->prepare("INSERT INTO registrations (event_id, attendee_id, registration_date) VALUES (?, ?, ?)");
        $stmt2->bind_param("iis", $event_id, $attendee_id, $reg_date);

        if ($stmt2->execute()) {
            $reg_id = $conn->insert_id; // Get registration ID
            header("Location: confirmation.php?rid=$reg_id");
            exit();
        } else {
            echo "Registration error: " . $conn->error;
        }
    } else {
        echo "Attendee error: " . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register for Music Fiesta 2025</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    /* Reset and Body */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f7f9fc;
      color: #333;
      display: flex;
      flex-direction: column;
      height: 100vh;
      justify-content: center;
    }

    .register-container {
      background: #ffffff;
      text-align: center;
      padding: 40px 30px;
      max-width: 500px;
      margin: auto;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }

    .register-container h1 {
      color: #2c3e50;
      font-size: 32px;
      margin-bottom: 30px;
    }

    .form-input {
      width: 100%;
      padding: 12px 20px;
      margin: 12px 0;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 16px;
    }

    .form-input:focus {
      border-color: #3498db;
      outline: none;
    }

    .submit-btn {
      padding: 12px 20px;
      background-color: #3498db;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 18px;
      width: 100%;
      transition: background-color 0.3s ease;
    }

    .submit-btn:hover {
      background-color: #2980b9;
    }

    .back-link {
      margin-top: 20px;
      text-decoration: none;
      color: #3498db;
      font-weight: bold;
    }

    .back-link:hover {
      text-decoration: underline;
    }

  </style>
</head>
<body>

<div class="register-container">
  <h1>Register Now</h1>
  
  <form method="POST">


    <!-- Name Input -->
    <input type="text" name="name" class="form-input" placeholder="Full Name" required>

    <!-- Email Input -->
    <input type="email" name="email" class="form-input" placeholder="Email Address" required>

    <!-- Phone Number Input -->
    <input type="text" name="phone" class="form-input" placeholder="Phone Number" required>
    
    <!-- Hidden Input for Event ID -->
    <input type="hidden" name="event_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">

    <!-- Submit Button -->
    <button type="submit" class="submit-btn">Register Now</button>
  </form>

  <a href="index.html" class="back-link">← Back to Event Details</a>
</div>

</body>
</html>
