<?php
require_once __DIR__ . '/../../api/session.php';

$q = $_POST['q'] ?? '';
$q = trim($q);

if (empty($q)) {
    echo "<h2>Musíte zadať hľadaný výraz.</h2>";
    exit;
}

// Predpokladáme, že máte tabuľku `articles` s poliami `title` a `category`.
$stmt = $conn->prepare("SELECT * FROM articles.articles WHERE title LIKE CONCAT('%', ?, '%') OR category LIKE CONCAT('%', ?, '%')");
$stmt->bind_param("ss", $q, $q);
$stmt->execute();
$result = $stmt->get_result();

// Vypíšeme výsledky
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p>Kategória: " . htmlspecialchars($row['category']) . "</p>";
        echo "<a href='/projekt/blog_post.php?id=" . (int)$row['id'] . "'>Zobraziť</a>";
        echo "</div>";
    }
} else {
    echo "<h2>Žiadne články nevyhovujú hľadaniu.</h2>";
}
