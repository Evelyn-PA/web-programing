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
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar">
    <h1 class="logo">Wander Whimsy</h1>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Destination</a></li>
        <li><a href="#">Travel Tips</a></li>
        <li><a href="#">About</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="create_post.php">Create Post</a></li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>

<header>
    <div class="header-content">
        <h2>Explore The World With Wander Whimsy</h2>
        <p>Join us on a journey to discover hidden gems and exciting adventures.</p>

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
            <a href="auth/login.php" class="ctn">Start Exploring</a>
        <?php endif; ?>
    </div>
</header>

<!-- Recent Posts -->
<section>
    <h2>Recent Posts</h2>
    <div class="recent-posts">
        <?php
        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
                $postTitle = !empty($row['title']) ? htmlspecialchars($row['title']) : "Untitled Post";
        ?>
            <div class="post-card">
                <h3><?php echo $postTitle; ?></h3>
                <a href="post.php?id=<?php echo $row['id']; ?>">🔗</a>
            </div>
        <?php
            endwhile;
        else:
            echo "<p>No recent posts available.</p>";
        endif;
        ?>
    </div>
</section>

<!-- Comment Section -->


<!-- Footer -->
<footer>
        <div class="footer-content">
            <div class="footer-section intro-section">
                <p>Did you come here for something in particular or just general Riker?</p>
            </div>

            <!-- A section with links to different blog categories -->
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

            <!-- Quick links for easy navigation -->
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Terms & Conditions</a></li>
                    <li><a href="#">Support</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Newsletter subscription form and social media links -->
            <div class="footer-section">
                <h3>Subscribe For Newsletter</h3>
                <form>
                    <input type="email" placeholder="Your Email" required>
                    <button type="submit">Subscribe</button>
                </form>

                <!-- Social media icons -->
                <h3>Follow us</h3>
                <div class="social-icons">
                    <div class="social-links">
                        <a href="#"><img src="Images/FB_logo.jpg" alt="Facebook"></a>
                        <a href="#"><img src="Images/Twitter_logo.jpg" alt="Twitter"></a>
                        <a href="#"><img src="Images/IG_logo.jpg" alt="Instagram"></a>
                        <a href="#"><img src="Images/TT_logo.jpg" alt="Tiktok"></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
