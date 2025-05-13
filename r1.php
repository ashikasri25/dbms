<?php
session_start();  // Start the session

if (!isset($_SESSION['user_id'])) {
  // Redirect to signup page with a popup message
  echo "<script type='text/javascript'>
          alert('Please log in first');
          window.location.href = 'user_signup.php';
        </script>";
  exit();
}// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_data"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get event ID from URL
$event_id = isset($_GET['id']) ? $_GET['id'] : null;

if ($event_id) {
    $sql = "SELECT * FROM EVENTS WHERE EVENT_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    } else {
        echo "Event not found!";
        exit;
    }
} else {
    echo "Invalid event ID!";
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Details | EventEase</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --accent: #4895ef;
      --dark: #1b263b;
      --light: #f8f9fa;
      --text: #2b2d42;
      --text-light: #8d99ae;
      --success: #4cc9f0;
      --warning: #f72585;
      --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      --transition: all 0.3s ease;
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Poppins', sans-serif;
      color: var(--text);
      background-color: var(--light);
      line-height: 1.6;
    }

    /* Header */
    header {
      background-color: white;
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem 2rem;
      max-width: 1200px;
      margin: 0 auto;
    }

    .logo {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary);
      text-decoration: none;
    }

    .logo span {
      color: var(--warning);
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.5rem 1rem;
      background-color: var(--primary);
      color: white;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 500;
      transition: var(--transition);
    }

    .back-btn:hover {
      background-color: var(--secondary);
      transform: translateY(-2px);
    }

    /* Hero Section */
    .hero {
      height: 60vh;
      min-height: 400px;
      background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1505373877841-8d25f7d46678?ixlib=rb-1.2.1&auto=format&fit=crop&w=1200&q=80') center/cover no-repeat;
      display: flex;
      align-items: flex-end;
      padding: 3rem 2rem;
      color: white;
    }

    .hero-content {
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
    }

    .hero h1 {
      font-size: 3rem;
      margin-bottom: 1rem;
      line-height: 1.2;
    }

    .hero-meta {
      display: flex;
      gap: 2rem;
      margin-top: 1.5rem;
      flex-wrap: wrap;
    }

    .hero-meta-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.1rem;
    }

    /* Main Content */
    .container {
      max-width: 1200px;
      margin: 3rem auto;
      padding: 0 2rem;
    }

    /* Event Details */
    .event-details {
      display: grid;
      grid-template-columns: 1fr 300px;
      gap: 3rem;
      margin-bottom: 4rem;
    }

    @media (max-width: 768px) {
      .event-details {
        grid-template-columns: 1fr;
      }
    }

    .event-content h2 {
      font-size: 2rem;
      margin-bottom: 1.5rem;
      color: var(--dark);
    }

    .event-description {
      margin-bottom: 2rem;
      color: var(--text);
      line-height: 1.8;
    }

    .event-highlights {
      margin: 2rem 0;
    }

    .highlight-item {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }

    .highlight-icon {
      color: var(--accent);
      font-size: 1.5rem;
    }

    .highlight-content h3 {
      font-size: 1.2rem;
      margin-bottom: 0.5rem;
    }

    .highlight-content p {
      color: var(--text-light);
    }

    /* Event Sidebar */
    .event-sidebar {
      background: white;
      border-radius: 10px;
      padding: 2rem;
      box-shadow: var(--shadow);
      height: fit-content;
      position: sticky;
      top: 100px;
    }

    .event-info {
      margin-bottom: 2rem;
    }

    .info-item {
      display: flex;
      justify-content: space-between;
      padding: 1rem 0;
      border-bottom: 1px solid #eee;
    }

    .info-item:last-child {
      border-bottom: none;
    }

    .info-label {
      font-weight: 500;
      color: var(--text-light);
    }

    .info-value {
      font-weight: 600;
      text-align: right;
    }

    .register-btn {
      width: 100%;
      padding: 1rem;
      background-color: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .register-btn:hover {
      background-color: var(--secondary);
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(67, 97, 238, 0.2);
    }

    /* Gallery */
    .gallery-section {
      margin: 4rem 0;
    }

    .section-title {
      font-size: 2rem;
      margin-bottom: 2rem;
      text-align: center;
      color: var(--dark);
    }

    
    .gallery {
    padding: 50px 0;
    background-color: #f9f9f9;
}

.gallery h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 30px;
    color: #333;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.gallery-grid img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s;
}

