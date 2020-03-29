<?php
session_start();
if($_SESSION['login']==FALSE){
	header("Location: index.php");
}
$name = $_POST['Product-Name'];
$height = $_POST['Height'];
$width = $_POST['Width'];
$breadth = $_POST['Breadth'];
$weight = $_POST['Weight'];
$type = $_POST['Type'];
if(strlen($name) == 0 || strlen($height) == 0 || strlen($width) == 0 || strlen($breadth) == 0 || strlen($weight) == 0 || strlen($type) == 0 ){	//its possible qr code contains other data
		echo "<script>window.location.href= 'alteration.html';</script>";	//so redirect to invalid page
	}
$data = array("Product-Name" => $name, "Height" => $height, "Width" => $width, "Breadth" => $breadth, "Weight" => $weight, "Type" => $type);
$data_string = json_encode($data); 
$getUrl = "http://localhost:7071/api/register";
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
curl_close($ch);
$json = $result;
$obj = json_decode($json);
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
	<style>
		
		div {
		  border-radius: 5px;
		  background-color: #f2f2f2;
		  padding: 20px;
		}
	</style>
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
	   		
			<?php
				if($obj->{'Status'} == "Registered Successfully"){
					include 'media/lib/full/qrlib.php'; 
					$path = 'media/qrs/'; 
					$file = $path.uniqid().".png"; 
					$ecc = 'L'; 
					$pixel_Size = 5; 
					$frame_Size = 5; 
					$text = $obj->{'id'}; 
					QRcode::png($text, $file, $ecc, $pixel_Size, $frame_size); 
					echo "<div style=\"display: inline-block;\"><img src='".$file."'><br><br><button onclick='print()'>Print</button></div>";
					echo "<h2 style=\"color: green;\">".$obj->{'Status'}."</h2>"; 
				}
				else{
					echo "<h2 style=\"color: red;\">"."Registration Failed"."</h2>";
				}
			?>	
		</center>
	</div>
</body>
</html>