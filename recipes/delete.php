<?php
$id = $_POST['id'] ?? null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Проверка токена
    if (!isset($_POST['token']) || !hash_equals($_SESSION['token'], $_POST['token'])) {
        $error = 'Invalid CSRF token. Operation denied.';
    } else {
        if ($id) {
            // Удаляем рецепт по ID
            delete('recipes', $id);
            header('Location: ?app=recipes&view=list');
            exit();
        } else {
            $error = 'Recipe not found.';
        }
    }
}
?>

<?php if ($error): ?>
    <p style="color: red;"><?= htmlspecialchars($error, ENT_QUOTES) ?></p>
<?php endif; ?>
