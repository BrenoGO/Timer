let str = '0h 0Min 00Sec'
var interval;
function initTimer(time){
  localStorage.setItem("currentStartTime@timer", time)
  interval = setInterval(setDiff, 1000);
}

function setDiff(){
  
  const localTime = localStorage.getItem('currentStartTime@timer');
  const [Ymd, hms] = localTime.split(' ');
  const [year, month, day] = Ymd.split('-');
  const [hour, minutes, seconds] = hms.split(':');
  const startDate = new Date(year, month-1, day, hour, minutes, seconds);
  
  const now = new Date();

  // console.log('now:', now);
  // console.log('startDate:',startDate);
  const dif = now.getTime() - startDate.getTime();
  const difHours = Math.floor(dif / (1000 * 60 * 60));
  const difMinutes = Math.floor((dif - difHours*1000*60*60)/ (1000*60) );
  const difSeconds = Math.floor((dif - difHours*1000*60*60 - difMinutes*1000*60) / 1000);
  str = `${difHours}h ${difMinutes}Min ${difSeconds}Sec`
  
  document.getElementById('timer').innerHTML=str;
  
}

function stopTaskNow(taskId) {
  const now = new Date();
  let nowDay = now.getDate(),
    nowMonth = now.getMonth()+1,
    nowYear = now.getFullYear(),
    nowHour = now.getHours(),
    nowMinutes = now.getMinutes(),
    nowSeconds = now.getSeconds();
  
  if(String(nowMonth).length === 1) nowMonth = '0'+nowMonth;
  if(String(nowDay).length === 1) nowDay = '0'+nowDay;
  if(String(nowHour).length === 1) nowHour = '0'+nowHour;
  if(String(nowMinutes).length === 1) nowMinutes = '0'+nowMinutes;
  if(String(nowSeconds).length === 1) nowSeconds = '0'+nowSeconds;

  const stopTime = nowYear+'-'+nowMonth+'-'+nowDay+' '+nowHour+':'+nowMinutes+':'+nowSeconds;
  const shownedStopTime = nowDay+'/'+nowMonth+'/'+nowYear+' '+nowHour+':'+nowMinutes+':'+nowSeconds;
  stopTask(taskId, stopTime, shownedStopTime);
}

function stopTaskChosenTime(taskId) {
  const inputDate = document.getElementById('cStoppedDate').value;
  
  const [year, month, day] = inputDate.split('-');
  const hour = document.getElementById('cStoppedHour').value,
    minutes = document.getElementById('cStoppedMin').value,
    seconds = document.getElementById('cStoppedSec').value;
  
  const stopTime = year+'-'+month+'-'+day+' '+hour+':'+minutes+':'+seconds;
  const shownedStopTime = day+'/'+month+'/'+year+' '+hour+':'+minutes+':'+seconds;
  document.getElementById('strTimer').style.display = 'none';
  stopTask(taskId, stopTime, shownedStopTime);
}

function stopTask(taskId, stopTime, shownedStopTime) {
  clearInterval(interval);
  document.getElementById('stopTime').innerHTML='Stopped Time: '+shownedStopTime+'</br>';
  document.getElementById('divDescription').style.display = 'inline';
  document.getElementById('stopBut').style.display = 'none';
  document.getElementById('choosingStopTime').style.display = 'none';
  fetch(`http://brenogo.tech/timer/stopTask.php?id=${taskId}&stoptime=${stopTime}`)
    .then(resp => resp.json())
    .then(resp => {
      console.log(resp);
    })
    .catch(error => {
      console.log('errorrr');
      console.log(error);
    });
}

function sendDesc(taskId) {
  const description = document.getElementById('description').value;
  fetch('http://brenogo.tech/timer/addDesc.php',
  {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({description, taskId})
  })
    .then(resp => resp.json())
    .then(resp => { 
      console.log(resp);
      window.location.href='index.php';
    })
    .catch(error => { 
      console.log(error);
      window.location.href='index.php';
    });
}