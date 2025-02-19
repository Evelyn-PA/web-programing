<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "anh";
$password = "123456789";
$dbname = "wander_whimpsy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch latest blog posts
$sql = "SELECT * FROM blog_posts ORDER BY id DESC LIMIT 5"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wander Whimsy</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css?v=1.0">
    <!-- Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Orbitron:wght@400..900&family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>

<!-- Navigation -->
<nav class="navbar">
    <h1 class="logo">Wander Whimsy</h1>
    <ul class="nav-links">
        <li><a href="header.php">Home</a></li>
        <li><a href="#">Destination</a></li>
        <li><a href="#">Travel Tips</a></li>
        <li><a href="#">About</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="createPost.php">Create Post</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Header Section -->
<header>
    <div class="header-content">
        <h2>Explore The World With Wander Whimsy</h2>
        <p>Join us on a journey to discover hidden gems, exciting adventures, and unforgettable experiences around the globe. Let Wander Whimsy be your guide to a world full of wonder and wanderlust.</p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php
            $user_id = $_SESSION['user_id'];
            $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($username);
            $stmt->fetch();
            $stmt->close();
            ?>
            <p class="welcome-message">Welcome, <?php echo htmlspecialchars($username); ?>!</p>
        <?php else: ?>
            <a href="login.php" class="ctn">Start Exploring</a>
        <?php endif; ?>
    </div>
</header>

<!-- About Us Section -->
<section class="about-us">
    <div class="about-content">
        <div class="about-text">
            <h3>ABOUT US</h3>
            <h2>Explore The World With Us</h2>
            <p>Welcome to Wander Whimsy, where we share inspiring travel stories, practical tips, and carefully curated guides to help you explore the world, no matter your budget or experience level.</p>
        </div>
        <div class="about-image">
            <img src="Images/about_us.jpg" alt="About us">
        </div>
    </div>
</section>

<!-- Options Section -->
<section class="options">
    <div class="option-box">
        <img src="Images/option-1.jpg" alt="Travel Blog">
        <button>Travel Blog</button>
    </div>
    <div class="option-box">
        <img src="Images/option-2.jpg" alt="Destination">
        <button>Destination</button>
    </div>
    <div class="option-box">
        <img src="Images/option-3.jpg" alt="Travel Tips">
        <button>Travel Tips</button>
    </div>
</section>

<!-- Quote Section -->
<section class="quote-section">
    <h1>"Every journey begins with curiosity, and every destination has a story to tell."</h1>
    <p>- Wander Whimsy Team</p>
</section>

<!-- Recent Posts Section -->
<section class="recent-posts">
    <h2>Recent Posts</h2>
    <div class="posts-container">
        <?php
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
                $postTitle = !empty($row['title']) ? htmlspecialchars($row['title']) : "Untitled Post";
                $postContent = !empty($row['content']) ? htmlspecialchars(substr($row['content'], 0, 150)) . "..." : "No content available.";
        ?>
        <div class="post-card">
            <a href="post.php?id=<?php echo $row['id']; ?>">
                <h3><?php echo $postTitle; ?></h3>
                <p><?php echo $postContent; ?></p>
                <button>Read More</button>
            </a>
        </div>
        <?php
            endwhile;
        else:
            echo "<p>No recent posts available.</p>";
        endif;
        ?>
    </div>
</section>

<!-- Footer Section -->
<footer>
    <div class="footer-content">
        <div class="footer-section">
            <p>Did you come here for something in particular or just general Riker?</p>
        </div>
        <div class="footer-section">
            <h3>Blogs</h3>
            <ul>
                <li><a href="#">Travel</a></li>
                <li><a href="#">Technology</a></li>
                <li><a href="#">Lifestyle</a></li>
                <li><a href="#">Fashion</a></li>
                <li><a href="#">Business</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Terms & Conditions</a></li>
                <li><a href="#">Support</a></li>
                <li><a href="#">Privacy Policy</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Subscribe For Newsletter</h3>
            <form>
                <input type="email" placeholder="Your Email" required>
                <button type="submit">Subscribe</button>
            </form>
            <h3>Follow us</h3>
            <div class="social-icons">
                <a href="#"><img src="Images/FB_logo.jpg" alt="Facebook"></a>
                <a href="#"><img src="Images/Twitter_logo.jpg" alt="Twitter"></a>
                <a href="#"><img src="Images/IG_logo.jpg" alt="Instagram"></a>
                <a href="#"><img src="Images/TT_logo.jpg" alt="Tiktok"></a>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="style.js"></script>

</body>
</html>