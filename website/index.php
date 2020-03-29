<?php
session_start();
if($_SESSION['login'] == TRUE){
	header("Location: home.php");
	exit();
}
$vk = $_SERVER['HTTP_USER_AGENT'];
$condition = FALSE;
if($_POST['username'] && $_POST['password']){
	$condition = TRUE;
	$data = array("username" => $_POST['username'], "password" => $_POST['password']);                                                                   
	$data_string = json_encode($data); 
	$getUrl = "http://localhost:7071/api/login";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
	curl_setopt ($ch, CURLOPT_CAINFO, dirname(__FILE__)."/ssl.txt");                                                                     
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
	    'Content-Type: application/json',                                                                                
	    'Content-Length: ' . strlen($data_string))                                                                       
	);
	curl_setopt($ch, CURLOPT_URL, $getUrl);
	curl_setopt($ch, CURLOPT_TIMEOUT, 80);                                
	$result = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if($http_code ==200){
	    $_SESSION['username'] = $_POST['username'];
		$_SESSION['login'] = TRUE;
		header("Location: home.php");
		exit();
	}
	curl_close($ch);
}
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>QR_Inventory</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/login.css">
</head>
<body>
	<div class="bg-image">
	</div>
	<div class="w3-display-topleft w3-padding-large w3-large text">
	   QR Based Inventory
	</div>
	<div class="login-box">
		<div class="login-container">
			<form action="index.php" method="post">
				<div class="login-row">
			    	<h2 style="text-align:center">Login to QR Based Inventory</h2>
			    	<div class="login-col">
				        <input type="text" name="username" placeholder="Username" required>
				        <input type="password" name="password" placeholder="Password" required>
				        <input id="login-btn" type="submit" value="Login">
			        	<p id="wrong" style="color: red;text-align: right;padding: 0;"><b>*Wrong ID or Password</b></p>
			      	</div>
			    </div>
			</form>
		</div>
		<div class="forgot-container">
			<div class="row">
			    <div class="col">
			    	<a href="forgot.php" style="color:white" class="forgot">Forgot Password?</a>
			    </div>
			</div>
		</div> 
	</div>
<?php
	if($condition){
		echo "<script>";
		echo "document.getElementById(\"wrong\").style.display ='block';";
		echo "</script>";
	}
	else{
		echo "<script>";
		echo "document.getElementById(\"wrong\").style.display ='none';";
		echo "</script>";
	}
?>
</body>
</html>