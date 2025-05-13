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

// Get attendee ID from session
$attendee_id = $_SESSION['attendee_id'] ?? 0;
if ($attendee_id == 0) {
    die("No attendee information found. Please register first.");
}

// Fetch all bookings for this attendee
$sql = "SELECT 
          r.registration_id, r.registration_date, IFNULL(r.status, 'Pending') as status,
          e.name AS event_name, e.location, e.event_date
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        WHERE r.attendee_id = ?
        ORDER BY r.registration_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $attendee_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Bookings</title>
  <style>
    body { font-family: Arial; background: #f9f9f9; padding: 20px; }
    table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
    th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
    th { background-color: #3498db; color: white; }
    .pending { color: orange; font-weight: bold; }
    .confirmed { color: green; font-weight: bold; }
  </style>
</head>
<body>

<h2>My Bookings</h2>
<table>
  <tr>
    <th>Booking ID</th>
    <th>Event</th>
    <th>Location</th>
    <th>Event Date</th>
    <th>Registration Date</th>
    <th>Status</th>
  </tr>
  <?php while ($row = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $row['registration_id'] ?></td>
    <td><?= htmlspecialchars($row['event_name']) ?></td>
    <td><?= htmlspecialchars($row['location']) ?></td>
    <td><?= $row['event_date'] ?></td>
    <td><?= $row['registration_date'] ?></td>
    <td class="<?= strtolower($row['status']) === 'pending' ? 'pending' : 'confirmed' ?>">
      <?= ucfirst($row['status']) ?>
    </td>
  </tr>
  <?php endwhile; ?>
</table>

</body>
</html>
