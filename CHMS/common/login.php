<?php
ob_start();
include("session.php");
include("connection.php");
include("header-content.php");
require_once("functions.php");

if(isset($_SESSION['User_ID'])){
	$newLocation = "/chms/common/profile.php";
	redirect_to($newLocation);
	exit();
}

if (isset($_POST['submit'])){

    $User_Name = $_POST['User_Name'];
	$Password = $_POST['Password'];

            $sql = "SELECT ID, User_Name , Role, Password
            		FROM user_details
            		WHERE User_Name = '$User_Name'";
            $result_set = mysqli_query($conn, $sql);

            confirm_query($result_set);

            if (mysqli_num_rows($result_set) == 1) {
                // username authenticated
                // and only 1 match
                $found_user = mysqli_fetch_array($result_set);


				$stored_hashed_password = $found_user['Password'];

				if(password_verify($Password, $stored_hashed_password)){
				    // Passwords match, login successful
				    $_SESSION['User_ID'] = $found_user['ID'];
	                $_SESSION['User_Name'] = $found_user['User_Name'];
	                $_SESSION['Role'] = $found_user['Role'];

				    redirect_to("/chms/common/profile.php");

				} else {
				    echo "<script type=\"text/javascript\">window.alert('Username & password combination is incorrect.!');window.location.href = '/chms/common/login.php';</script>";
				}
                

            } else {
            	echo "<script type=\"text/javascript\">window.alert('Username is incorrect.!');window.location.href = '/chms/common/login.php';</script>";
            }

    } else { // Form has not been submitted.
        if (isset($_GET['logout']) && $_GET['logout'] == 1) {
            $message = "You are now logged out.";
        }
        $User_Name = "";
        $Password = "";
    }
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - CHMS</title>
</head>
<body>
	<form action="" method="POST">
		<table align="center">
			<br>
			<caption><div id="joy_congratulation">Log In Now</div></caption>
			<br>
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
				<td colspan="2" align="center"><input type="submit" name="submit" value="Log In"></td>
			</tr>
		</table>
	</form>
</body>
</html>