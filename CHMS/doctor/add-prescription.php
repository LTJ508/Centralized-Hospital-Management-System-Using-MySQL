<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");
include("status-check.php");

$User_ID = $_SESSION['User_ID'];

if(isset($_POST['submit'])){
	$P_ID = $_POST['P_ID'];
	$Problem = $_POST['Problem'];
	$Suggestions = $_POST['Suggestions'];
	$DateTime = date("Y-m-d H:i:s");

	if($Suggestions == ""){
		$Suggestions = "N/A";
	}

	$sql_insert = "INSERT INTO prescriptions(Date, D_ID, P_ID, Problem, Suggestions)
					VALUES('$DateTime', $User_ID, $P_ID, '$Problem', '$Suggestions')";
	if (mysqli_query($conn, $sql_insert)){
				echo "<script type=\"text/javascript\">window.alert('Prescription submitted Successfully.. You can now add medicine and test');window.location.href = '/chms/doctor/add-medicine.php?P_ID=".$P_ID."&Date=".$DateTime."';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);


}
else{
		if(isset($_POST['add'])){
		$P_ID = $_POST['ID'];

		$sql_patient = "SELECT p.Fname, p.Lname, p.DOB, p.Gender, p.Blood, p.Weight, u.Photo
						FROM user_details u, patient p
						WHERE u.ID = p.ID AND u.ID = $P_ID";

		$result_patient = mysqli_query($conn, $sql_patient);

		$found_patient = mysqli_fetch_array($result_patient);

		$Fname = $found_patient['Fname'];
		$Lname = $found_patient['Lname'];
		$DOB  = $found_patient['DOB'];
		$Gender = $found_patient['Gender'];
		$Blood  = $found_patient['Blood'];
		$Weight = $found_patient['Weight'];
		$Photo = $found_patient['Photo'];


		echo'
		<table border="1" align="center">
			<caption>
				<div id="joy_congratulation">
					Patient Information & History
				</div>
			</caption>
		     <tr>
		     	<td colspan="4" align="center">
		     		<div style="display: flex;" align="center">';
						if($Photo == ""){
							echo '<img src="/chms/patient/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px">';
						}
						else{
							echo '<img src="/chms/patient/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px">';
						}
						echo '
						<div id="joy_congratulation">
							Name: '.$Fname .' '. $Lname .'<br>
							Age: '.calculateAge($DOB).' Years<br>
							Gender: '.$Gender.'<br>
							Blood: '.$Blood.'<br>
							Weight: '.$Weight.' KG
						</div>
					</div>						
		     	</td> 
		     </tr></table>

			<form action="" method="POST">
			<table align="center">
				<tr>
					<td>
						<div id="joy_congratulation">
							<label for="Problem">Problem:</label>
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							<textarea id="Problem" name="Problem" rows="2" cols="30" placeholder="Problem" required></textarea>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div id="joy_congratulation">
							<label for="Suggestions">Suggestions:</label>
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							<textarea id="Suggestions" name="Suggestions" rows="2" cols="30" placeholder="Suggestions"></textarea>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<div id="joy_congratulation">
							<input type="hidden" id="P_ID" name="P_ID" value="'.$P_ID.'">
							<input type="submit" name="submit" value="Submit">
						</div>
					</td>
				</tr>
			</table>
		</form>';
	}
	else{
		redirect_to("/chms/doctor/patient-view.php");
	}
}







ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Add New Prescription</title>
</head>
<body>

</body>
</html>

