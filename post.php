<?php
session_start();
require 'db.php'; // Include database connection

// Check if a post ID is provided in the URL
if (!isset($_GET['id'])) {
    header("Location: header.php"); // Redirect to homepage if no post ID
    exit;
}

$post_id = $_GET['id'];

// Fetch the blog post from the database
$sql = "SELECT blog_posts.*, users.username 
        FROM blog_posts 
        JOIN users ON blog_posts.user_id = users.id 
        WHERE blog_posts.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    // If no post is found, redirect to homepage
    header("Location: header.php");
    exit;
}

$post = $result->fetch_assoc(); // Fetch the post data
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - Wander Whimsy</title>
    <link rel="stylesheet" href="style.css?v=1.0">

</head>
<body>

    <div class="post-container">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        <p><em>Posted by: <?php echo htmlspecialchars($post['username']); ?></em></p>
    </div>

    <!-- Comment Form (for logged-in users) -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="comment-form">
            <h3>Leave a Comment</h3>
            <form action="addComment.php" method="POST">
                <textarea name="comment" placeholder="Write your comment..." required></textarea>
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <button type="submit">Submit Comment</button>
            </form>
        </div>
    <?php else: ?>
        <p id ="text" ><a href="login.php">Log in</a> to leave a comment.</p>
    <?php endif; ?>

    <!-- Display Comments -->
    <div class="comments-section">
        <h3>Comments</h3>
        <?php
        // Fetch comments for this post
        $sql = "SELECT comments.*, users.username 
                FROM comments 
                JOIN users ON comments.user_id = users.id 
                WHERE post_id = ? 
                ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $post_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($comment = $result->fetch_assoc()) {
                echo "<div class='comment'>";
                echo "<p><strong>" . htmlspecialchars($comment['username']) . "</strong> <em>(" . $comment['created_at'] . ")</em></p>";
                echo "<p>" . nl2br(htmlspecialchars($comment['comment'])) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>No comments yet. Be the first to comment!</p>";
        }
        ?>
    </div>
</body>
</html>
