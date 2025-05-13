<?php
// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all registrations
$sql = "SELECT 
          r.registration_id, r.registration_date, 
          IFNULL(r.status, 'Pending') as status,
          e.name AS event_name, 
          a.name AS attendee_name 
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        JOIN attendees a ON r.attendee_id = a.attendee_id
        ORDER BY r.registration_date DESC";
$result = $conn->query($sql);

// Get total count
$count = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - All Registrations</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 30px;
      background-color: #f4f6f9;
    }

    h1 {
      color: #2c3e50;
      margin-bottom: 10px;
    }

    .badge {
      background-color: #3498db;
      color: white;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 14px;
      display: inline-block;
      margin-left: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      margin-top: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 14px 20px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #2c3e50;
      color: white;
      text-transform: uppercase;
      font-size: 14px;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .status {
      font-weight: bold;
      text-transform: capitalize;
    }
    .pending { color: orange; }
    .confirmed { color: green; }
    .rejected { color: red; }

    .no-data {
      margin-top: 30px;
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      text-align: center;
      color: #888;
    }
  </style>
</head>
<body>

  <h1><i class="fa fa-book icon"></i> All Registrations <span class="badge"><?= $count ?></span></h1>

  <?php if ($count > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Booking ID</th>
          <th>Attendee Name</th>
          <th>Event Name</th>
          <th>Status</th>
          <th>Registration Date</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['registration_id'] ?></td>
            <td><?= htmlspecialchars($row['attendee_name']) ?></td>
            <td><?= htmlspecialchars($row['event_name']) ?></td>
            <td class="status <?= strtolower($row['status']) ?>"><?= $row['status'] ?></td>
            <td><?= $row['registration_date'] ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="no-data">No registrations found.</div>
  <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
