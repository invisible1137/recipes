<?php
$id = (int)($_GET['id'] ?? 0);
$review = getById('reviews', $id);

if (!$review) {
    die('Review not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        die('Invalid CSRF token.');
    }

    $name = trim($_POST['name']);
    $reviewText = trim($_POST['review']);
    $rate = (int)$_POST['rate'];

    if ($name && $reviewText && $rate >= 1 && $rate <= 5) {
        $updatedAt = (new DateTime())->format('Y-m-d H:i:s');
        $updateQuery = "UPDATE reviews SET name = :name, review = :review, rate = :rate, updated_at = :updated_at WHERE id = :id";
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute([
            ':name' => $name,
            ':review' => $reviewText,
            ':rate' => $rate,
            ':updated_at' => $updatedAt,
            ':id' => $id,
        ]);
        header('Location: ?app=reviews&view=list');
        exit();
    } else {
        echo 'All fields are required, and rate must be between 1 and 5!';
    }
}
?>

<h2>Update Review</h2>
<form method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES) ?>">
    <label>
        Name:
        <input type="text" name="name" value="<?= htmlspecialchars($review['name'], ENT_QUOTES) ?>" required>
    </label><br>
    <label>
        Review:
        <textarea name="review" required><?= htmlspecialchars($review['review'], ENT_QUOTES) ?></textarea>
    </label><br>
    <label>
        Rate (1-5):
        <input type="number" name="rate" value="<?= htmlspecialchars($review['rate'], ENT_QUOTES) ?>" min="1" max="5" required>
    </label><br>
    <button type="submit">Update</button>
</form>
