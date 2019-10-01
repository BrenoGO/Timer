<?php
require '../../config/connection.php';
require '../../phpFunctions/functionsPDO.php';

$stopTime = $_GET['stoptime'];
$taskId = $_GET['id'];

$qr = 'update tasks set stopTime = ? where id = ?';
$values = [$stopTime, $taskId];
$stmt = executeSQL($dbh, $qr, $values);
if(is_string($stmt)){
  $message = $stmt;
}else{
  $message = 'ok, stopped the time at: '.$stopTime;
}

echo json_encode($message);