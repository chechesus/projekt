<div class="card mb-4">
    <div class="card-header">Komentáre na schválenie</div>
    <div class="card-body">
        <?php
        $comments_query = "SELECT * FROM comments WHERE status = 'pending' ORDER BY created_at DESC";
        $comments_result = mysqli_query($conn, $comments_query);
        ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Komentár</th>
                    <th>Akcia</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($comment = mysqli_fetch_assoc($comments_result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($comment['comment_text']); ?></td>
                        <td>
                            <form method="post" action="" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?= $comment['id']; ?>">
                                <button type="submit" name="approve_comment" class="btn btn-sm btn-success">Schváliť</button>
                                <button type="submit" name="reject_comment" class="btn btn-sm btn-warning">Zamietnuť</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>