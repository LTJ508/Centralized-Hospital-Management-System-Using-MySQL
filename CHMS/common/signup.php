<?php
ob_start();
include("connection.php");
include("header-content.php");
include("functions.php");

if(isset($_SESSION['User_ID'])){
	$newLocation = "/chms/common/profile.php";
	redirect_to($newLocation);
	exit();
}


if(isset($_POST['submit'])){	
	 $User_Name = $_POST['User_Name'];
	 $Password = $_POST['Password'];
	 $Role = $_POST['Role'];
	 $Phone_Number= $_POST['Phone_Number'];

	 if($Role == "Patient"){
	 	$Status = 1;
	 }
	 else{
	 	$Status = 0;
	 }
	 

	 $query = mysqli_query($conn, "SELECT * FROM user_details WHERE User_Name = '$User_Name'") or die("Failed to query database ".mysqli_error($conn));
	 $count = mysqli_fetch_row($query);

	if ($count > 0) {
  		echo "<script type=\"text/javascript\">window.alert('Username Already Exists!');window.location.href = '/chms/common/signup.php';</script>";
	}
	else{
		$query = mysqli_query($conn, "SELECT * FROM user_details WHERE Phone_Number = '$Phone_Number'") or die("Failed to query database ".mysqli_error($conn));
		$count = mysqli_fetch_row($query);
		if ($count > 0) {
  			echo "<script type=\"text/javascript\">window.alert('Phone Number Already Exists!');window.location.href = '/chms/common/signup.php';</script>";
		}
		else{
			

			$password_hash = password_hash($Password, PASSWORD_BCRYPT);


			$sql_query = "INSERT INTO user_details(User_Name, Password, Role, Phone_Number, Email, Address, Photo, Status)
	 		VALUES ('$User_Name','$password_hash', '$Role','$Phone_Number', NULL, NULL, NULL, $Status)";

	 
			if (mysqli_query($conn, $sql_query)){
				echo "<script type=\"text/javascript\">window.alert('You have successfully created an account! Click OK to Login now!');window.location.href = '/chms/common/login.php';</script>";
			} 
			else{
				echo "Error: " . $sql . "" . mysqli_error($conn);
	 		}
	 		mysqli_close($conn);
		}
	}
}
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign Up - CHMS</title>
</head>
<body>
	<form action="" method="POST">
		<table align="center">
			<br>
			<caption><div id="joy_congratulation">Sign Up Now</div></caption>
			<br>
			<tr>
				<td ><label for="Role">I am </label></td>
				<td>
					<select id="Role" name="Role" required>
						<option value="Patient">Patient</option>
						<option value="Doctor">Doctor</option>
						<option value="Diagnostic Center">Diagnostic Center</option>
						<option value="Hospital">Hospital</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>
					<label for="User_Name">User Name: </label>
				</td>
				<td>
					<input type="text" name="User_Name" id="User_Name" placeholder="Enter User Name" required>
				</td>
			</tr>
			<tr>
				<td><label for="Password">Password: </label></td>
				<td><input type="password" id="Password" name="Password" placeholder="**********" required></td>
			</tr>
			<tr>
				<td>
					<label for="Phone_Number">Phone Number: </label>
				</td>
				<td>
					<input type="text" name="Phone_Number" id="Phone_Number" placeholder="+8801XXXXXXXXX" value="+8801" required>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><input type="submit" name="submit" value="Submit"></td>
			</tr>
		</table>
	</form>

</body>
</html>