<?php 

include_once $dbPath .  'db.php';

// Отладочный код, чтобы посмотреть содержимое GET и POST запросов.

// echo "<h2>GET запрос</h2>"; 
// var_dump($_GET);
// echo "<hr2>";

// echo "<h2>POST запрос</h2>";
// var_dump($_POST);
// echo "<hr2>";

if(!empty($_POST)){
  $username = $_POST['username'];
  $password = $_POST['password'];
  $age = $_POST['age'];
  $id = create('users', [
    'username' => $username,
    'password' => $password,
    'age' => $age
  ]);
  if($id){
    echo 'User created'; // Вместо вывода сообщения лушче сделать перенаправление на страницу с созданной записью или списком всех записей. 
    // Подумайте, как это сделать. 
  }
  else{
    echo 'Error';
    // Здесь также можно перенаправить на страницу ошибки. Подумайте, как это сделать. 
  }
}
?>
<h2>
  Create User
</h2>

<form action="" class="form" method="post">
  <div class="form__field">
    <label for="username" class="form__label">
      Username:
    </label>
    <input type="text" id="username" name="username" class="form__input">
  </div>
  <div class="form__field">
    <label for="password" class="form__label">
      Password:
    </label>
    <input type="password" id="password" name="password" class="form__input">
  </div>
  <div class="form__field">
    <label for="age" class="form__label">
      Age:
    </label>
    <input type="number" id="age" name="age" class="form__input">
  </div>
  <button type="submit">Send</button>
</form>