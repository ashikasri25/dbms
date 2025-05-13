<?php
session_start();  // Start the session

//if (isset($_SESSION['user_id'])) {
  
 // header('Location: index.php'); // or whatever page you want
  //exit();
//}

// User is logged in, fetch events
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "event_data"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events from the database
$sql = "SELECT * FROM EVENTS";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EMS - Event Management System</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html, body {
      height: 100%;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: white;
      background-color: #000;
    }

    /* Navbar */
    .topnav {
      background-color: rgba(0, 0, 0, 0.7);
      overflow: hidden;
      padding: 10px 20px;
      position: relative;
      z-index: 2;
    }

    .topnav a {
      float: right;
      color: white;
      text-align: center;
      padding: 12px 18px;
      text-decoration: none;
      font-size: 18px;
      transition: 0.3s;
      border-radius: 5px;
    }

    .topnav a:hover {
      background-color: #0066cc;
      color: whitesmoke;
    }

    .topnav a.active {
      background-color: red;
      color: white;
    }

    /* Hero Section */
    .hero-section {
      height: 800px;
      position: relative;
      overflow: hidden;
    }

    .hero-section video {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 1;
    }

    .hero-text {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      z-index: 2;
    }

    .hero-text h1 {
      font-size: 48px;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .hero-text p {
      font-size: 20px;
    }

    /* Section Styling */
    .section {
      padding: 3rem 2rem;
      text-align: center;
      background-color: white;
    }

    .highlight {
      color: #00bfff;
    }

    .card-grid {
      display: grid;
      gap: 1.5rem;
      grid-template-columns: repeat(4, 1fr); /* 4 cards per row */
      margin-top: 1.5rem;
      justify-items: center; /* Center the cards */
    }

    .card {
      background-color: white;
      box-shadow: 0 0 10px #ddd;
      border-radius: 10px;
      overflow: hidden;
      transition: transform 0.2s ease;
      color: black;
    }

    .card:hover {
      transform: scale(1.02);
    }

    .card img {
  width: 100%;
  height: 180px; /* Fixed height for uniformity */
  object-fit: cover; /* Crop image to fit container */
  display: block;
}


    .card-info {
      padding: 1rem;
      text-align: left;
    }

    .view-more {
      margin-top: 1.5rem;
      padding: 0.6rem 1.2rem;
      background: #00bfff;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
    }

    .create-event {
      background: navy;
      padding: 3rem 2rem;
      text-align: center;
    }

    .create-event h2 {
      font-size: 32px;
      margin-bottom: 1rem;
    }

    .create-event p {
      font-size: 18px;
      margin-bottom: 1.5rem;
    }

    .create-btn {
      padding: 0.6rem 1.2rem;
      background: #ff6600;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
    }

    /* Responsive Fix */
    @media (max-width: 600px) {
      .hero-text h1 {
        font-size: 32px;
      }
      .hero-text p {
        font-size: 16px;
      }
    }
    .card-link {
  text-decoration: none;
  color: inherit;
  display: block;
  height: 100%;
}
.banner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      background-color: #140D69; /* Deep blue background */
      color: white;
      padding: 40px;
      border-radius: 10px;
      font-family: sans-serif;
    }
.brands {
      padding: 2rem;
      text-align: center;
      background-color: white;
    }
    .brand-logos{
      display: flex;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
      margin-top: 1rem;
     

    }
    .brand-logos img {
      width: 100px;
      height: auto;
    }
    .blogs-section {
  padding: 3rem 2rem;
  background-color: #f8f8f8;
  text-align: center;
}

.blogs-section h2 {
  font-size: 28px;
  margin-bottom: 2rem;
}

.black-text {
  color: #111;
}

.purple-text {
  color: #6C5CE7;
}

.blogs-grid {
  display: grid;
  gap: 2rem;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  justify-items: center;
}

.blog-card {
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  max-width: 350px;
  transition: transform 0.3s;
}

.blog-card:hover {
  transform: translateY(-5px);
}

.blog-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  padding-left: 10px;
  padding-right:10px;
  padding-bottom:10px;
  padding-top:10px;

  padding-right:10px;
  border-radius: 15px;
}

