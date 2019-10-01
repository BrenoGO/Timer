<?php

require '../../config/connection.php';
require '../../phpFunctions/functionsPDO.php';

$email = $_POST['email'];
$password = MD5($_POST['password']);

$qr = 'select * from users where email=? and password=?';
$values = [$email, $password];
$user = fetchAssoc($dbh, $qr, $values);

if($user['name']) {
  setcookie('name@timer',$user['name'],time()+(5*12*30*24*3600));
  setcookie('id@timer',$user['id'],time()+(5*12*30*24*3600));
  echo '<script>window.location.href="index.php"</script>';
  die();
} else {
  echo '<script>
      alert("E-mail or Password is not correct");
      window.location.href = "index.php" ;
      </script>';
}
