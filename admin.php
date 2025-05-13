<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to the login page if not logged in
    header("Location: admin_login.php");
    exit;
}


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
// Count total events
$totalEvents = $conn->query("SELECT COUNT(*) as total FROM events")->fetch_assoc()['total'];

// Count total bookings
$totalBookings = $conn->query("SELECT COUNT(*) as total FROM registrations")->fetch_assoc()['total'];

// Count new bookings (pending)
$newBookings = $conn->query("SELECT COUNT(*) as total FROM registrations WHERE status IS NULL OR status = 'pending'")->fetch_assoc()['total'];

// Count confirmed
$confirmedBookings = $conn->query("SELECT COUNT(*) as total FROM registrations WHERE status = 'confirmed'")->fetch_assoc()['total'];

// Count rejected
$rejectedBookings = $conn->query("SELECT COUNT(*) as total FROM registrations WHERE status = 'rejected'")->fetch_assoc()['total'];

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_category = $_POST['event_category'];
    $event_description = $_POST['event_description'];
    $event_sponsor = $_POST['event_sponsor'];

    // SQL query to insert data into the EVENTS table
    $sql = "INSERT INTO EVENTS (NAME, EVENT_DATE, CATEGORY, DESCRIPTION, SPONSOR) 
            VALUES ('$event_name', '$event_date', '$event_category', '$event_description', '$event_sponsor')";

    // Check if insertion was successful
    if ($conn->query($sql) === TRUE) {
        echo "New event added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; // Display error if insertion fails
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <title>Admin Dashboard</title>
  <style>
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
        text-align: left;
      }

      .sidebar a:hover {
        background-color: #34495e;
        padding-left: 10px;
      }

      .main {
        flex: 1;
        padding: 20px;
      }

      .main h1 {
        margin-bottom: 20px;
      }

      .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
      }

      .card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s;
      }

      .card:hover {
        transform: translateY(-5px);
      }

      .card i {
        font-size: 30px;
        margin-bottom: 10px;
        color: #3498db;
      }

      .card h2 {
        margin: 10px 0;
        font-size: 24px;
      }

      .card p {
        font-size: 14px;
        color: #555;
        margin-bottom: 15px;
      }

      .card button {
        padding: 8px 15px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        text-align: center;
        display: inline-block;
        margin-top: 10px;
      }

      .card button:hover {
        background-color: #2980b9;
      }

      .dropdown {
        position: relative;
      }

      .dropdown-content {
        display: none;
        flex-direction: column;
        background-color: #34495e;
        margin-left: 10px;
      }

      .dropdown-content a {
        padding: 8px 0;
        font-size: 14px;
      }

      .dropdown:hover .dropdown-content {
        display: flex;
      }

  </style>
</head>
<body>
    <div class="sidebar">
        <h2>Admin Panel</h2>
        <a href="admin.php"><i class="fa-solid fa-house"></i> Dashboard</a>
        <a href="#"><i class="fa-solid fa-list"></i> Category</a>
        <a href="#"><i class="fa-solid fa-handshake"></i> Manage Sponsors</a>
        <div class="dropdown">
            <a href="#"><i class="fa-solid fa-calendar-days"></i> Events <i class="fa-solid fa-caret-down" style="float: right;"></i></a>
            <div class="dropdown-content">
                <a href="create_event.php">Create Event</a>
               
            </div>
        </div>
        <a href="#"><i class="fa-solid fa-users"></i> Manage Users</a>
        <a href="#"><i class="fa-solid fa-envelope"></i> Manage Subscribers</a>
        <a href="#"><i class="fa-solid fa-book-open"></i> Manage Bookings</a>
        <a href="#"><i class="fa-solid fa-newspaper"></i> News</a>
        <a href="#"><i class="fa-solid fa-gear"></i> Website Settings</a>
        <a href="admin_logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h1>Dashboard</h1>

        <div class="cards">
            <div class="card">
                <i class="fa fa-file"></i>
                <h2>4</h2>
                <p>Listed Categories</p>
                <button>View Details</button>
            </div>

            <div class="card">
                <i class="fa fa-database"></i>
                <h2>1</h2>
                <p>Sponsors</p>
                <button>View Details</button>
            </div>

            <div class="card">
                <i class="fa fa-calendar"></i>
                <h2><?= $totalEvents ?></h2>
                <p>Total Events</p>
                <a href="total_event.php">
                    <button>View Details</button>
                </a>
            </div>

            <div class="card">
                <i class="fa fa-users"></i>
                <h2><?= $totalBookings ?></h2>
                <p>Total Registered Users</p>
                <a href="total_reg.php">
                    <button>View Details</button>
                </a>
            </div>

          

            <div class="card">
                <i class="fa fa-book"></i>
                <h2><?= $newBookings ?></h2>
                <p>New Bookings</p>
                <a href="accept.php">
                    <button>View Details</button>
                </a>
            </div>

            <div class="card">
                <i class="fa fa-check"></i>
                <h2><?= $confirmedBookings ?></h2>
                <p>Confirmed Bookings</p>
                <a href="c.php">
                    <button>View Details</button>
                </a>
            </div>

            <div class="card">
                <i class="fa fa-times"></i>
                <h2><?= $rejectedBookings ?></h2>
                <p>Cancelled Bookings</p>
                <a href="reject.php">
                    <button>View Details</button>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
