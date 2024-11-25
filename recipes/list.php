<?php  
include_once $dbPath . 'db.php';

// Обработка запроса на удаление рецепта
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        die('Invalid CSRF token. Operation denied.');
    }

    $id = (int)$_POST['id'];
    if ($id > 0) {
        // Удаление рецепта
        $deleteQuery = "DELETE FROM recipes WHERE id = :id";
        $stmt = $pdo->prepare($deleteQuery);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            // Перенаправление обратно на страницу с рецепты
            header('Location: ?app=recipes&view=list');
            exit();
        } else {
            echo 'Ошибка при удалении рецепта.';
        }
    }
}

// Получаем все рецепты из базы
$recipes = getAll('recipes');
?>

<h2>Все рецепты</h2>

<?php foreach($recipes as $recipe): ?>
    <div class="recipe-item">
        <p>
            <strong>Название:</strong> <?= htmlspecialchars($recipe['title'], ENT_QUOTES) ?><br>
            <strong>Описание:</strong> <?= htmlspecialchars($recipe['description'], ENT_QUOTES) ?><br>
            <strong>Калории:</strong> <?= htmlspecialchars($recipe['kcal'], ENT_QUOTES) ?><br>
            <strong>Создано:</strong> <?= htmlspecialchars($recipe['created_at'], ENT_QUOTES) ?><br>
            <strong>Обновлено:</strong> <?= htmlspecialchars($recipe['updated_at'], ENT_QUOTES) ?><br>
        </p>
        <div class="action-buttons">
            <!-- Кнопка для редактирования -->
            <a href="?app=recipes&view=update&id=<?= htmlspecialchars($recipe['id'], ENT_QUOTES) ?>" class="button edit-button">Редактировать</a>

            <!-- Форма для удаления рецепта -->
            <form method="POST" style="display:inline;">
                <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES) ?>"> <!-- CSRF токен -->
                <input type="hidden" name="id" value="<?= htmlspecialchars($recipe['id'], ENT_QUOTES) ?>"> <!-- ID рецепта -->
                <button type="submit" name="action" value="delete" class="button delete-button">Удалить</button> <!-- Кнопка удаления -->
            </form>
        </div>
    </div>
<?php endforeach; ?>
