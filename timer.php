<?php

require '../../config/connection.php';
require '../../phpFunctions/functionsPDO.php';

echo '<script src="timer.js"></script>';

if(isset($_GET['action'])){
  $action = $_GET['action'];
  if($action === 'startTask'){
    require 'startTask.php';
  }
}else{
  $action = false;
}


$userId = $_COOKIE['id@timer'];
$qr = 'select distinct(task) from tasks where userId = ?';
$values = [$userId];
$tasks = fetchToArray($dbh, $qr, $values);

$qr = 'select * from tasks where userId = ? and stopTime is null';
$values = [$userId];
$openedTask = fetchAssoc($dbh, $qr, $values);

echo '
</br>';
if(!$tasks && !($action)){
  echo '
  <div>
    <h4>You still dont have any task</h4>
  </div>';
}
if($action === 'updateTask') {
  $taskId=$_POST['taskId'];
  $project=$_POST['project'];
  $task=$_POST['task'];
  $startTime=$_POST['startTime'];
  $stopTime=$_POST['stopTime'];
  $description=$_POST['description'];

  $qr = 'update tasks set 
    project=?, task=?, startTime=?, stopTime=?, description=?
    where id=?';
  $values=[$project, $task, $startTime, $stopTime, $description,$taskId];
  $stmt = executeSQL($dbh, $qr, $values);
  if(is_string($stmt)){
    echo '<script>alert("Error while trying to update task: '.$stmt.'")</script>';
  }
}
if(!$openedTask){ //if dont have opened tasks, start or continue a task
  echo '
  <div id="startAtaskDiv">
    <h3>Start a task</h3>
    <form method="post" action="index.php?action=startTask">
    <label>
      Project Name:
      <input type="text" name="project" id="project" />
    </label>
    </br>
    <label>
      Task Name:
      <input type="text" name="taskName" id="taskName" />
    </label>
    </br>
    <input type="submit" value="START!" />
    </form>
  </div>
  ';
  if($tasks){
    echo '</br></br>
    <table>
    <thead>
      <tr>
        <td>Project</td>
        <td>Task</td>
        <td>Times working</td>
        <td>Total Time</td>
        <td>Continue?</td>
      </tr>
    </thead>
    <tbody>';
    function constructRows($someTasks){
      $timesWorking = 0;
      $totalTime = 0;
      foreach($someTasks as $eachTask){
        $thisStopDate = strtotime($eachTask['stopTime']);
        $thisStartDate = strtotime($eachTask['startTime']);
        $diff = $thisStopDate - $thisStartDate;
        $totalTime += $diff;
        $timesWorking++;
      }
      $totalTimeH = floor($totalTime/(60*60));
      $totalTimeM = floor(($totalTime-$totalTimeH*60*60)/60);
      $totalTimeS = floor($totalTime-$totalTimeH*60*60-$totalTimeM*60);
      echo '<tr>
        <td><a href="?action=detail&proj='.$eachTask['project'].'">'.$eachTask['project'].'</a></td>
        <td>
          <a href="?action=detail&proj='.$eachTask['project'].'&task='.$eachTask['task'].'">
            '.$eachTask['task'].'
          </a>
        </td>
        <td>'.$timesWorking.'</td>
        <td>'.$totalTimeH.'h '.$totalTimeM.'Mins '.$totalTimeS.'Secs</td>
        <td>
          <form method="post" action="index.php?action=startTask">
            <input type="hidden" name="taskName" value="'.$eachTask['task'].'" />
            <input type="hidden" name="project" value="'.$eachTask['project'].'" />
            <input type="submit" value="continue" />
          </form>
        </td>
      </tr>';
    }
    foreach ($tasks as $distinctTask) {
      $qr = 'select distinct(project) from tasks where userId = ? and task=?';
      $values = [$userId, $distinctTask['task']];
      $projects = fetchToArray($dbh, $qr, $values);
      if(count($projects) > 1){
        foreach($projects as $project) {
          $qr = 'select * from tasks where task = ? and project=? and userId=?';
          $value = [$distinctTask['task'], $project['project'], $userId];

          $eachTasks = fetchToArray($dbh, $qr, $value);
          constructRows($eachTasks);
        }
      }else{
        $qr = 'select * from tasks where task = ? and userId=?';
        $value = [$distinctTask['task'], $userId];
  
        $eachTasks = fetchToArray($dbh, $qr, $value);
        constructRows($eachTasks);
      }
    }
    echo '</tbody></table>
    ';
    
    if($action === 'detail') {
      $proj = $_GET['proj'];
      $task = isset($_GET['task']) ? $_GET['task'] : false;

      if($task) {
        $qr = 'select * from tasks where task=? and project=? and userId=?';
        $values = [$task, $proj, $userId];
      }else{
        $qr = 'select * from tasks where project=? and userId=?';
        $values = [$proj, $userId];
      }

      $detailedTasks = fetchToArray($dbh, $qr, $values);
      $totalTime = 0;
      $timesWorking = 0;
      echo '
      </br>
      <table>
        <thead>
          <tr>
            <td>Project</td>
            <td>Task</td>
            <td>Start</td>
            <td>Stop</td>
            <td>Time Working</td>
            <td>Description</td>
            <td>Edit</td>
          </tr>
        </thead>
        <tbody>';
      foreach($detailedTasks as $eachTask) {
        $thisStopDate = strtotime($eachTask['stopTime']);
        $thisStartDate = strtotime($eachTask['startTime']);
        $diff = $thisStopDate - $thisStartDate;
        $totalTime += $diff;
        $timesWorking++;
        $timeH = floor($diff/(60*60));
        $timeM = floor(($diff-$timeH*60*60)/60);
        $timeS = floor($diff-$timeH*60*60-$timeM*60);
        $timeStr = $timeH.'h '.$timeM.'Mins '.$timeS.'Secs';
        
        echo '
        <tr>
          <td>'.$eachTask['project'].'</td>
          <td>'.$eachTask['task'].'</td>
          <td>'.$eachTask['startTime'].'</td>
          <td>'.$eachTask['stopTime'].'</td>
          <td>'.$timeStr.'</td>
          <td>'.$eachTask['description'].'</td>
          <td><a href="?action=editTask&id='.$eachTask['id'].'">Edit</a></td>
        </tr>
        ';
      }
      $totalTimeH = floor($totalTime/(60*60));
      $totalTimeM = floor(($totalTime-$totalTimeH*60*60)/60);
      $totalTimeS = floor($totalTime-$totalTimeH*60*60-$totalTimeM*60);
      $totalTimeStr = $totalTimeH.'h '.$totalTimeM.'Mins '.$totalTimeS.'Secs';
      echo '
        <tr>
          <td colspan="3">Times Working: <b>'.$timesWorking.'</b></td>
          <td colspan="3">Total Time:<b>'.$totalTimeStr.'</b></td>
        </tr>
      </tbody></table>';
    }
    if($action === 'editTask') {
      $taskId = $_GET['id'];

      $qr = 'select * from tasks where id=?';
      $value = [$taskId];
      $task = fetchAssoc($dbh, $qr, $value);
      echo '
      <form method="post" action="?action=updateTask">
        <input type="hidden" name="taskId" value="'.$taskId.'"/>
        <label for="editingProject">
          Project
          <input type="text" name="project" id="editingProject" value="'.$task['project'].'" />
        </label>
        </br>
        <label for="editingTask">
          Task
          <input type="text" name="task" id="editingTask" value="'.$task['task'].'" />
        </label>
        </br>
        <label for="startTime">
          Start Time:
          <input type="text" name="startTime" id="startTime" value="'.$task['startTime'].'" />
        </label>
        </br>
        <label for="stopTime">
          Stop Time
          <input type="text" name="stopTime" id="stopTime" value="'.$task['stopTime'].'" />
        </label>
        </br>
        <label for="description">
          Description:</br>
          <textarea cols="50" rows="8" name="description" id="description">
          '.$task['description'].'
          </textarea>
        </label>
        </br>
        <input type="submit" value="Edit Task" />
      </form>';
    }
  }
} else{ //has opened.. continue or finish..
  $startTime = $openedTask['startTime'];
  $temp = explode(' ', $startTime);
  $tempDate = explode('-', $temp[0]);
  $shownedStartTime = $tempDate[2].'/'.$tempDate[1].'/'.$tempDate[0].' '.$temp[1]; 
  echo '
  <div id="divTimer">';
  if($openedTask['task']){
    echo '
      Task: '.$openedTask['task'].'
      </br>
    ';
  }
  if($openedTask['project']){
    echo '
      Project: '.$openedTask['project'].'
      </br>
    ';
  } 
  echo '
    <span>Started Time: '.$shownedStartTime.'</span>
    </br>
    <span id="stopTime"></span>
    <span id="strTimer">Total Time: <span id="timer">0h 0Min 00Sec</span></span>
    </br>
    <button type="button" id="stopBut" onclick="stopTaskNow('.$openedTask['id'].')">STOP RIGHT NOW!</button>
    
    <div id="choosingStopTime">
      <b>Or choose the time that you stopped:</b>
      </br>
      Date: <input type="date" name="cStoppedDate" id="cStoppedDate" value="'.date('Y-m-d').'" />
      </br>
      Hour: <input type="text" size="2" style="max-width: 40px" name="cStoppedHour" id="cStoppedHour" value="'.date('G').'"/>h
      <input type="text" style="max-width: 40px" size="2" name="cStoppedMin" id="cStoppedMin" value="'.date('i').'"/>Min 
      <input type="text" size="2" style="max-width: 40px" name="cStoppedSec" id="cStoppedSec" value="'.date('s').'"/>Sec
      <button type="button" onclick="stopTaskChosenTime('.$openedTask['id'].')">STOP WITH THIS CHOSEN TIME</button>

    </div>
    <div id="divDescription" style="display: none;">
    <label for="description">
      Description: </br>
      <textarea cols="50" rows="8" name="description" id="description">
      </textarea>
      <button type="button" onclick="sendDesc('.$openedTask['id'].')">Send Description</button>
    </label>
    </div>
    
  </div>
  <script>
  initTimer("'.$startTime.'");
  </script>
  
  ';
}

