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

$success_message = ""; // To hold success message

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $event_name = $_POST['eventname'];
    $event_time = $_POST['eventtime'];
    $event_date = $_POST['eventdate'];
    $event_category = $_POST['category'];
    $event_description = $_POST['description'];
    $event_sponsor = $_POST['sponsor'];
    $event_location = $_POST['location'];

    // Prepared statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO EVENTS (NAME, EVENT_DATE, CATEGORY, DESCRIPTION, SPONSOR, LOCATION, EVENT_TIME) 
                            VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $event_name, $event_date, $event_category, $event_description, $event_sponsor, $event_location, $event_time);

    // Execute the query
    if ($stmt->execute()) {
        $success_message = "New event added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Event - Admin</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
      .sidebar {
          width: 250px;
          background-color: #2c3e50;
          color: white;
          padding: 30px;
          height: 100vh;
          position: fixed;
          top: 0;
          left: 0;
      }

      .sidebar h2 {
          margin-bottom: 40px;
          font-size: 22px;
          text-align: center;
      }

      .sidebar a {
          display: block;
          color: white;
          padding: 12px 0;
          text-decoration: none;
          font-size: 18px;
          transition: background 0.3s;
      }

      .sidebar a:hover {
          background-color: #34495e;
          padding-left: 15px;
      }

      .dropdown {
          position: relative;
      }

      .dropdown-content {
          display: none;
          position: absolute;
          background-color: #34495e;
          min-width: 200px;
          box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
          z-index: 1;
      }

      .dropdown:hover .dropdown-content {
          display: block;
      }

      body {
          background-color: #f4f4f4;
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
          display: flex;
      }

      .container {
          max-width: 800px;
          margin: 50px auto;
          background: white;
          padding: 40px;
          border-radius: 8px;
          box-shadow: 0 0 15px rgba(0,0,0,0.1);
          flex-grow: 1;
      }

      h2 {
          text-align: center;
          margin-bottom: 30px;
      }

      label {
          font-weight: bold;
          display: block;
          margin-bottom: 5px;
      }

      input[type="text"],
      input[type="date"],
      select,
      textarea {
          width: 100%;
          padding: 12px;
          margin-bottom: 20px;
          border-radius: 5px;
          border: 1px solid #ccc;
          box-sizing: border-box;
          font-size: 16px;
      }

      button {
          background-color: #007BFF;
          color: white;
          padding: 12px 20px;
          border: none;
          border-radius: 5px;
          font-size: 16px;
          cursor: pointer;
          width: 100%;
      }

      button:hover {
          background-color: #0056b3;
      }

      @media (max-width: 768px) {
          .sidebar {
              width: 100%;
              height: auto;
          }

          .container {
              margin: 20px;
          }
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
                <a href="#">Create Event</a>

            </div>
        </div>

        <a href="#"><i class="fa-solid fa-users"></i> Manage Users</a>
        <a href="#"><i class="fa-solid fa-envelope"></i> Manage Subscribers</a>
        <a href="#"><i class="fa-solid fa-book-open"></i> Manage Bookings</a>
        <a href="#"><i class="fa-solid fa-newspaper"></i> News</a>
        <a href="#"><i class="fa-solid fa-gear"></i> Website Settings</a>
    </div>

    <div class="container">
        <h2>Add Event</h2>
        <form action="create_event.php" method="POST">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="Sports Party">Sports Party</option>
                <option value="Tech Conference">Tech Conference</option>
                <option value="Cultural Program">Cultural Program</option>
                <option value="Workshop">Workshop</option>
            </select>

            <label for="sponsor">Event Sponsors</label>
            <input type="text" id="sponsor" name="sponsor" required>

            <label for="eventname">Event Name</label>
            <input type="text" id="eventname" name="eventname" required>
            
            <label for="eventtime">Event Time</label>
            <input type="text" id="eventtime" name="eventtime" required>

            <label for="location">Location</label>
            <input type="text" id="location" name="location" required>

            <label for="description">Event Description</label>
            <textarea id="description" name="description" rows="8" placeholder="Enter the details of the event..." required></textarea>

            <label for="eventdate">Event Start Date</label>
            <input type="date" id="eventdate" name="eventdate" required>

            <button type="submit">Add Event</button>
        </form>
    </div>

    <?php if (!empty($success_message)) : ?>
    <script>
        window.onload = function () {
            alert("<?php echo addslashes($success_message); ?>");
            document.querySelector("form").reset();
        };
    </script>
    <?php endif; ?>
</body>
</html>
