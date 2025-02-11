<?php
require_once 'C:\xampp\htdocs\projekt\cms\auth\auth.php';
// Fetch posts
$posts_query = "SELECT * FROM posts";
$posts_result = mysqli_query($conn, $posts_query);

// Fetch comments
$comments_query = "SELECT * FROM comments WHERE status = 'pending'";
$comments_result = mysqli_query($conn, $comments_query);

// Approve or reject comments
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['approve_comment'])) {
        $comment_id = $_POST['comment_id'];
        $update_query = "UPDATE comments SET status = 'approved' WHERE id = $comment_id";
        mysqli_query($conn, $update_query);
    } elseif (isset($_POST['reject_comment'])) {
        $comment_id = $_POST['comment_id'];
        $update_query = "UPDATE comments SET status = 'rejected' WHERE id = $comment_id";
        mysqli_query($conn, $update_query);
    } elseif (isset($_POST['delete_post'])) {
        $post_id = $_POST['post_id'];
        $delete_query = "DELETE FROM posts WHERE id = $post_id";
        mysqli_query($conn, $delete_query);
    }
    header('Location: moderator_dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Moderator Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Moderator Dashboard</h1>

    <h2>Posts</h2>
    <table>
        <tr>
            <th>Title</th>
            <th>Content</th>
            <th>Action</th>
        </tr>
        <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($post['title']); ?></td>
            <td><?php echo htmlspecialchars($post['content']); ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <button type="submit" name="delete_post">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Pending Comments</h2>
    <table>
        <tr>
            <th>Comment</th>
            <th>Action</th>
        </tr>
        <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
        <tr>
            <td><?php echo htmlspecialchars($comment['content']); ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="comment_id" value="<?php echo $comment['id']; ?>">
                    <button type="submit" name="approve_comment">Approve</button>
                    <button type="submit" name="reject_comment">Reject</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>