<?php
session_start();

// Redirect to login if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("db.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $title = htmlspecialchars(trim($_POST["title"]));
    $content = htmlspecialchars(trim($_POST["content"]));
    $user_id = $_SESSION['user_id'];

    $errors = [];

    if (empty($title)) {
        $errors[] = "Title is required.";
    }

    // Check if content is empty
    if (empty($content)) {
        $errors[] = "Content is required.";
    }
 
    // insert the post into the database if no error
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO blog_posts (title, content, user_id) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ssi", $title, $content, $user_id);

        if ($stmt->execute()) {
            // Post created successfully
            header("Location: header.php");
            exit();
        } else {
            $errors[] = "Error creating post. Please try again.";
        }

        // Close statement
        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - Wander Whimsy</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="create-post-container">
        <h2>Create a New Post</h2>
        <?php if (!empty($errors)): ?>
            <div class="error-messages">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        //Form to create the post content
        <form method="POST">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" placeholder="Enter post title" required>
            </div>
            <div class="form-group">
                <label for="content">Content:</label>
                <textarea name="content" placeholder="Write your post content" rows="10" required></textarea>
            </div>
            <button type="submit" class="btn">Create Post</button>
        </form>
    </div>
</body>
</html>
