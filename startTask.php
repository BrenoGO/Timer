<?php
$taskName = $_POST['taskName'];
$project = $_POST['project'];

$qr = 'select * from tasks where userId = ? and stopTime is null';
$values = [$userId];
$openedTask = fetchAssoc($dbh, $qr, $values);
if($openedTask){
  echo '<script>
    alert("You already have an opened task");
  </script>';
  die();
}
if($taskName === '') {
  echo '<script>
    alert("Your task gotta have a name...");
  </script>';
  die();
}
$qr = 'insert into tasks 
(id, userId, startTime, task, project) 
values (default, ?, ?, ?, ?)';
$values = [$userId, date('Y-m-d G:i:s'), $taskName, $project];

$stmt=executeSQL($dbh,$qr,$values);

if(is_string($stmt)){//deu erro no mysql..
  echo '<script>alert('.$stmt.')</script>';
}else{
  $taskId = $dbh->lastInsertId();
  $qr = 'select startTime from tasks where id = ?';
  $value = [$taskId];
  $row = fetchAssoc($dbh, $qr, $value);
  $startTime = $row['startTime'];
  echo '<script>
    localStorage.setItem("currentStartTime@timer", "'.$startTime.'")
    setInterval(getDif, 1000)
  </script>
  
  ';

}