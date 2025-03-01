<?php
// gallery.php
require_once '../api/session.php';

// Overenie prihlásenia – ak chcete, aby si galériu mohli prezerať len prihlásení užívatelia
if (!isset($_SESSION['userid'])) {
  header("Location: login.php");
  exit;
}

// Získanie filtrov z GET parametrov
$filterCategory = $_GET['category'] ?? '';
$filterCategory = $_GET['title'] ?? '';
$filterVehicle  = $_GET['vehicle'] ?? '';
$filterPeriod   = $_GET['period'] ?? ''; // napr. "today", "week", "month", "all"
$filterType     = $_GET['type'] ?? ''; // "mine" alebo "best" (Najlepší výber)

$query = "SELECT * FROM gallery.gallery_images WHERE 1=1";
$params = [];
$types = "";

// Filter podľa kategórie
if ($filterCategory !== '') {
  $query .= " AND category = ?";
  $params[] = $filterCategory;
  $types .= "s";
}

// Filter podľa vozidla
if ($filterVehicle !== '') {
  $query .= " AND vehicle = ?";
  $params[] = $filterVehicle;
  $types .= "s";
}

// Filter podľa typu: mine (používateľské) alebo best (admin)
if ($filterType === "mine") {
  $query .= " AND user_id = ?";
  $params[] = $_SESSION['userid'];
  $types .= "i";
} elseif ($filterType === "best") {
  $query .= " AND is_best = 1";
}

// Filter podľa obdobia uploadu
if ($filterPeriod === "today") {
  $query .= " AND DATE(upload_date) = CURDATE()";
} elseif ($filterPeriod === "week") {
  $query .= " AND upload_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
} elseif ($filterPeriod === "month") {
  $query .= " AND upload_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
} // default "all" – žiadny filter

$query .= " ORDER BY upload_date DESC";

$stmt = $conn->prepare($query);
if ($params) {
  $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
require '../inc/header.php';
?>

<body>
  <div class="grid-container">

    <?php require_once '../website_elements/menu.php'; ?>

  </div>
  <div class="container my-4">
    <!-- Filtračný formulár -->
    <form method="GET" class="mb-4">
      <div class="row g-2">
        <div class="col-md-3">
          <input type="text" name="category" class="form-control" placeholder="Kategória" value="<?= htmlspecialchars($filterCategory); ?>">
        </div>
        <div class="col-md-3">
          <input type="text" name="vehicle" class="form-control" placeholder="Vozidlo" value="<?= htmlspecialchars($filterVehicle); ?>">
        </div>
        <div class="col-md-2">
          <select name="period" class="form-select">
            <option value="">Obdobie: Všetko</option>
            <option value="today" <?= ($filterPeriod === 'today') ? 'selected' : ''; ?>>Dnes</option>
            <option value="week" <?= ($filterPeriod === 'week') ? 'selected' : ''; ?>>Tento týždeň</option>
            <option value="month" <?= ($filterPeriod === 'month') ? 'selected' : ''; ?>>Tento mesiac</option>
          </select>
        </div>
        <div class="col-md-2">
          <select name="type" class="form-select">
            <option value="">Typ: Všetko</option>
            <option value="mine" <?= ($filterType === 'mine') ? 'selected' : ''; ?>>Moje</option>
            <option value="best" <?= ($filterType === 'best') ? 'selected' : ''; ?>>Najlepší výber</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">Filtrovať</button>
        </div>
      </div>
    </form>

    <div class="row">
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($img = $result->fetch_assoc()):
          $id = $img['id'];
          $title = $img['title']; // Môžete pridať ďalší stĺpec pre názov, ak ho máte
          $imageContent = @file_get_contents($img['image_url']);
          if ($imageContent === false) {
              // fallback, ak načítanie zlyhá
              $imageContent = file_get_contents('C:\xampp\htdocs\projekt\images\user_ico.png');
          }
          $finfo      = new finfo(FILEINFO_MIME_TYPE);
          $mimeType   = $finfo->buffer($imageContent);
          $base64Image = base64_encode($imageContent);
          $img['image_url']  = "data:" . $mimeType . ";base64," . $base64Image;
          $thumb = !empty($img['image_url']) ? $img['image_url'] : "default_thumbnail.jpg";
          $upload_date = $img['upload_date'];
        ?>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="gallery-item">
              <a href="show_gallery_image.php?id=<?= $id; ?>">
                <img src="<?= htmlspecialchars($thumb); ?>" alt="<?= htmlspecialchars($title); ?>" class="gallery-thumbnail">
                <div class="gallery-content">
                  <div class="gallery-title"><?= htmlspecialchars($title); ?></div>
                  <div class="gallery-meta"><?= date("d.m.Y", strtotime($upload_date)); ?></div>
                </div>
              </a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Žiadne obrázky nenájdené.</p>
      <?php endif; ?>
    </div>
  </div>
  <?php require_once '../website_elements/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>