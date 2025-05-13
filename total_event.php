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

// Handle delete event request
if (isset($_POST['delete_event_id'])) {
    $event_id = $_POST['delete_event_id'];
    
    // Delete the event from the database
    $sql = "DELETE FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Event deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting event.');</script>";
    }
}

// Fetch all events
$sql = "SELECT event_id, name, event_date, category, sponsor FROM events ORDER BY event_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - All Events</title>
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
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      border-radius: 8px;
      overflow: hidden;
    }

    th, td {
      padding: 16px 20px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #2c3e50;
      color: white;
      text-transform: uppercase;
      font-size: 14px;
      letter-spacing: 0.5px;
    }

    tr:hover {
      background-color: #f1f1f1;
    }

    .no-data {
      padding: 20px;
      background: #fff;
      border-radius: 8px;
      text-align: center;
      color: #888;
    }

    .icon {
      color: #3498db;
      margin-right: 8px;
    }

    .delete-button {
      background-color: #e74c3c;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .delete-button:hover {
      background-color: #c0392b;
    }
  </style>
</head>
<body>

  <h1><i class="fa fa-calendar-days icon"></i>All Events</h1>

  <?php if ($result->num_rows > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Date</th>
          <th>Category</th>
          <th>Sponsor</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['event_id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= $row['event_date'] ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['sponsor']) ?></td>
            <td>
              <form method="POST" action="">
                <input type="hidden" name="delete_event_id" value="<?= $row['event_id'] ?>">
                <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this event?');">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  <?php else: ?>
    <div class="no-data">No events found.</div>
  <?php endif; ?>

</body>
</html>

<?php $conn->close(); ?>
