<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Name Surname">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form action='login.php' method='POST' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
									<input type='hidden' name='hiddenPass' value=''".$row["password"]."''/>
									<input type='hidden' name = 'hiddenEmail' value=''".$row["email"]."''/>
								</div>
							  </form>";
							
							if(isset($_POST["submit"])){
								$target_dir = "uploads/";
								$uploadFile = $_FILES["picToUpload"];
								$target_file = $target_dir . basename($uploadFile["name"]);
								$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
								
								if(($uploadFile["type"] == "image/jpeg" || $uploadFile["type"] == "image/jpeg") && $uploadFile["size"] < 1000){
									if($uploadFile["error"] > 0){
										echo "Error: ".$uploadFile["error"]."<br/>";
									}
									else{
										$userID = "SELECT user_id FROM tblusers WHERE email='".$email."'";
										$query = "INSERT INTO tbgallery (user_id, filename) VALUES ('".$userID."', '".$uploadFile['name']."')";
										$resp = $mysqli->query($query);
										if($resp == TRUE){
											echo "Picture uploaded successfully";
										}
									}
								}
								echo "<h2>Gallery</h2>
									<div class='row imageGallery'>";
									$selectImage = "SELECT filename from tbgallery where user_id =".$row["user_id"];
									$respp = $mysqli->query($selectImage);
									if($respp->num_rows > 0){
										while($row = mysqli_fetch_array($respp)){
											$file_name = '"gallery/'.$row["filename"].'"';
											echo "<div class='col-3' style='background-image: url(".$file_name.")'>
											</div>"; 
										}
									}
									echo "</div>";
							}
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>