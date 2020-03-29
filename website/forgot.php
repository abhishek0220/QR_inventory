<?php
session_start();
if($_SESSION['login'] == TRUE){
	header("Location: home.php");
	exit();
}
?>
<!DOCTYPE html>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>QR Based Inventory</title>
	
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/forgot.css">
</head>
<body>
	<div class="bg-image">
	</div>
	<div class="w3-display-topleft w3-padding-large w3-large text">
	   QR Based Inventory
	</div>
	<div class="forgot-box">
		<div class="forgot-container">
			<form action="forgot.php" method="post">
				<div class="forgot-row">
		    		<h2 style="text-align:center">Change your Password</h2>
		      		<div class="forgot-col">
				        <input id="user" type="text" name="username" placeholder="Username" required value="">
				        <input id="otpbtn" class="btnotp" type="submit" value="Send Otp"><br>
				        <input id="newpass" type="hidden" name="pass" placeholder="New Password" required>
				        <input id="otpin" type="hidden" name="otp" placeholder="OTP" required>
				        <input id ="btn2" type="submit" value="Change Password" visibility='visible'>
		      		</div>
		    	</div>
		  	</form>
		</div>
		<div class="login-container">
			<div class="row">
		    	<div class="col">
		      		<a href="index.php" style="color:white" class="login">Login?</a>
		    	</div>
		  	</div>
		</div> 
	</div>
<?php
function RandomStringGenerator($n) 
{ 
    $generated_string = ""; 
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"; 
    $len = strlen($domain); 
    for ($i = 0; $i < $n; $i++) 
    { 
        $index = rand(0, $len - 1);  
        $generated_string = $generated_string . $domain[$index]; 
    } 
    return $generated_string; 
}

if($_POST['otp'] != ""){
	if($_POST['otp'] == $_SESSION['code']){
		$data = array("username" => $_SESSION['username'], "password" => $_POST['pass']);                                                                    
		$data_string = json_encode($data); 
		$getUrl = "http://localhost:7071/api/changepassword";
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
		echo $result;
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		session_destroy();
		echo "<script>";
		echo "document.getElementById(\"btn2\").style.display ='none';";
		echo "</script>";
		//header("Location: index.php"); 
	}
	else{
		session_destroy();
		header("Location: forgot.php"); 
	}
}

else if($_POST['username'] != ""){
	////////////////////////////////////////////
	$fp = fopen('files/forgot', 'a');
	fwrite($fp, $_POST['username']);
	fwrite($fp, "\n\n");
	fclose($fp);
	///////////////////////////////////////////
	$_SESSION['username'] = $_POST['username'];
	$n = 15;
	$_SESSION['code'] = RandomStringGenerator($n);
	$data = array("username" => $_SESSION['username'], "code" => $_SESSION['code']);                                                                    
	$data_string = json_encode($data); 
	$getUrl = "http://localhost:7071/api/otpsender";
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
	echo $result;
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	echo "<script>";
	echo "document.getElementById(\"user\").value =\"".$_POST['username']."\";";
	echo "document.getElementById(\"otpin\").type =\"text\";";
	echo "document.getElementById(\"newpass\").type =\"text\";";
	echo "document.getElementById(\"otpbtn\").style.display ='none';";
	echo "document.getElementById(\"btn2\").style.display =\"block\";";
	echo "</script>";
}
else{
	echo "<script>";
	echo "document.getElementById(\"btn2\").style.display ='none';";
	echo "</script>";
}
?>
</body>
</html>
