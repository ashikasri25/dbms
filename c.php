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

// Fetch confirmed registrations
$sql = "SELECT r.registration_id, r.registration_date, e.name AS event_name, a.name AS attendee_name, r.status 
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        JOIN attendees a ON r.attendee_id = a.attendee_id
        WHERE r.status IN ('confirmed', 'pending')";

$result = $conn->query($sql); // Execute the query and store the result

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Confirmed Bookings</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    body { background-color: #f4f7fc; color: #333; display: flex; min-height: 100vh; }

    .sidebar {
      width: 250px;
      background-color: #2c3e50;
      color: white;
      padding: 20px;
    }

    .sidebar h2 {
      margin-bottom: 30px;
      text-align: center;
      font-size: 24px;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 12px;
      font-size: 16px;
      text-decoration: none;
      border-radius: 5px;
      margin: 8px 0;
      transition: all 0.3s;
    }

    .sidebar a:hover {
      background-color: #34495e;
      padding-left: 15px;
    }

    .main {
      flex: 1;
      padding: 40px;
      background-color: #ffffff;
      overflow-y: auto;
    }

    .main h1 {
      margin-bottom: 30px;
      font-size: 28px;
      color: #2c3e50;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
    }

    th {
      background-color: #28a745;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .status {
      font-weight: bold;
      color: green;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Admin Panel</h2>
  <a href="#"><i class="fa-solid fa-house"></i> Dashboard</a>
  <a href="#"><i class="fa-solid fa-list"></i> Category</a>
  <a href="#"><i class="fa-solid fa-handshake"></i> Manage Sponsors</a>

  <div class="dropdown">
    <a href="#"><i class="fa-solid fa-calendar-days"></i> Events <i class="fa-solid fa-caret-down" style="float: right;"></i></a>
    <div class="dropdown-content">
      <a href="create_event.php">Create Event</a>
      <a href="#">Manage Events</a>
    </div>
  </div>

  <a href="#"><i class="fa-solid fa-users"></i> Manage Users</a>
  <a href="#"><i class="fa-solid fa-envelope"></i> Manage Subscribers</a>
  <a href="#"><i class="fa-solid fa-book-open"></i> Manage Bookings</a>
  
</div>

<!-- Main content -->
<div class="main">
  <h1>Confirmed Bookings</h1>
  <table>
    <thead>
      <tr>
        <th>Booking ID</th>
        <th>Attendee Name</th>
        <th>Event Name</th>
        <th>Registration Date</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
          while($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row['registration_id'] . "</td>";
              echo "<td>" . htmlspecialchars($row['attendee_name']) . "</td>";
              echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
              echo "<td>" . $row['registration_date'] . "</td>";
              echo "<td class='status'>" . ucfirst($row['status']) . "</td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No confirmed bookings found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>

<?php $conn->close(); ?> 
