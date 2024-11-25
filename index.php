<?php
session_start();

// Регенерация ID сессии для повышения безопасности
session_regenerate_id(true);

// Параметры куки для сессии
session_set_cookie_params([
    'lifetime' => 86400,   // Срок жизни куки (1 день)
    'path' => '/',         // Путь, на котором куки действительны
    'domain' => 'localhost', // Домен куки
    'secure' => false,     // Использовать только для https
    'httponly' => true,    // Ограничение доступа к куки через JavaScript
    'samesite' => 'Strict' // Политика SameSite для куки
]);

// Функция для получения или создания CSRF-токена
function getCsrfToken()
{
    if (empty($_SESSION['token'])) {
        $_SESSION['token'] = bin2hex(random_bytes(32)); // Создаем токен, если его нет
    }
    return $_SESSION['token'];
}

// Генерация токена
$csrfToken = getCsrfToken();

// Проверка инициализации приложения и представления
$app = 'users';
$view = 'index';
$dbPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'pdo' . DIRECTORY_SEPARATOR;
require_once $dbPath . 'db.php';

$apps = ['users', 'recipes', 'reviews'];
$views = ['create', 'update', 'delete', 'show', 'list', 'index'];

if (isset($_GET['app']) && in_array($_GET['app'], $apps)) {
    $app = $_GET['app'];
} else {
    header('Location: /?app=users&view=index');
    exit;
}

if (isset($_GET['view']) && in_array($_GET['view'], $views)) {
    $view = $_GET['view'];
}

// Проверка CSRF-токена для всех POST-запросов
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['token']) || !hash_equals($csrfToken, $_POST['token'])) {
        die('Invalid CSRF token. Operation denied.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/assets/css/normalize.css">
    <link rel="stylesheet" href="/assets/css/sakura.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <title>PHP CRUD | <?= strtoupper($app) . '::' . ucfirst(strtolower($view)) ?></title>
  </head>
  <body>
    <nav class="nav">
      <ul class="nav__list">
        <li class="nav__item <?= $app === 'users' ? 'active' : '' ?>">
          <a href="?app=users&view=index" class="nav__link">Users</a>
        </li>
        <li class="nav__item <?= $app === 'recipes' ? 'active' : '' ?>">
          <a href="?app=recipes&view=index" class="nav__link">Recipes</a>
        </li>
        <li class="nav__item <?= $app === 'reviews' ? 'active' : '' ?>">
          <a href="?app=reviews&view=index" class="nav__link">Reviews</a>
        </li>
      </ul>
    </nav>

    <div class="container">
      <nav class="nav">
        <ul class="nav__list">
          <?php if ($app === 'users'): ?>
            <li class="nav__item--crud">
              <a href="?app=users&view=list" class="nav__link--crud">Пользователи</a>
            </li>
            <li class="nav__item--crud">
              <a href="?app=users&view=create" class="nav__link--crud">Регистрация</a>
            </li>
          <?php elseif ($app === 'recipes'): ?>
            <li class="nav__item--crud">
              <a href="?app=recipes&view=list" class="nav__link--crud">Список рецептов</a>
            </li>
            <li class="nav__item--crud">
              <a href="?app=recipes&view=create" class="nav__link--crud">Создать рецепт</a>
            </li>
          <?php elseif ($app === 'reviews'): ?>
            <li class="nav__item--crud">
              <a href="?app=reviews&view=list" class="nav__link--crud">Отзывы</a>
            </li>
            <li class="nav__item--crud">
              <a href="?app=reviews&view=create" class="nav__link--crud">Оставить отзыв</a>
            </li>
          <?php endif; ?>
        </ul>
      </nav>
      <?php
      if (in_array($view, $views)) {
        $path = $app . DIRECTORY_SEPARATOR . $view . '.php';
        include_once $path;
      }
      ?>
    </div>

    <footer>
      <?php if (defined('DEBUG') && DEBUG): // Показывать токен только в режиме отладки ?>
        <p>Session Token: <?= $csrfToken ?></p>
      <?php endif; ?>
    </footer>
  </body>
</html>

