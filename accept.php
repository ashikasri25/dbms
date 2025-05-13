<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle accept/reject action
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $registration_id = $_POST['registration_id'];
    $action = $_POST['action']; // 'accept' or 'reject'
    
    if ($action == 'accept') {
        $status = 'confirmed';
    } elseif ($action == 'reject') {
        $status = 'rejected';
    }
    
    // Update booking status
    $sql = "UPDATE registrations SET status = ? WHERE registration_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $registration_id);
    $stmt->execute();

    // Redirect to refresh the page and show updated status
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Fetch all pending registrations
$sql = "SELECT r.registration_id, r.registration_date, e.name AS event_name, a.name AS attendee_name, r.status 
        FROM registrations r
        JOIN events e ON r.event_id = e.event_id
        JOIN attendees a ON r.attendee_id = a.attendee_id
        WHERE r.status = 'pending'";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Admin Dashboard - Manage Bookings</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      background-color: #f4f7fc;
      color: #333;
      display: flex;
      height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      background-color: #2c3e50;
      color: white;
      padding: 20px;
      box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
    }

    .sidebar h2 {
      margin-bottom: 30px;
      text-align: center;
      font-size: 24px;
      font-weight: 600;
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

    .sidebar .dropdown-content a {
      padding-left: 25px;
    }

    /* Main Content */
    .main {
      flex: 1;
      padding: 40px;
      background-color: #ffffff;
      overflow-y: auto;
      box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .main h1 {
      margin-bottom: 30px;
      font-size: 28px;
      font-weight: 700;
      color: #34495e;
    }

    .table-container {
      width: 100%;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table, th, td {
      border: 1px solid #ddd;
    }

    th, td {
      padding: 12px 15px;
      text-align: left;
    }

    th {
      background-color: #2c3e50;
      color: white;
      font-weight: 600;
    }

    td {
      background-color: #f9f9f9;
    }

    tr:nth-child(even) {
      background-color: #f2f2f2;
    }

    tr:hover {
      background-color: #e1e8f0;
    }

    .action-buttons {
      display: flex;
      gap: 10px;
    }

    .action-buttons button {
      padding: 10px 20px;
      font-size: 14px;
      cursor: pointer;
      border-radius: 5px;
      transition: all 0.3s ease;
      border: none;
    }

    .accept-btn {
      background-color: #28a745;
      color: white;
    }

    .reject-btn {
      background-color: #dc3545;
      color: white;
    }

    .accept-btn:hover {
      background-color: #218838;
    }

    .reject-btn:hover {
      background-color: #c82333;
    }

    .status {
      font-weight: bold;
      color: #2c3e50;
    }
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
      display: flex;
      min-height: 100vh;
      background-color: #f2f2f2;
    }

    /* Sidebar */
    .sidebar {
      width: 220px;
      background-color: #2c3e50;
      color: white;
      padding: 20px;
    }

    .sidebar h2 {
      margin-bottom: 30px;
      font-size: 20px;
      text-align: center;
    }

    .sidebar a {
      display: block;
      color: white;
      padding: 10px 0;
      text-decoration: none;
      font-size: 16px;
      transition: background 0.3s;
    }

    .sidebar a:hover {
      background-color: #34495e;
      padding-left: 10px;
    }
  </style>
</head>
<body>
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
    <a href="#"><i class="fa-solid fa-newspaper"></i> News</a>
    <a href="#"><i class="fa-solid fa-gear"></i> Website Settings</a>
  </div>

  <div class="main">
    <h1>Manage Pending Bookings</h1>
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>Booking ID</th>
            <th>Attendee Name</th>
            <th>Event Name</th>
            <th>Registration Date</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if ($result->num_rows > 0) {
              while($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . $row['registration_id'] . "</td>";
                  echo "<td>" . $row['attendee_name'] . "</td>";
                  echo "<td>" . $row['event_name'] . "</td>";
                  echo "<td>" . $row['registration_date'] . "</td>";
                  echo "<td class='status'>" . ucfirst($row['status']) . "</td>";
                  echo "<td>
                        <div class='action-buttons'>
                          <form method='POST'>
                            <input type='hidden' name='registration_id' value='" . $row['registration_id'] . "'>
                            <button type='submit' name='action' value='accept' class='accept-btn'>Accept</button>
                            <button type='submit' name='action' value='reject' class='reject-btn'>Reject</button>
                          </form>
                        </div>
                        </td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='6'>No pending bookings.</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>
</html>

<?php
// Close the connection
$conn->close();
?>
