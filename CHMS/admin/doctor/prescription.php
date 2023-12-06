<?php
ob_start();
include("../../common/header-content.php");
include("../../common/functions.php");
include("restrict-access.php");

if(isset($_GET['D_ID'])){
	$D_ID = $_GET['D_ID'];
	$sql_info = "SELECT d.Title, d.Fname, d.Lname, u.Photo
		FROM doctor d, user_details u
		WHERE d.ID = u.ID AND d.ID = $D_ID";

		$result_info = mysqli_query($conn, $sql_info);
		$found_user = mysqli_fetch_array($result_info);

		$Title = $found_user['Title'];
		$Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];
        $Photo = $found_user['Photo'];

        echo '<table align="center" border="1">';

        if($Photo == ""){
			echo '<div align="center"><img src="/chms/doctor/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/doctor/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px"></div>';
		}

		echo '<div id="joy_congratulation" align="center">
			'.$Title .' Dr. '. $Fname . ' '. $Lname .'</div>';

		echo '
		<caption>
			<div id="joy_congratulation">
				All Prescriptions
			</div>
		</caption>
		<tr>
			<th>
				<div id="joy_warning" align="center">
					Prescription ID
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Problem
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Date
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Precribed for
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Action
				</div>
			</th>
		</tr>';

	$sql_all_prescription = "SELECT pr.ID, pr.Problem, pr.Date, p.Fname, p.Lname
								FROM prescriptions pr, patient p
								WHERE p.ID = pr.P_ID AND pr.D_ID = $D_ID
								ORDER BY Date DESC";

	$result_all_prescription = mysqli_query($conn, $sql_all_prescription);
	if (mysqli_num_rows($result_all_prescription) > 0){
		while ($row = mysqli_fetch_assoc($result_all_prescription)){
			echo '<tr>
					<td>
						<div id="joy_congratulation">
							'.$row['ID'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							'.$row['Problem'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							'.$row['Date'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							'.$row['Fname'].' '.$row['Lname'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							<form action="/chms/admin/doctor/prescriptions-details.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
								<input type="hidden" id="ID" name="ID" value="'.$row['ID'].'">
								<input type="submit" name="submit" value="View Details">
							</form>
						</div>
					</td>
				</tr>';
		}
	}
	else{
		echo '
		<tr>
			<td colspan="5" align="center">
				<div id="joy_warning" align="center">
					No Prescription Available
				</div>
			</td>
		</tr>';
	}

	echo '</table>';
	
}
else{
	redirect_to("/chms/admin/");
}






ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>All Prescription</title>
</head>
<body>

</body>
</html>