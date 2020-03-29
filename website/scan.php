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
	<!--for scanning-->
	<link rel="stylesheet" href="css/scan_style.css">
  	<script type="text/javascript" src="js/scan/adapter.min.js"></script>
  	<script type="text/javascript" src="js/scan/vue.min.js"></script>
  	<script type="text/javascript" src="js/scan/jquery.min.js"></script>
  	<script type="text/javascript" src="js/scan/instascan.min.js"></script>
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
	   		<div class = "w3-display-middle" style="margin-left: 1%;" id="app">
		    	<div class="sidebar">
		      		<section class="cameras">
		        		<h2>Cameras</h2>
		        		<ul>
				         	<li v-if="cameras.length === 0" class="empty">No cameras found</li>
				          	<li v-for="camera in cameras">
				            	<span v-if="camera.id == activeCameraId" :title="formatName(camera.name)" class="active">{{ formatName(camera.name) }}</span>
				            	<span v-if="camera.id != activeCameraId" :title="formatName(camera.name)">
				              		<a @click.stop="selectCamera(camera)">{{ formatName(camera.name) }}</a>
				            	</span>
				         	</li>
		        		</ul>
		      		</section>
		      		<section class="scans">
		        		<h2>Scans</h2>
				        <ul v-if="scans.length === 0">
				          <li class="empty">No scans yet</li>
				        </ul>
				        <transition-group name="scans" tag="ul">
				          <li v-for="scan in scans" :key="scan.date" :title="scan.content">{{ scan.content }}</li>
				        </transition-group>
		      		</section>
		    	</div>
			    <div class="preview-container">
			    	<video id="preview"></video>
			    </div>
			</div>
		</center>
	</div>
	<form id="data_capture" action="product.php" method="post">
		<input type="hidden" id="scan_id" name="scaned" value="none">
	</form>
	
  <script type="text/javascript" src="js/scan/app.js"></script>
</body>
</html>