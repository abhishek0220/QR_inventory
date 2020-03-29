<?php
session_start();
if($_SESSION['login']==FALSE){
	header("Location: index.php");
}
$ID = $_POST['scaned'];
if(strlen($ID) == 0){
	echo "<script>window.location.href= 'alteration.html';</script>";
}
else if(strlen($ID) != 36 ){
	echo "<script>window.location.href= 'invalid_qr.html';</script>";
}
$data = array("Product-ID" => $ID);
$data_string = json_encode($data); 
$getUrl = "http://localhost:7071/api/product";
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
$order = $obj->{'order'};
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
		table {
		  border-collapse: collapse;
		  width: 600px;
		}

		th, td {
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even){background-color: #f2f2f2}
		tr:nth-child(odd){background-color: #999966}

		th {
		  background-color: #4CAF50;
		  color: white;
		}
		@media screen and (max-width: 720px) {
		  table{
		    width: 300px;
		  }
		}
	</style>
</head>
<body>
	<div class="bg-image">
	</div>
	<div class="w3-display-topright w3-padding-large" style="background-color: rgba(0,0,0,0.7);border-bottom-left-radius: 50%;">
		<a href="logout.php"><i class="fa fa-power-off" style="font-size:40px;color:white"></i></a>
	</div>
	<div class="w3-display-topmiddle" style="padding-top: 5%;">
		<center>
	   		<h1 class="text" >QR Based Inventory</h1><br>
		</center>
	
		<table style="font-size: 25px;">
			<tr>
		    	<th>Attribute</th>
		    	<th>Value</th>
		  	</tr>
		  	<tr>
		   		<td></td>
		    	<td></td>
			</tr>
			<?php
			foreach ($order as $value){
				echo "<tr>";
				echo "<td><b>".$value."</b></td><td>".$obj->{$value}."</td";
				echo "</tr>";
			}
			?>
		</table>
	</div>
</body>
</html>