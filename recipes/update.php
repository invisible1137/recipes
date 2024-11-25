<?php 
include_once $dbPath . 'db.php';

$id = (int)($_GET['id'] ?? 0);
$recipe = $id > 0 ? getById('recipes', $id) : null;
$error = '';

if (!$recipe) {
    die('Recipe not found.');
}

// Генерация токена
if (empty($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка токена
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        $error = 'Invalid CSRF token. Operation denied.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $kcal = (int)($_POST['kcal'] ?? 0);

        if ($title === '' || $description === '' || $kcal <= 0) {
            $error = 'All fields are required, and calories must be greater than 0.';
        } else {
            $values = [
                'id' => $id,
                'title' => $title,
                'description' => $description,
                'kcal' => $kcal,
                'updated_at' => (new DateTime())->format('Y-m-d H:i:s'),
            ];
            if (update('recipes', $values)) {
                header('Location: ?app=recipes&view=list');
                exit();
            } else {
                $error = 'Failed to update the recipe. Please try again.';
            }
        }
    }
}
?>

<form method="POST">
    <!-- CSRF токен -->
    <input type="hidden" name="token" value="<?= htmlspecialchars($_SESSION['token'], ENT_QUOTES) ?>">

    <!-- Поле для названия -->
    <label for="title">Название:</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($recipe['title'], ENT_QUOTES) ?>" required>

    <!-- Поле для описания -->
    <label for="description">Описание:</label>
    <textarea id="description" name="description" required><?= htmlspecialchars($recipe['description'], ENT_QUOTES) ?></textarea>

    <!-- Поле для калорий -->
    <label for="kcal">Калории:</label>
    <input type="number" id="kcal" name="kcal" value="<?= htmlspecialchars($recipe['kcal'], ENT_QUOTES) ?>" required min="1">

    <!-- Кнопка обновления -->
    <button type="submit">Обновить</button>
</form>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
<?php endif; ?>
