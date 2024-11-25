<?php
$id = (int)($_GET['id'] ?? 0);
$review = getById('reviews', $id);

if (!$review) {
    die('Review not found.');
}
?>

<h2>Review Details</h2>
<p>
    <strong>Name:</strong> <?= htmlspecialchars($review['name'], ENT_QUOTES) ?><br>
    <strong>Review:</strong> <?= htmlspecialchars($review['review'], ENT_QUOTES) ?><br>
    <strong>Rate:</strong> <?= htmlspecialchars($review['rate'], ENT_QUOTES) ?><br>
    <strong>Created At:</strong> <?= htmlspecialchars($review['created_at'], ENT_QUOTES) ?><br>
    <strong>Updated At:</strong> <?= htmlspecialchars($review['updated_at'], ENT_QUOTES) ?><br>
</p>
<a href="?app=reviews&view=list">Back to list</a>
