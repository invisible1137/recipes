<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        die('Invalid CSRF token.');
    }

    $name = trim($_POST['name']);
    $review = trim($_POST['review']);
    $rate = (int)$_POST['rate'];

    if ($name && $review && $rate >= 1 && $rate <= 5) {
        $createdAt = (new DateTime())->format('Y-m-d H:i:s');
        $insertQuery = "INSERT INTO reviews (name, review, rate, created_at, updated_at) 
                        VALUES (:name, :review, :rate, :created_at, :updated_at)";
        $stmt = $pdo->prepare($insertQuery);
        $stmt->execute([
            ':name' => $name,
            ':review' => $review,
            ':rate' => $rate,
            ':created_at' => $createdAt,
            ':updated_at' => $createdAt,
        ]);
        header('Location: ?app=reviews&view=list');
        exit();
    } else {
        echo 'All fields are required, and rate must be between 1 and 5!';
    }
}
?>

<h2>Create Review</h2>
<form method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES) ?>">
    <label>
        Name:
        <input type="text" name="name" required>
    </label><br>
    <label>
        Review:
        <textarea name="review" required></textarea>
    </label><br>
    <label>
        Rate (1-5):
        <input type="number" name="rate" min="1" max="5" required>
    </label><br>
    <button type="submit">Create</button>
</form>
