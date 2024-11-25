<?php
include_once $dbPath . 'db.php';

session_start();
$error = ''; // Для отображения ошибок

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка токена
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        $error = 'Invalid CSRF token. Operation denied.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $kcal = (int)($_POST['kcal'] ?? 0);

        // Проверка данных
        if ($title === '' || $description === '' || $kcal <= 0) {
            $error = 'All fields are required, and calories must be greater than 0.';
        } else {
            $recipeId = create('recipes', [
                'title' => $title,
                'description' => $description,
                'kcal' => $kcal,
                'created_at' => (new DateTime())->format('Y-m-d H:i:s'),
                'updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
            ]);

            if ($recipeId) {
                header('Location: /?app=recipes&view=list');
                exit();
            } else {
                $error = 'Failed to create the recipe. Please try again.';
            }
        }
    }
}
?>
<form method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES) ?>">

    <!-- Поле для названия рецепта -->
    <label for="title">Название :</label>
    <input type="text" id="title" name="title" placeholder="Введите название рецепта" value="<?= htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES) ?>" required>

    <!-- Поле для описания рецепта -->
    <label for="description">Описание :</label>
    <textarea id="description" name="description" placeholder="Введите описание рецепта" required><?= htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES) ?></textarea>

    <!-- Поле для количества калорий -->
    <label for="kcal">Калории :</label>
    <input type="number" id="kcal" name="kcal" placeholder="Введите количество калорий" value="<?= htmlspecialchars($_POST['kcal'] ?? 0, ENT_QUOTES) ?>" required min="1">

    <!-- Кнопка отправки -->
    <button type="submit">Создать рецепт</button>
</form>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
<?php endif; ?>
