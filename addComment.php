<?php
session_start();
require 'db.php'; // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate inputs
    $comment = htmlspecialchars($_POST['comment']);
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    // Insert the comment into the database
    $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $post_id, $user_id, $comment);

    if ($stmt->execute()) {
        // Redirect back to the post page after successful submission
        header("Location: post.php?id=$post_id");
        exit;
    } else {
        echo "Error submitting comment. Please try again.";
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}
?>
