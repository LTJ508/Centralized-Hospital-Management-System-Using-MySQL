<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];

if(isset($_POST['approve'])){
	$P_ID = $_POST['P_ID'];
	$Vaccine_Name = $_POST['Vaccine_Name'];
	$Doss_Number = $_POST['Doss_Number'];

	$sql_update_data = "UPDATE vaccination_logs
						SET Status = 1
						WHERE P_ID = $P_ID AND Vaccine_Name = '$Vaccine_Name' AND Doss_Number = '$Doss_Number'";

	if (mysqli_query($conn, $sql_update_data)){
				echo "<script type=\"text/javascript\">window.alert('Vaccine Info Approved Successfully..');window.location.href = '/chms/admin/pending-vaccine.php';</script>";
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
			All Pending Vaccination Info List
		</div>
	</caption>
	<tr>
		<th>
			<div id="joy_congratulation" align="center">
				Patient Name
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Vaccine Name
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Doss Number
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Date
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Action
			</div>
		</th>
	</tr>';


$sql_vaccine_logs = "SELECT p.ID, p.Fname, p.Lname, v.Vaccine_Name, v.Doss_Number, v.Date
                    FROM vaccination_logs v, patient p
                    WHERE p.ID = v.P_ID AND Status = FALSE
                    ORDER BY Date";

$result_vaccine_logs = mysqli_query($conn, $sql_vaccine_logs);
if (mysqli_num_rows($result_vaccine_logs) > 0){
	while ($row = mysqli_fetch_assoc($result_vaccine_logs)){
		echo '<tr>
				<td>
					<div id="joy_warning" align="center">
						'.$row['Fname'].' '.$row['Lname'].'
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						'.$row['Vaccine_Name'].'
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						'.$row['Doss_Number'].'
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						'.$row['Date'].'
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						<form action="" onsubmit="return showConfirmation()" method="POST" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="P_ID" name="P_ID" value="'.$row['ID'].'">
							<input type="hidden" id="Vaccine_Name" name="Vaccine_Name" value="'.$row['Vaccine_Name'].'">
							<input type="hidden" id="Doss_Number" name="Doss_Number" value="'.$row['Doss_Number'].'">
							<input type="submit" name="approve" value="Make Approve">
						</form>
					</div>
				</td>
			</tr>';
	}
}
else{
	echo '
			<tr><td colspan="5" align="center">
				<div id="joy_warning">
					No Pending Vaccination
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
    <title>Pending Vaccine</title>
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