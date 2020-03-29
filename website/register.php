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
	<style>
		input[type=text], select {
		  width: 100%;
		  padding: 12px 20px;
		  margin: 4px 0;
		  display: inline-block;
		  border: 1px solid #ccc;
		  border-radius: 4px;
		  box-sizing: border-box;
		}

		input[type=submit] {
		  width: 100%;
		  background-color: #4CAF50;
		  color: white;
		  padding: 14px 20px;
		  margin: 8px 0;
		  border: none;
		  border-radius: 4px;
		  cursor: pointer;
		}

		input[type=submit]:hover {
		  background-color: #45a049;
		}

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
	<div class="w3-display-topmiddle" style="background-color: rgba(0,0,0,0);width: 50%;">
		<center>
	   		<h1 class="text">QR Based Inventory</h1>
		</center>
		<div>
			<form action="create.php" method="post">
				<label for="pname">Product Name</label>
		    	<input type="text" id="pname" name="Product-Name" placeholder="Product name.." required>
		    	<label for="height">Height</label>
		    	<input type="text" id="height" name="Height" placeholder="Height.." required>
		    	<label for="width">Width</label>
		    	<input type="text" id="width" name="Width" placeholder="Width.." required>
		    	<label for="breadth">Breadth</label>
		    	<input type="text" id="breadth" name="Breadth" placeholder="Breadth.." required>
		    	<label for="weight">Weight</label>
		    	<input type="text" id="weight" name="Weight" placeholder="Weight.." required>
		    	<label for="type">Type</label>
		    	<select id="type" name="Type" required>
				    <option value="A">A</option>
				    <option value="B">B</option>
				    <option value="C">C</option>
			    </select>
		    <input type="submit" value="Submit">
		  </form>
		</div>
	</div>
</body>
</html>