<?php
$id = $_GET['id'] ?? null;
if ($id) {
    delete('reviews', $id); // Удаляем отзыв
    header('Location: ?app=reviews&view=list');
}
?>
