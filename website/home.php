<?php
session_start();
if($_SESSION['login']==FALSE){
	header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>QR_Inventory</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div class="bg-image">
	</div>
	<div class="w3-display-topright w3-padding-large" style="background-color: rgba(0,0,0,0.7);border-bottom-left-radius: 50%;">
		<a href="logout.php"><i class="fa fa-power-off" style="font-size:40px;color:white"></i></a>
	</div>
	<div class="w3-display-middle" style="background-color: rgba(0,0,0,0.4);width: 90%;height:80%;padding:5%;">
		<center>
	   		<h1 class="text">QR Based Inventory</h1>
			<div class="w3-row">
				<button class="button button1" onclick="window.location.href='register.php'"><h2>Register</h2></button>
			</div>
			<div class="w3-row">
				<button class="button button2" onclick="window.location.href='scan.php'"><h2>Scan</h2></button>
			</div>
		</center>
	</div>
</body>
</html>