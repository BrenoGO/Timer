<?php

require '../../config/connection.php';
require '../../phpFunctions/functionsPDO.php';

$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];
$confPassword = $_POST['confPassword'];

if($password !== $confPassword) {
  echo '<script>
    alert("confirmation password different from the password")
    window.location.href="index.php"
    </script>';
    die();
}
if($password === '') {
  echo '<script>
    alert("password is empty...")
    window.location.href="index.php"
    </script>';
    die();
}
$password = MD5($password);
$qr = 'insert into users values (default, ?, ?, ?)';
$values = [$name, $email, $password];
$stmt = executeSQL($dbh,$qr,$values);

if(is_string($stmt)){//deu erro no mysql..
  echo '<script>
    alert("'.$stmt.'")
    window.location.href="index.php"
    </script>';
    die();
}else{
  $id = $dbh->lastInsertId();
  setcookie('name@timer',$name,time()+(5*12*30*24*3600));
  setcookie('id@timer',$id,time()+(5*12*30*24*3600));
  echo '<script>
    alert('.$id.')
    window.location.href="index.php"
  </script>';
}

