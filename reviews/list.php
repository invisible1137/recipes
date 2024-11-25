<?php  
include_once $dbPath . 'db.php';

// Обработка запроса на удаление
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        die('Invalid CSRF token.');
    }

    $id = (int)$_POST['id'];
    if ($id > 0) {
        $deleteQuery = "DELETE FROM reviews WHERE id = :id";
        $stmt = $pdo->prepare($deleteQuery);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: ?app=reviews&view=list');
        exit();
    }
}

$reviews = getAll('reviews');
?>

<h2>All Reviews</h2>
<?php foreach($reviews as $review): ?>
    <div class="review-item">
        <p>
            <strong>Name:</strong> <?= htmlspecialchars($review['name'], ENT_QUOTES) ?><br>
            <strong>Review:</strong> <?= htmlspecialchars($review['review'], ENT_QUOTES) ?><br>
            <strong>Rate:</strong> <?= htmlspecialchars($review['rate'], ENT_QUOTES) ?><br>
            <strong>Created At:</strong> <?= htmlspecialchars($review['created_at'], ENT_QUOTES) ?><br>
            <strong>Updated At:</strong> <?= htmlspecialchars($review['updated_at'], ENT_QUOTES) ?><br>
        </p>
        <div class="action-buttons">
            <a href="?app=reviews&view=update&id=<?= htmlspecialchars($review['id'], ENT_QUOTES) ?>" class="button edit-button">Edit</a>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES) ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars($review['id'], ENT_QUOTES) ?>">
                <button type="submit" name="action" value="delete" class="button delete-button">Delete</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>

