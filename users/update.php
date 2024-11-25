<?php 

include_once $dbPath .  'db.php';

$user = getById('users', $_GET['id']);

if(!empty($_POST)){
  $username = $_POST['username'];
  $password = $_POST['password'];
  $userId = $_GET['id'];
  $age = $_POST['age'];
  update('users',  [
    'id' => $_GET['id'],
    'username' => $username,
    'password' => $password,
    'age' => $age
  ]);   
  header("Location: /?app=$app&view=show&id=$userId");
}

?>

<h2>
  Update User
</h2>

<form action="" method="post" class="form">
  <div class="form__field">
    <label for="username" class="form__label">
      Username:
    </label>
    <input type="text" id="username" name="username" class="form__input" value="<?= $user['username'];?>">
  </div>
  <div class="form__field">
    <label for="password" class="form__label">
      Password:
    </label>
    <input type="password" id="password" name="password" class="form__input" value="<?= $user['password'];?>">
  </div>
  <div class="form__field">
    <label for="age" class="form__label">
      Age:
    </label>
    <input type="number" id="age" name="age" class="form__input" value="<?= $user['age'];?>">
  </div>

  <button type="submit">Send</button>
</form>