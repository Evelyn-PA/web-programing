<?php
// Start the session (required for user login functionality)
session_start();

// Database connection (replace with your database credentials)
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

// Fetch blog posts from the database (example)
$sql = "SELECT * FROM blog_posts ORDER BY id DESC LIMIT 5"; // Fetch the latest 5 posts
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <nav class="navbar">
        <h1 class="logo">Wander Whimsy</h1>
        <ul class="nav-links">
            <li class="active"><a href="header.php">Home</a></li>
            <li><a href="#">Destination</a></li>
            <li><a href="#">Travel Tips</a></li>
            <li><a href="#">About</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Show logout link if user is logged in and the create post button-->
                <li><a href="create_post.php">Create Post</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                <!-- Show login/register links if user is not logged in -->
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Register</a></li>
            <?php endif; ?>
        </ul>
        <div class="mobile-nav">
            <img src="./menu-btn.png" alt="" class="menu-btn" />
            <h1 class="close-btn">X</h1>
        </div>
    </nav>

    <header>
    <div class="header-content">
        <h2>Explore The World With Wander Whimsy</h2>
        <p>
            Join us on a journey to discover hidden gems, exciting adventures, and
            unforgettable experiences around the globe. Let Wander Whimsy be your
            guide to a world full of wonder and wanderlust.
        </p>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- Display welcome message if user is logged in -->
            <?php
            // Fetch the username from the database
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
            <!-- Display "Start Exploring" button if user is not logged in -->
            <a href="#" class="ctn">Start Exploring</a>
        <?php endif; ?>
    </div>
</header>

    <script>
        const mobileNav = document.querySelector(".mobile-nav");
        const menuBtn = document.querySelector(".menu-btn");
        const navlinks = document.querySelector(".nav-links");
        const closeBtn = document.querySelector(".close-btn");

        mobileNav.addEventListener("click", (e) => {
            navlinks.classList.toggle("mobile-menu");
            if (e.target === menuBtn) {
                menuBtn.replaceWith(closeBtn);
                closeBtn.style.display = "block";
            }
            if (e.target === closeBtn) {
                closeBtn.replaceWith(menuBtn);
            }
        });
    </script>

    <title>Wander Whimsy</title>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="style.css">
    <lang="en">
        <!-- Font: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Orbitron:wght@400..900&family=Pacifico&display=swap"
            rel="stylesheet">
</head>

<body>
    <!-- The about us content with image -->
    <div class="body">
        <div id="content-abus">
            <h3 id="header">ABOUT US</h3>
            <h2 id="title">Explore The World </br>With Us</h2>
            <h4 id="content">Welcome to Wander Whimsy,</br>
                where we share inspiring travel stories, practical tips,</br>
                and carefully curated guides to help you explore the world.</br>
                no matter your budget or experience level.</h4>
        </div>
        <div>
            <img src="Images/about_us.jpg" alt="About us" id="about_us_img">
        </div>
    </div>

    <div class="option">
        <div class="box" id="option-1">
            <img src="Images/option-1.jpg" alt="option-1">
            <button id="btn-1">Travel Blog</button>
        </div>

        <div class="box" id="option-2">
            <img src="Images/option-2.jpg" alt="option-2">
            <button id="btn-2">Destination</button>
        </div>

        <div class="box" id="option-3">
            <img src="Images/option-3.jpg" alt="option-3">
            <button id="btn-3">Travel Tips</button>
        </div>
    </div>

    <div class="text-background">
        <h1>"Every journey begins with curiosity,
            and every destination has a story to tell."</h1>
        <p>- Wander Whimsy Team</p>
    </div>

    <h2>Recent Posts</h2>
    <div class="container swiper">
        <div class="card-wrapper">
            <ul class="card-list swiper-wrapper">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="swiper-slide">
                            <li class="card-item swiper-slide">
                                <a href="post.php?id=<?php echo $row['id']; ?>" class="card-link">
                                    <h2 class="card-title"><?php echo $row['title']; ?></h2>
                                    <button class="card-button material-symbols-rounded">-></button>
                                </a>
                            </li>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No blog posts found.</p>
                <?php endif; ?>
            </ul>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="style.js"></script>
</body>

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

</html>