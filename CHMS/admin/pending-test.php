<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];

if(isset($_POST['approve'])){
	$T_ID = $_POST['T_ID'];

	$sql_update_data = "UPDATE test
						SET Status = 1
						WHERE ID = $T_ID";

	if (mysqli_query($conn, $sql_update_data)){
				echo "<script type=\"text/javascript\">window.alert('Test Approved Successfully..');window.location.href = '/chms/admin/pending-test.php';</script>";
			} 
			else{
				echo "Error: " . $sql . "" . mysqli_error($conn);
	 		}
	 		mysqli_close($conn);
}



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
		<th>
			<div id="joy_congratulation" align="center">
				Action
			</div>
		</th>
	</tr>';


$sql_retrive_test ="SELECT ID, Name, Price
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
				<td>
					<div id="joy_warning" align="center">
						<form action="" onsubmit="return showConfirmation()" method="POST" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="T_ID" name="T_ID" value="'.$row['ID'].'">
							<input type="submit" name="approve" value="Make Approve">
						</form>
					</div>
				</td>
			</tr>';
	}
}
else{
	echo '
			<tr><td colspan="3" align="center">
				<div id="joy_warning">
					No Pending Review
				</div>
			</td></tr>';
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
    <title>Pending Test</title>
</head>
<body>
      <script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to approve this review?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>