<?php 

include_once $dbPath .  'db.php';

if(isset($_GET['id'])){
  // Вместо удаления сразу можно вывести пользователю вопрос для подтверждения удаления записи. Тогда на странице можно вывести данные записи и кнопку для удаления с методом пост. 
  // Подумайте, как это сделать. 
  delete('users', $_GET['id']);
  header("Location: /?app=$app&view=list");
}