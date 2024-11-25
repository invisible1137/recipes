<?php
$id = $_GET['id'] ?? null;
$recipe = getById('recipes', $id); // Получаем рецепт по ID
?>

<h2><?= $recipe['title'] ?></h2>
<p><?= $recipe['description'] ?></p>
<p>Calories: <?= $recipe['kcal'] ?></p>
<p>Created: <?= $recipe['created_at'] ?></p> <!-- Отображаем время создания -->
<p>Updated: <?= $recipe['updated_at'] ?></p> <!-- Отображаем время обновления -->
<a href="?app=recipes&view=update&id=<?= $recipe['id'] ?>">Edit</a>
<a href="?app=recipes&view=delete&id=<?= $recipe['id'] ?>">Delete</a>
