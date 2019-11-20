<!DOCTYPE html>
<html lang="pt-br">
<head>
	<title>Timer</title>
	<link rel="stylesheet" type="text/css" href="main.css" />
	<link rel="shortcut icon" href="favicon.ico"/>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<?php require 'phpConfig.php' ?>
</head>
<body>
	<?php
		require 'header.php';
		if(!isset($_COOKIE['name@timer'])) {
			require 'signUpOrLogin.php';
		} else{
			$name = $_COOKIE['name@timer'];
			$userId = $_COOKIE['id@timer'];
			echo '<div style="display: flex; flex-direction:row;">';
			echo 'Hello '.$name;
			echo '<form action="logout.php">
			<input type="submit" value="LOGOUT"/>
			</form>';
			echo '</div>';
			require 'timer.php';
		}
	?>
</body>
</html>
