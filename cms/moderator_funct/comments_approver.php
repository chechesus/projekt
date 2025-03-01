<?php
require_once '/xampp/htdocs/projekt/api/session.php';

$userId   = $_SESSION['userid'];
$roleId   = $_SESSION['role_id'];
$userName = $_SESSION['name'] ?? 'Moderátor';

// Spracovanie GET filtrov
$whereClauses = [];
if (!empty($_GET['article_id'])) {
    $article_id = (int)$_GET['article_id'];
    $whereClauses[] = "article_id = $article_id";
}
if (!empty($_GET['fk_user'])) {
    $fk_user = (int)$_GET['fk_user'];
    $whereClauses[] = "fk_user_ID = $fk_user";
}
if (!empty($_GET['from_date'])) {
    $from_date = mysqli_real_escape_string($conn, $_GET['from_date']);
    $whereClauses[] = "created_at >= '$from_date'";
}
if (!empty($_GET['to_date'])) {
    $to_date = mysqli_real_escape_string($conn, $_GET['to_date']);
    $whereClauses[] = "created_at <= '$to_date'";
}

$posts_query = "SELECT * FROM data.comments";
if (count($whereClauses) > 0) {
    $posts_query .= " WHERE " . implode(" AND ", $whereClauses);
}
$posts_query .= " ORDER BY created_at DESC";
$posts_result = mysqli_query($conn, $posts_query);

// Ak ide o AJAX požiadavku, vrátime iba HTML obsah tabuľky
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    while ($post = mysqli_fetch_assoc($posts_result)): ?>
        <tr>
            <td><?= htmlspecialchars(substr($post['comment_text'], 0, 60)) . '...'; ?></td>
            <td>
                <form method="post" action="" style="display:inline;">
                    <input type="hidden" name="post_id" value="<?= $post['comment_id']; ?>">
                    <button type="submit" name="delete_post" class="btn btn-sm btn-danger">Vymazať</button>
                    <button type="submit" name="approve_post" class="btn btn-sm btn-success">Povoliť</button>
                </form>
            </td>
        </tr>
    <?php endwhile;
    exit;
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Vlaky - Adminský panel</title>
    <link rel="icon" href="../images/logo.jpg">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Štýly a knižnice -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.3.0/styles/overlayscrollbars.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/projekt/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        <?php require_once 'C:\xampp\htdocs\projekt\cms\includes\nav.php'; ?>
        <aside class="app-sidebar">
            <aside class="sidebar">
                <?php require_once '../sidebar-menu/index.php'; ?>
            </aside>
        </aside>
        <main class="app-main">
            <div class="app-content">
                <div class="container-fluid">
                    <!-- Správa komentárov -->
                    <div class="card mb-4">
                        <div class="card-header">Správa komentárov</div>
                        <div class="card-body">
                            <!-- Priebežné filtrovanie -->
                            <form id="filterForm" method="get" action="moderator_dashboard.php" class="mb-4">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="article_id">ID príspevku:</label>
                                        <input type="number" name="article_id" id="article_id" class="form-control" value="<?= isset($_GET['article_id']) ? htmlspecialchars($_GET['article_id']) : '' ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="fk_user">ID používateľa:</label>
                                        <input type="number" name="fk_user" id="fk_user" class="form-control" value="<?= isset($_GET['fk_user']) ? htmlspecialchars($_GET['fk_user']) : '' ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="from_date">Od dátumu:</label>
                                        <input type="date" name="from_date" id="from_date" class="form-control" value="<?= isset($_GET['from_date']) ? htmlspecialchars($_GET['from_date']) : '' ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="to_date">Do dátumu:</label>
                                        <input type="date" name="to_date" id="to_date" class="form-control" value="<?= isset($_GET['to_date']) ? htmlspecialchars($_GET['to_date']) : '' ?>">
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <a href="moderator_dashboard.php" class="btn btn-secondary">Zrušiť filtre</a>
                                </div>
                            </form>
                            
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Obsah</th>
                                        <th>Akcia</th>
                                    </tr>
                                </thead>
                                <tbody id="commentsTableBody">
                                    <?php while ($post = mysqli_fetch_assoc($posts_result)): ?>
                                        <tr>
                                            <td><?= htmlspecialchars(substr($post['comment_text'], 0, 60)) . '...'; ?></td>
                                            <td>
                                                <form method="post" action="" style="display:inline;">
                                                    <input type="hidden" name="post_id" value="<?= $post['comment_id']; ?>">
                                                    <button type="submit" name="delete_post" class="btn btn-sm btn-danger">Vymazať</button>
                                                    <button type="submit" name="approve_post" class="btn btn-sm btn-success">Povoliť</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- JavaScript pre priebežné filtrovanie -->
    <script>
    function applyFilters() {
        const articleId = document.getElementById('article_id').value;
        const fkUser = document.getElementById('fk_user').value;
        const fromDate = document.getElementById('from_date').value;
        const toDate = document.getElementById('to_date').value;

        const queryParams = new URLSearchParams();
        queryParams.append('ajax', '1');
        if (articleId) {
            queryParams.append('article_id', articleId);
        }
        if (fkUser) {
            queryParams.append('fk_user', fkUser);
        }
        if (fromDate) {
            queryParams.append('from_date', fromDate);
        }
        if (toDate) {
            queryParams.append('to_date', toDate);
        }

        fetch('moderator_dashboard.php?' + queryParams.toString())
            .then(response => response.text())
            .then(html => {
                document.getElementById('commentsTableBody').innerHTML = html;
            })
            .catch(error => console.error('Chyba pri filtrovaní:', error));
    }

    // Pridáme event listenery na všetky filtračné polia
    document.getElementById('article_id').addEventListener('input', applyFilters);
    document.getElementById('fk_user').addEventListener('input', applyFilters);
    document.getElementById('from_date').addEventListener('change', applyFilters);
    document.getElementById('to_date').addEventListener('change', applyFilters);
    </script>
</body>
</html>
<?php
// Spracovanie POST požiadaviek
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve_comment'])) {
        $comment_id = (int)$_POST['comment_id'];
        $upd = "UPDATE data.comments SET status='approved' WHERE comment_id=$comment_id";
        mysqli_query($conn, $upd);
        header("Location: moderator_dashboard.php");
        exit;
    }
    if (isset($_POST['reject_comment'])) {
        $comment_id = (int)$_POST['comment_id'];
        $upd = "UPDATE data.comments SET status='rejected' WHERE comment_id=$comment_id";
        mysqli_query($conn, $upd);
        header("Location: moderator_dashboard.php");
        exit;
    }
    if (isset($_POST['delete_post'])) {
        $post_id = (int)$_POST['post_id'];
        $del = "DELETE FROM data.comments WHERE comment_id=$post_id";
        mysqli_query($conn, $del);
        header("Location: moderator_dashboard.php");
        exit;
    }
}

mysqli_close($conn);
?>