.gallery-grid img:hover {
    transform: scale(1.03);
}


    /* Footer */
    footer {
      background-color: var(--dark);
      color: white;
      padding: 4rem 2rem 2rem;
    }

    .footer-container {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 2rem;
    }

    .footer-logo {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1rem;
      color: white;
    }

    .footer-about p {
      color: #ccc;
      margin-bottom: 1.5rem;
    }

    .social-links {
      display: flex;
      gap: 1rem;
    }

    .social-link {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background-color: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      color: white;
      transition: var(--transition);
    }

    .social-link:hover {
      background-color: var(--primary);
      transform: translateY(-3px);
    }

    .footer-links h3 {
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
      position: relative;
      padding-bottom: 0.5rem;
    }

    .footer-links h3::after {
      content: '';
      position: absolute;
      left: 0;
      bottom: 0;
      width: 40px;
      height: 2px;
      background-color: var(--primary);
    }

    .footer-links ul {
      list-style: none;
    }

    .footer-links li {
      margin-bottom: 0.8rem;
    }

    .footer-links a {
      color: #ccc;
      text-decoration: none;
      transition: var(--transition);
    }

    .footer-links a:hover {
      color: white;
      padding-left: 5px;
    }

    .footer-bottom {
      text-align: center;
      padding-top: 2rem;
      margin-top: 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: #ccc;
      font-size: 0.9rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2.2rem;
      }
      
      .hero-meta {
        flex-direction: column;
        gap: 1rem;
      }
      
      .navbar {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->
  <header>
    <div class="navbar">
      <a href="index.html" class="logo">Event<span>Ease</span></a>
      <a href="events-list.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Events
      </a>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-content">
      <h1><?php echo htmlspecialchars($event['NAME']); ?></h1>
      <div class="hero-meta">
        <div class="hero-meta-item">
          <i class="fas fa-calendar-alt"></i>
          <span><?php echo htmlspecialchars($event['EVENT_DATE']); ?></span>
        </div>
        <div class="hero-meta-item">
          <i class="fas fa-map-marker-alt"></i>
          <span><?php echo htmlspecialchars($event['LOCATION']); ?></span>
        </div>
        <div class="hero-meta-item">
          <i class="fas fa-tag"></i>
          <span><?php echo htmlspecialchars($event['CATEGORY']); ?></span>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Content -->
  <div class="container">
    <div class="event-details">
      <!-- Event Content -->
      <div class="event-content">
        <h2>About This Event</h2>
        <p class="event-description">
          <?php echo htmlspecialchars($event['DESCRIPTION']); ?>
        </p>

        <div class="event-highlights">
          <div class="highlight-item">
            <div class="highlight-icon">
              <i class="fas fa-star"></i>
            </div>
            <div class="highlight-content">
              <h3>Key Features</h3>
              <p>Experience world-class speakers, interactive workshops, and networking opportunities with industry leaders.</p>
            </div>
          </div>
          <div class="highlight-item">
            <div class="highlight-icon">
              <i class="fas fa-users"></i>
            </div>
            <div class="highlight-content">
              <h3>Who Should Attend</h3>
              <p>Professionals, entrepreneurs, and anyone interested in learning about the latest trends and innovations.</p>
            </div>
          </div>
          <div class="highlight-item">
            <div class="highlight-icon">
              <i class="fas fa-award"></i>
            </div>
            <div class="highlight-content">
              <h3>What You'll Gain</h3>
              <p>Practical knowledge, new skills, professional connections, and potential business opportunities.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Event Sidebar -->
     <!-- Event Sidebar -->
<div class="event-sidebar">
  <div class="event-info">
    <div class="info-item">
      <span class="info-label">Date:</span>
      <span class="info-value"><?php echo htmlspecialchars($event['EVENT_DATE']); ?></span>
    </div>
    <div class="info-item">
      <span class="info-label">Time:</span>
      <span class="info-value">
  <?php 
    // Format the time value as 12-hour format with AM/PM
    echo date('g:i A', strtotime($event['EVENT_TIME'])); 
  ?>
</span>

    </div>
    <div class="info-item">
      <span class="info-label">Location:</span>
      <span class="info-value"><?php echo htmlspecialchars($event['LOCATION']); ?></span>
    </div>
    <div class="info-item">
      <span class="info-label">Category:</span>
      <span class="info-value"><?php echo htmlspecialchars($event['CATEGORY']); ?></span>
    </div>
    <div class="info-item">
      <span class="info-label">Sponsor:</span>
      <span class="info-value"><?php echo htmlspecialchars($event['SPONSOR']); ?></span>
    </div>
  </div>
</div>

        <a href="register.php?id=<?php echo $event['EVENT_ID']; ?>">
          <button class="register-btn">
            <i class="fas fa-ticket-alt"></i> Register Now
          </button>
        </a>
      </div>
    </div>

    <!-- Gallery Section -->
    <section class="gallery-section">
      <h2 class="section-title">Event Gallery</h2>
      <div class="gallery-grid">
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1501281668745-f7f57925c3b4?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Event photo">
        </div>
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1531058020387-3be344556be6?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Event photo">
        </div>
        <div class="gallery-item">
          <img src="https://images.unsplash.com/photo-1511578314322-379afb476865?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Event photo">
        </div>
      </div>
    </section>
  </div>

  <!-- Footer -->
  <footer>
    <div class="footer-container">
      <div class="footer-about">
        <div class="footer-logo">EventEase</div>
        <p>Making event management simple and efficient for organizers and attendees alike.</p>
        <div class="social-links">
          <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
          <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
          <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="footer-links">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">Events</a></li>
          <li><a href="#">Categories</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Contact</a></li>
        </ul>
      </div>
      <div class="footer-links">
        <h3>Support</h3>
        <ul>
          <li><a href="#">FAQs</a></li>
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Terms of Service</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>
      <div class="footer-links">
        <h3>Contact Us</h3>
        <ul>
          <li><i class="fas fa-map-marker-alt"></i> 123 Event St, City</li>
          <li><i class="fas fa-phone"></i> (123) 456-7890</li>
          <li><i class="fas fa-envelope"></i> info@eventease.com</li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2023 EventEase. All rights reserved.</p>
    </div>
  </footer>
  </body>
  <?php $conn->close(); ?>

  </html>
