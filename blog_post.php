<?php include 'api/session.php';?> 
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Prihlásiť sa</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
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
    $query = "SELECT * FROM comments WHERE article_id = '$article_id' ORDER BY timestamp DESC";
    $result = mysqli_query($conn, $query);
    $query2 = "SELECT nick from users WHERE ID = (SELECT user_id FROM `comments` join users on comments.user_id = users.ID)";
    $result2 = mysqli_query($conn, $query2);

    while ($comment = mysqli_fetch_assoc($result)) {
        $comment_id = $comment['comment_id'];
        $comment_text = $comment['comment_text'];
        $comment_author = mysqli_fetch_assoc($result2);
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

        // Display edit button if the user is the comment author
        if ($_SESSION['username'] == $comment_author) {
            echo "<button type ='button' class ='edit-comment' data-comment-id='$comment_id'>Edit</button>";
        }

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

       // Display the comment submission form
       if (isset($_SESSION['username'])) {
        echo "<form action='' method='post'>";
        echo "<textarea name='new-comment'></textarea>";
        echo "<input type='hidden' name='article-id' value='$article_id'>";
        echo "<button type='submit'>Add Comment</button>";
        echo "</form>";
    } else {
        echo "You must be logged in to add a comment.";
    }
    ?>
</div>

<?php
// Handle comment submission
if (isset($_POST['new-comment'])) {
    $new_comment = $_POST['new-comment'];
    $article_id = $_POST['article-id'];
    $author = $_SESSION['username'];

    // Insert the new comment into the database
    $query = "INSERT INTO comments (article_id, author, text) VALUES ('$article_id', '$author', '$new_comment')";
    mysqli_query($conn, $query);

    // Redirect to the same page to display the new comment
    header("Location: article.php?id=$article_id");
    exit;
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
?>