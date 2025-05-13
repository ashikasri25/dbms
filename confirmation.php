<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_data";

// Connect to DB
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Use GET parameter or fallback to session
$registration_id = isset($_GET['rid']) ? intval($_GET['rid']) : ($_SESSION['registration_id'] ?? 0);

// Optional: clear the session value after use
// unset($_SESSION['registration_id']);
// Assuming the registration_id is available after booking confirmation
$_SESSION['registration_id'] = $registration_id;  // Store booking ID in session

if ($registration_id === 0) {
    die("Invalid booking ID.");
}

// Fetch booking details
$sql = "SELECT 
          r.registration_id, r.registration_date, IFNULL(r.status, 'Pending') as status,
          e.name AS event_name, e.location, e.event_date,
          a.name AS attendee_name, a.email
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        JOIN attendees a ON r.attendee_id = a.attendee_id
        WHERE r.registration_id = ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $registration_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Booking not found.");
}

$booking = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>s
  <meta charset="UTF-8">
  <title>Booking Confirmation</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f2f6fa;
      padding: 50px;
      color: #333;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    h2 {
      color: #2c3e50;
      text-align: center;
      margin-bottom: 20px;
    }
    .label {
      font-weight: bold;
    }
    .value {
      margin-bottom: 12px;
    }
    .status {
      font-weight: bold;
      color: green;
    }
    .pending {
      color: orange;
    }
    .footer {
      margin-top: 20px;
      text-align: center;
    }
    .footer a {
      color: #3498db;
      text-decoration: none;
    }
    .footer a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Booking Confirmation</h2>
  <div class="value"><span class="label">Booking ID:</span> <?= $booking['registration_id'] ?></div>
  <div class="value"><span class="label">Name:</span> <?= htmlspecialchars($booking['attendee_name']) ?></div>
  <div class="value"><span class="label">Email:</span> <?= htmlspecialchars($booking['email']) ?></div>
  <div class="value"><span class="label">Event:</span> <?= htmlspecialchars($booking['event_name']) ?></div>
  <div class="value"><span class="label">Location:</span> <?= htmlspecialchars($booking['location']) ?></div>
  <div class="value"><span class="label">Event Date:</span> <?= $booking['event_date'] ?></div>
  <div class="value"><span class="label">Registration Date:</span> <?= $booking['registration_date'] ?></div>
  <div class="value"><span class="label">Status:</span> 
    <span class="<?= strtolower($booking['status']) === 'pending' ? 'pending' : 'status' ?>">
      <?= ucfirst($booking['status']) ?>
    </span>
  </div>

  <div class="footer">
    <p><a href="index.php">← Back to Events</a></p>
  </div>
</div>

</body>
</html>
