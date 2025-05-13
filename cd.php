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
$attendee_id = isset($_SESSION['attendee_id']) ? $_SESSION['attendee_id'] : 0;

// Ensure that attendee ID is available
if ($attendee_id === 0) {
    die("Attendee ID is missing or invalid.");
}

// Fetch all previous bookings for the attendee
$sql = "SELECT 
          r.registration_id, r.registration_date, IFNULL(r.status, 'Pending') as status,
          e.name AS event_name, e.location, e.event_date,
          a.name AS attendee_name, a.email
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        JOIN attendees a ON r.attendee_id = a.attendee_id
        WHERE r.attendee_id = ? ORDER BY r.registration_date DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $attendee_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user has any bookings
if ($result->num_rows === 0) {
    echo "You have no previous bookings.";
} else {
    // Display all previous bookings
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
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

  <?php if (isset($bookings)): ?>
    <?php foreach ($bookings as $booking): ?>
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
      <hr>
    <?php endforeach; ?>
  <?php endif; ?>

  <div class="footer">
    <p><a href="index.php">← Back to Events</a></p>
  </div>
</div>

</body>
</html>
