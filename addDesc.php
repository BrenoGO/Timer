<?php

require '../../config/connection.php';
require '../../phpFunctions/functionsPDO.php';

$json = file_get_contents('php://input');

$obj = json_decode($json,true);
$description = $obj['description'];
$taskId = $obj['taskId'];

$qr = 'update tasks set description = ? where id = ?';
$values = [$description, $taskId];

$stmt = executeSQL($dbh, $qr, $values);
if(is_string($stmt)){
  echo json_encode($stmt);  
}else{
  echo json_encode('ok: description inserted..');
}

