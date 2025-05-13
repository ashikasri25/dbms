<?php
include 'connect.php';

// Join EVENTS and REGISTRATIONS to count attendees
$sql = "SELECT E.EVENT_ID, E.NAME, E.EVENT_DATE, E.LOCATION, COUNT(R.REGISTRATION_ID) AS total_attendees
        FROM EVENTS E
        LEFT JOIN REGISTRATIONS R ON E.EVENT_ID = R.EVENT_ID
        GROUP BY E.EVENT_ID";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Event Report</title>
</head>
<body>
  <h2>Event Report</h2>

  <table border="1" cellpadding="10">
    <tr>
      <th>Event Name</th>
      <th>Date</th>
      <th>Location</th>
      <th>Total Attendees</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
      <tr>
        <td><?= $row['NAME'] ?></td>
        <td><?= $row['EVENT_DATE'] ?></td>
        <td><?= $row['LOCATION'] ?></td>
        <td><?= $row['total_attendees'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>

</body>
</html>
