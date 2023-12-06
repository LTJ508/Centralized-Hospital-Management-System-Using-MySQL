<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");
include("status-check.php");

$User_ID = $_SESSION['User_ID'];

if(isset($_POST['add'])){
	$Name = $_POST['Name'];
	$Price = $_POST['Price'];

	$sql_check = "SELECT *
					FROM test
					WHERE Name = '$Name'";
	$result_check = mysqli_query($conn, $sql_check);
	if (mysqli_num_rows($result_check) > 0){
		echo "<script type=\"text/javascript\">window.alert('This Test Already Exist!!!');window.location.href = '/chms/diagnostic-center/missing-test.php';</script>";
	}
	else{
		$sql_add = "INSERT INTO test(Name, Price, Status)
					VALUES('$Name', $Price, FALSE)";
		if (mysqli_query($conn, $sql_add)){
			echo "<script type=\"text/javascript\">window.alert('New Test Info Submitted for review..');window.location.href = '/chms/diagnostic-center/missing-test.php';</script>";
		} 
		else{
			echo "Error: " . $sql . "" . mysqli_error($conn);
 		}
 		mysqli_close($conn);
	}
}



$sql_info = "SELECT dc.Name, u.Photo
		FROM diagnostic_center dc, user_details u
		WHERE dc.ID = u.ID AND dc.ID = $User_ID";

		$result_info = mysqli_query($conn, $sql_info);
		$found_user = mysqli_fetch_array($result_info);

        $Name = $found_user['Name'];
        $Photo = $found_user['Photo'];

        echo '<table align="center" border="1">';

        if($Photo == ""){
			echo '<div align="center"><img src="/chms/diagnostic-center/img/null.jpg" alt="'.$Name.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/diagnostic-center/'.$Photo.'" alt="'.$Name.'" width="120px" height="120px"></div>';
		}

		echo '<div id="joy_congratulation" align="center">
		'. $Name . '</div>';

		echo '<form action="" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
		<tr>
			<td colspan="3">
				<div id="joy_congratulation" align="center">
					Add New Missing Test
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<input type="text" name="Name" placeholder="Test Name" required>
			</td>
			<td>
				<input type="number" name="Price" placeholder="Price">
			</td>
			<td>
				<input type="submit" name="add" value="Add">
			</td>
		</tr>
	</form>
	</table>';





echo '<table align="center" border="1">';
echo '
	<caption>
		<div id="joy_congratulation">
			All Pending Test List
		</div>
	</caption>
	<tr>
		<th>
			<div id="joy_congratulation" align="center">
				Test Name
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Price
			</div>
		</th>
	</tr>';


$sql_retrive_test ="SELECT Name, Price
					FROM test
					WHERE Status = FALSE
					ORDER BY Name";
$result_test_list = mysqli_query($conn, $sql_retrive_test);
if (mysqli_num_rows($result_test_list) > 0){
	while ($row = mysqli_fetch_assoc($result_test_list)){
		echo '<tr>
				<td>
					<div id="joy_warning" align="center">
						'.$row['Name'].'
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						'.$row['Price'].' TK
					</div>
				</td>
			</tr>';
	}
}
echo'
</table>';




ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add Missing Test</title>
</head>
<body>
	<script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to add this test?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>
