<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	// Your database details might be different
	$mysqli = mysqli_connect("localhost", "root", "", "dbUser");

	$email = isset($_POST["email"]) ? $_POST["email"] : false;
	$pass = isset($_POST["pass"]) ? $_POST["pass"] : false;

	// $image = isset($_POST["picToUpload"]) ? $_POST["picToUpload"] : false;
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 3</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Tshegofatso Sithole">
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
				
					echo 	"<form enctype='multipart/form-data' method='post' action='login.php'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload[]' multiple='multiple' id='picToUpload' /><br/>
									<input type='hidden' id='loginEmail' value=".$row['email']." class='form-control' name='email'>
									<input type='hidden' id='loginPass' value=".$row['password']." class='form-control' name='pass'>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";

						  	// echo "image: ".$image;

					$id = $row["user_id"];
					if(isset($_FILES["picToUpload"]))
					{
						$uploadFile= $_FILES["picToUpload"];
						for($i=0; $i<count($uploadFile["name"]); $i++)
						{
							if($uploadFile["type"][$i] == "image/jpeg"&&$uploadFile["size"][$i] <1000000)
							{
								if($uploadFile["error"][$i] > 0)
								{
									// echo "Error:" .$uploadFile["error"][$i] . "<br/>";
								}
								else 
								{
									move_uploaded_file($uploadFile["tmp_name"][$i],"gallery/" .$uploadFile["name"][$i]);

									$imagename = $uploadFile["name"][$i];
									$exists = FALSE;

									$query = "SELECT * FROM tbgallery WHERE user_id = '$id'";
									$queue = $mysqli->query($query);
									
									while($pic = mysqli_fetch_array($queue))
									{
										if($pic["filename"] == $imagename){
											$exists = TRUE;
										}
									}

									if(!$exists)
									{
										$query = "INSERT INTO tbgallery (user_id, filename) VALUES ('$id', '$imagename');";

										$que = mysqli_query($mysqli, $query) == TRUE;
									}
								}
							}
							else
							{
								// echo "Invalid file";
							}
						}
					}
					?>
					<div class="container">
						<h2>Image Gallery</h2>
						<div class="row imageGallery">
							<?php
								$query = "SELECT * FROM tbgallery WHERE user_id = '$id'";
								$queue = $mysqli->query($query);
								
								while($pic = mysqli_fetch_array($queue))
								{
									echo '<div class="col-3" style="background-image: url(gallery/'.$pic["filename"].')"></div>';
								}
							?>
						</div>
					</div>
					<?php
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