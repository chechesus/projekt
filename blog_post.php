<?php include 'api/session.php';?> 
<?php
// Handle comment submission
if (isset($_POST['new-comment'])) {
    $new_comment = $_POST['new-comment'];
    $article_id = $_POST['article-id'];

    // Insert the new comment into the database (assuming you have a comments table)
    // Example: $conn->query("INSERT INTO comments (article_id, comment) VALUES ('$article_id', '$new_comment')");

    // Insert a notification for the new comment
    $notification_message = "A new comment has been posted on your thread.";
    $sql = "INSERT INTO notifications (message, type, sent) VALUES ('$notification_message', 'comment', 0)";
    $conn->query($sql);
}

// Handle comment editing
if (isset($_POST['edited-comment'])) {
    $edited_comment = $_POST['edited-comment'];
    $comment_id = $_POST['comment-id'];

    // Update the comment in the database
    $query = "UPDATE comments SET text = '$edited_comment' WHERE id = '$comment_id'";
    mysqli_query($conn, $query);

    // Redirect to the same page to display the updated comment
    header("Location: article.php?id=$article_id");
    exit;
}

require 'inc/header.php';
?>
<body>
    <div class="grid-container">
        <?php include 'website_elements/menu.php';?> 
    </div>
    <div class="text">
    <?php
    include 'api/config.php';
    // Get the article ID from the URL parameter
    $article_id = $_GET['id'];

    // Query the database to retrieve the article data
    $query = "SELECT title, content FROM articles WHERE article_id = '$article_id'";
    $result = mysqli_query($conn, $query);

    // Check if the article exists
    if (mysqli_num_rows($result) > 0) {
        $article_data = mysqli_fetch_assoc($result);
        $title = $article_data['title'];
        $content = $article_data['content'];

        // Display the article content
        echo "<h1>$title</h1>";
        echo "<p>$content</p>";
    } else {
        echo "Article not found.";
    }
    ?>
    </div>
<div class="comment-thread">
    <?php
    // Query the database to retrieve comments for this article
    $query = "SELECT * FROM comments JOIN users ON comments.fk_user_ID = users.id WHERE article_id = '$article_id' ORDER BY timestamp DESC";
    $result = mysqli_query($conn, $query);

    while ($comment = mysqli_fetch_assoc($result)) {
        $comment_id = $comment['comment_id'];
        $comment_text = $comment['comment_text'];
        $comment_author = $comment['nick'];
        $comment_timestamp = $comment['timestamp'];

        // Display the comment
        echo "<div class='comment' id='comment-$comment_id'>";
        echo "<div class='comment-heading'>";
        echo "<div class='comment-voting'>";
        echo "<button type='button'>";
        echo "<span aria-hidden='true'>&#9650;</span>";
        echo "<span class='sr-only'>Vote up</span>";
        echo "</button>";
        echo "<button type='button'>";
        echo "<span aria-hidden='true'>&#9660;</span>";
        echo "<span class='sr-only'>Vote down</span>";
        echo "</button>";
        echo "</div>";
        echo "<div class='comment-info'>";
        echo "<a href='#' class='comment-author'>$comment_author</a>";
        echo "<p class='m-0'>";
        echo "$comment_timestamp";
        echo "</p>";
        echo "</div>";
        echo "</div>";
        echo "<div class='comment-body'>";
        echo "<p>$comment_text</p>";
        echo "<button type='button'>Reply</button>";
        echo "<button type='button'>Flag</button>";
        echo "</div>";

        // Display edit form if the user clicks the edit button
        if (isset($_POST['edit-comment']) && $_POST['edit-comment'] == $comment_id) {
            echo "<form action='' method='post'>";
            echo "<textarea name='edited-comment'>$comment_text</textarea>";
            echo "<input type='hidden' name='comment-id' value='$comment_id'>";
            echo "<button type='submit'>Update Comment</button>";
            echo "</form>";
        }

        echo "</div>";
    }

       // Check if the user is logged in
        if ( $_SESSION["loggedin"] === true) {
        echo "<form action='blog_post.php?id=1' method='post'>";
        echo "<textarea name='new-comment'></textarea>";
        echo "<input type='hidden' name='article-id' value='$article_id'>";
        echo "<button type='submit'>Add Comment</button>";
        echo "</form>";
    } else {
        echo "<div >";
        echo "<h2>Musíte sa prihlásiť na pridanie komentárov";
        echo "</h2>";
        echo "</div>";
    }
    ?>
</div>


<script>
    if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
</script>
<?php include 'website_elements/footer.php';?>
</body>
</html>
