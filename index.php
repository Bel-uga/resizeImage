<?
session_start();
?>
<html >
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>Resize image</title>		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<script type="text/javascript" src="./js/jquery-3.2.1.js"></script>	
		<script type="text/javascript" src="./js/main.js"></script>	
		<link rel="stylesheet" href="./css/bootstrap.css"  type="text/css" media="all">	
		<link rel="stylesheet" href="./css/styles.css"  type="text/css" media="all">	
	</head>
<body>
<div class="container pt-5">
	<div class="card">
		<div class="card-header text-center">
			<h2>Resize image</h2>
		</div>
		<div class="card-body">		
			<div class="row mt-3 justify-content-center">				
				<div class="col-md-4">		
					<label for="image" class="col-form-label ">Image</label>				
					<input type="file" class="form-control" id="image" name="image"/>
				</div>
			</div>	
			<div class="row mt-3 justify-content-center">	
				
				<div class="col-md-3">	
					<label for="width" class=" col-form-label">Width</label>
					<input id="width" name="width" class="form-control" type="number" value="512">					
				</div>
				
				<div class="col-md-3">	
					<label for="height" class=" col-form-label">Height</label>
					<input id="height" name="height" class="form-control" type="number" value="512">					
				</div>
			</div>
			
			<div class="row mt-3 justify-content-center">
				<input type="button" class="btn mt-3 btn-primary mr-2" id="addBtn"  value="Upload image" />				
			</div>
		</div>
	</div>
</div>




</body>
</html >