.blog-content {
  padding: 1rem;
  text-align: left;
}

.blog-label {
  display: inline-block;
  font-size: 12px;
  background: #eee;
  color: #333;
  padding: 2px 8px;
  border-radius: 4px;
  margin-bottom: 0.5rem;
}

.blog-content h3 {
  font-size: 16px;
  margin-bottom: 0.5rem;
  color: #222;
}

.blog-date,
.blog-location {
  font-size: 13px;
  color: #666;
  margin-bottom: 0.2rem;
}
.site-footer {
  background-color: #140D69;
  color: white;
  padding: 3rem 2rem 1rem;
  text-align: center;
}

.footer-container {
  max-width: 900px;
  margin: auto;
}

.footer-brand {
  font-size: 28px;
  margin-bottom: 1rem;
}

.footer-brand .highlight {
  color: #a56bff;
}

.subscribe-box {
  margin: 1rem 0;
}

.subscribe-box input {
  padding: 10px;
  width: 60%;
  max-width: 300px;
  border-radius: 6px 0 0 6px;
  border: none;
  outline: none;
}

.subscribe-box button {
  padding: 10px 20px;
  border-radius: 0 6px 6px 0;
  background-color: #a56bff;
  color: white;
  border: none;
  cursor: pointer;
}

.footer-links {
  margin-top: 2rem;
  display: flex;
  justify-content: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.footer-links a {
  color: #ccc;
  text-decoration: none;
  font-size: 14px;
}

.footer-links a:hover {
  color: #fff;
}

.social-icons {
  margin-top: 1rem;
  font-size: 24px;
}

.social-icons a {
  color: white;
  margin: 0 10px;
}

.footer-bottom {
  margin-top: 2rem;
  font-size: 12px;
  border-top: 1px solid #444;
  padding-top: 1rem;
}

.language-switcher {
  margin-bottom: 0.5rem;
}

.lang-btn {
  margin: 0 5px;
  padding: 6px 12px;
  background-color: #2f2f7f;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 12px;
  cursor: pointer;
}
.details-btn {
  display: block;
  margin-top: 12px;
  padding: 10px 20px;
  background-color: #007BFF;
  color: white;
  border: none;
  border-radius: 6px;
  font-size: 14px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s ease;
  width: 100%;
}

.details-btn:hover {
  background-color: #0056b3;
}
.hidden {
  display: none;
}




  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="topnav">
    <a class="active" href="user_signup.php">Signup</a>
    <a href="index.php">Home</a>
    <a href="user_login.php">Login</a>
   
    <a href="logout_user.php">Logout</a>
  </div>

  



  <!-- Hero Section -->
  <div class="hero-section">
    <video autoplay muted loop>
      <source src="event.mp4" type="video/mp4">
      Your browser does not support HTML5 video.
    </video>
    <div class="overlay"></div>
    <div class="hero-text">
      <h1>Welcome to EMS</h1>
      <p>Organize. Manage. Celebrate.</p>
    </div>
  </div>

  <!-- Upcoming Events Section -->
  <section class="section events">
    <h2 style="color:cadetblue;">Upcoming <span class="highlight">Events</span></h2>

    <div class="card-grid" id="card-grid">
    <?php
$limit = 4;
$count = 0;
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    $class = ($count >= $limit) ? 'hidden' : '';
?>
  <div class="card <?php echo $class; ?>">
    <img src="event3.jpg" alt="Event">
    <div class="card-info">
      <h3><strong><?php echo htmlspecialchars($row['NAME']); ?></strong></h3>
      <p><?php echo date('F d, Y', strtotime($row['EVENT_DATE'])); ?>, <?php echo htmlspecialchars($row['CATEGORY']); ?></p>

      <form action="r1.php" method="GET">
        <input type="hidden" name="id" value="<?php echo $row['EVENT_ID']; ?>">
        <button type="submit" class="details-btn">View Details</button>
      </form>
    </div>
  </div>
<?php
    $count++;
  }
}
?>

    </div>

    <button id="view-more-btn" class="view-more">View More</button>
  </section>
 <!-- JavaScript for toggling the "View More" functionality -->
 <script>
    document.getElementById('view-more-btn').addEventListener('click', function() {
      // Show all the remaining cards by removing the 'hidden' class
      let hiddenCards = document.querySelectorAll('.card-grid .card.hidden');
      hiddenCards.forEach(function(card) {
        card.classList.remove('hidden');
      });

      // Hide the "View More" button after clicking
      this.style.display = 'none';
    });

    // Initially hide all cards beyond the first 4
    document.querySelectorAll('.card-grid .card:nth-child(n+5)').forEach(function(card) {
      card.classList.add('hidden');
    });
  </script>
























  <!-- Create Event Section -->
  <section class="create-event">
    <div class="create-text">
      
      <h2>Make your own Event</h2>
      <p>Create events effortlessly and engage audiences.</p>
      <a href="my_bookings.php">
            <button class="create-btn">BOOKING DETAILS</button>
        </a>
    </div>
  
  </section>
  <section class="brands">
    <h3 style="color: navy;">Join these brands</h3>
    <div class="brand-logos">

      <img src="spotify.jpg" alt="Spotify">
      <img src="google.png" alt="Google">
      <img src="u tube.jpg" alt="Youtube">
      <img src="uber.jpg" alt="Uber">
      <img src="micro.png" alt="Microsoft">

    </div>
  </section>
 
  <section class="blogs-section">
    <h2><span class="black-text">Our</span> <span class="purple-text">Blogs</span></h2>
    <div class="blogs-grid">
  
      <!-- Blog Card 1 -->
      <div class="blog-card">
        <img src="blog1.jpg" alt="Blog Image">
        <div class="blog-content">
          <span class="blog-label">WEB</span>
          <h3>BestSeller Book Bootcamp - Write, Market & Publish Your Book - Lucknow</h3>
          <p class="blog-date">Saturday, March 9 · 3:30PM</p>
          <p class="blog-location">ONLINE EVENT · Attend anywhere</p>
        </div>
      </div>
  
      <!-- Blog Card 2 -->
      <div class="blog-card">
        <img src="blog2.jpg"alt="logImage">
        <div class="blog-content">
          <span class="blog-label">WEB</span>
          <h3>BestSeller Book Bootcamp - Write, Market & Publish Your Book - Lucknow</h3>
          <p class="blog-date">Saturday, March 9 · 3:30PM</p>
          <p class="blog-location">ONLINE EVENT · Attend anywhere</p>
        </div>
      </div>
  
      <!-- Blog Card 3 -->
      <div class="blog-card">
        <img src="blog 4.jpg"Blog Image">
        <div class="blog-content">
          <span class="blog-label">WEB</span>
          <h3>BestSeller Book Bootcamp - Write, Market & Publish Your Book - Lucknow</h3>
          <p class="blog-date">Saturday, March 9 · 3:30PM</p>
          <p class="blog-location">ONLINE EVENT · Attend anywhere</p>
        </div>
      </div>
  
    </div>
  </section>
  <footer class="site-footer">
    <div class="footer-container">
      <h2 class="footer-brand">Event <span class="highlight">Hive</span></h2>
  
      <div class="subscribe-box">
        <input type="email" plateceholder="Enter your mail">
        <button>Subscribe</button>
      </div>
  
      <div class="footer-links">
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Services</a>
        <a href="#">Get in touch</a>
        <a href="#">FAQs</a>
      </div>
  
      <div class="social-icons">
        <a href="#"><i class="fa fa-linkedin-square"></i></a>
        <a href="#"><i class="fa fa-instagram"></i></a>
        <a href="#"><i class="fa fa-facebook-official"></i></a>
      </div>
    </div>
  
    <div class="footer-bottom">
      <div class="language-switcher">
        <button class="lang-btn">English</button>
        <button class="lang-btn">French</button>
        <button class="lang-btn">Hindi</button>
      </div>
      <p>Non Copyrighted © 2023 Uploded by EventHive</p>
    </div>
  </footer>
  
  
</body>
</html>
