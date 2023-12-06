<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");
include("status-check.php");

$User_ID = $_SESSION['User_ID'];


if(isset($_POST['add_medicine'])){
	$Pr_ID = $_POST['Pr_ID'];
	$M_ID = $_POST['M_ID'];
	$Morning = $_POST['Morning'];
	$Noon = $_POST['Noon'];
	$Night = $_POST['Night'];
	$M_After_Meal = $_POST['M_After_Meal'];
	$N_After_Meal = $_POST['N_After_Meal'];
	$Nt_After_Meal = $_POST['Nt_After_Meal'];

	if($Morning == ""){
		$Morning = 0;
	}
	if($Noon == ""){
		$Noon = 0;
	}
	if($Night == ""){
		$Night = 0;
	}
	if($M_After_Meal == ""){
		$M_After_Meal = 0;
	}
	if($N_After_Meal == ""){
		$N_After_Meal = 0;
	}
	if($Nt_After_Meal == ""){
		$Nt_After_Meal = 0;
	}

	$sql_check = "SELECT *
					FROM prescribed_in
					WHERE Pr_ID = $Pr_ID AND M_ID = $M_ID";
	if(mysqli_num_rows(mysqli_query($conn, $sql_check)) > 0){
		echo "<script type=\"text/javascript\">window.alert('Medicine Already Added!!!');window.location.href = '/chms/doctor/add-medicine.php?Pr_ID=".$Pr_ID."';</script>";
	}
	else{
		$sql_insert = "INSERT INTO prescribed_in(Pr_ID, M_ID, Morning, M_After_Meal, Noon, N_After_Meal, Night, Nt_After_Meal)
						VALUES($Pr_ID, $M_ID, $Morning, $M_After_Meal, $Noon, $N_After_Meal, $Night, $Nt_After_Meal)";

		if (mysqli_query($conn, $sql_insert)){
				redirect_to("/chms/doctor/add-medicine.php?Pr_ID=".$Pr_ID."");
		} 
		else{
			echo "Error: " . $sql . "" . mysqli_error($conn);
 		}
 		mysqli_close($conn);
	}
}

else if(isset($_POST['add_test'])){
	$Pr_ID = $_POST['Pr_ID'];
	$T_ID = $_POST['T_ID'];

	$sql_check = "SELECT *
					FROM test_log
					WHERE Prescription_ID = $Pr_ID AND T_ID = $T_ID";
	if(mysqli_num_rows(mysqli_query($conn, $sql_check)) > 0){
		echo "<script type=\"text/javascript\">window.alert('Test Already Added!!!');window.location.href = '/chms/doctor/add-medicine.php?Pr_ID=".$Pr_ID."';</script>";
	}
	else{

		$sql_P_ID = "SELECT P_ID
					FROM prescriptions
					WHERE ID = $Pr_ID";


		$result_P_ID = mysqli_query($conn, $sql_P_ID);
		$found_P_ID = mysqli_fetch_array($result_P_ID);

	    $P_ID = $found_P_ID['P_ID'];

		$sql_insert = "INSERT INTO test_log(T_ID, P_ID, Prescription_ID)
						VALUES($T_ID, $P_ID, $Pr_ID)";

		if (mysqli_query($conn, $sql_insert)){
				redirect_to("/chms/doctor/add-medicine.php?Pr_ID=".$Pr_ID."");
		} 
		else{
			echo "Error: " . $sql . "" . mysqli_error($conn);
 		}
 		mysqli_close($conn);
	}
}

else{

if((isset($_GET['P_ID']) && isset($_GET['Date'])) || isset($_GET['Pr_ID'])){

	$Pr_ID = 0;

	if(isset($_GET['P_ID']) && isset($_GET['Date'])){
		$P_ID = $_GET['P_ID'];
		$Date = $_GET['Date'];

		$sql_retrive_prescription = "SELECT ID
									FROM prescriptions
									WHERE D_ID = $User_ID AND P_ID = $P_ID AND Date = '$Date'";
		$result_prID = mysqli_query($conn, $sql_retrive_prescription);
		$found_Pr_ID = mysqli_fetch_array($result_prID);
		$Pr_ID = $found_Pr_ID['ID'];
	}
	else{
		$Pr_ID = $_GET['Pr_ID'];
	}
	

	$sql_patient_details = "SELECT Fname, Lname, DOB, Gender, Blood, Weight
							FROM patient
							WHERE ID = (SELECT P_ID
							            FROM prescriptions
							            WHERE ID = $Pr_ID)";

	$result_patient_details = mysqli_query($conn, $sql_patient_details);

	if (mysqli_num_rows($result_patient_details) == 1){
		$found_patient = mysqli_fetch_array($result_patient_details);

        
        $Fname = $found_patient['Fname'];
        $Lname = $found_patient['Lname'];
        $DOB = $found_patient['DOB'];
        $Gender = $found_patient['Gender'];
        $Blood = $found_patient['Blood'];
        $Weight = $found_patient['Weight'];

	    $sql_doctor_details = "SELECT Title, Fname, Lname, Current_Position, P_Location
								FROM doctor
								WHERE ID = $User_ID";


		$result_doctor_details = mysqli_query($conn, $sql_doctor_details);
		$found_doctor = mysqli_fetch_array($result_doctor_details);

	        $Title = $found_doctor['Title'];
	        $dFname = $found_doctor['Fname'];
	        $dLname = $found_doctor['Lname'];
	        $Current_Position = $found_doctor['Current_Position'];
	        $P_Location = $found_doctor['P_Location'];


	    echo '<table border="1" align="center">
				<tr>
					<td>
						<div id="joy_congratulation">
							Name: '.$Fname.' '.$Lname.'<br>
							Age: '.calculateAge($DOB).' Years<br>
							Gender: '.$Gender.'<br>
							Blood: '.$Blood.'<br>
							Weight: '.$Weight.'
						</div>
					</td>
					<td>
						<div id="joy_congratulation" align="center">
							'.$Title.' Dr. '.$dFname.' '.$dLname.'<br>
							'.$Current_Position.'<br>At<br>
							'.$P_Location.'
						</div>
					</td>
				</tr>';


		$sql_medicine_list = "SELECT m.Name, m.Strength, p.Morning, p.M_After_Meal, p.Noon, p.N_After_Meal, p.Night, p.Nt_After_Meal
								FROM prescribed_in p, medicine m
								WHERE p.M_ID = m.ID AND p.Pr_ID = $Pr_ID
								ORDER BY m.Name";
		$sql_test_list = "SELECT t.Name, l.Result, l.ID
							FROM test_log l, test t
							WHERE l.T_ID = t.ID AND l.Prescription_ID = $Pr_ID
							ORDER BY t.Name";
		$sql_problem_suggestion = "SELECT Problem, Suggestions
									FROM prescriptions
									WHERE ID = $Pr_ID";

		$result_medicine_list = mysqli_query($conn, $sql_medicine_list);
		$result_test_list = mysqli_query($conn, $sql_test_list);
		$result_problem_suggestion = mysqli_query($conn, $sql_problem_suggestion);

		$found_problem = mysqli_fetch_array($result_problem_suggestion);

	        $Problem = $found_problem['Problem'];
	        $Suggestions = $found_problem['Suggestions'];


	     echo '
				<tr>
					<td rowspan="'.(mysqli_num_rows($result_medicine_list) - 1).'">
						<div id="joy_warning">
							Problem:<br>'.$Problem.'<br><br>
							Suggestion:<br>'.$Suggestions.'<br><br>
							Test:<br>';
							if (mysqli_num_rows($result_test_list) > 0){
								while ($row = mysqli_fetch_assoc($result_test_list)){
									echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$row['Name'].'';
									if($row['Result'] == ""){
										echo ' - Not Done Yet';
									}
									else{
										echo ' - <a href="/chms/diagnostic-center/'.$row['Result'].'" download="report-'.$row['ID'].'.pdf">
									        <button>Download Report</button>
									    </a>';
									}
								}
							}
							else{
								echo '&nbsp;&nbsp;&nbsp;&nbsp;No Test';
							}
						echo '
						</div>
					</td>
					<td><div id="joy_congratulation">';
						if (mysqli_num_rows($result_medicine_list) > 0){
							while ($row = mysqli_fetch_assoc($result_medicine_list)){
								echo''.$row['Name'].' '.$row['Strength'].'<br>';

								if($row['Morning']){
									if($row['M_After_Meal']){
										echo '&nbsp;&nbsp;&nbsp;&nbsp;Morning - After Meal<br>';
									}
									else{
										echo '&nbsp;&nbsp;&nbsp;&nbsp;Morning - Before Meal<br>';
									}
								}
								if($row['Noon']){
									if($row['N_After_Meal']){
										echo '&nbsp;&nbsp;&nbsp;&nbsp;Noon - After Meal<br>';
									}
									else{
										echo '&nbsp;&nbsp;&nbsp;&nbsp;Noon - Before Meal<br>';
									}
								}
								if($row['Night']){
									if($row['Nt_After_Meal']){
										echo '&nbsp;&nbsp;&nbsp;&nbsp;Night - After Meal<br>';
									}
									else{
										echo '&nbsp;&nbsp;&nbsp;&nbsp;Night - Before Meal<br>';
									}
								}
								
							}
						}
						else{
							echo 'No Medicine';
						}

						echo '
					</div></td>
				</tr>
			</table>';

	echo '
	<table border="1" align="center">
		<caption>
			<div id="joy_congratulation">
				Add Medicine & Test
			</div>
		</caption>
		<form action="" method="POST" style="margin: 0px; padding: 0px;">
			<tr>
				<td>
					<div id="joy_congratulation">
						<select id="M_ID" name="M_ID">';
							$sql_medicine_list = "SELECT ID, Name, Strength
								FROM medicine
								ORDER BY Name, Strength DESC";
							$result_medicine_list = mysqli_query($conn, $sql_medicine_list);
							if(mysqli_num_rows($result_medicine_list) > 0){
								while ($row = mysqli_fetch_assoc($result_medicine_list)){
									echo '<option value="'.$row['ID'].'">'.$row['Name'].' - '.$row['Strength'].'</option>';
								}
							}
							echo '
						</select>
					</div>
				</td>
				<td>
					<div id="joy_congratulation">
						<input type="checkbox" id="Morning" name="Morning" value="1">
						<label for="Morning"> Morning </label>
						<input type="checkbox" id="M_After_Meal" name="M_After_Meal" value="1">
						<label for="M_After_Meal"> After Meal</label><br>

						<input type="checkbox" id="Noon" name="Noon" value="1">
						<label for="Noon"> Noon </label>
						<input type="checkbox" id="N_After_Meal" name="N_After_Meal" value="1">
						<label for="N_After_Meal"> After Meal</label><br>

						<input type="checkbox" id="Night" name="Night" value="1">
						<label for="Night"> Night </label>
						<input type="checkbox" id="Nt_After_Meal" name="Nt_After_Meal" value="1">
						<label for="Nt_After_Meal"> After Meal</label>
					</div>
				</td>
				<td>
					<div id="joy_congratulation">
						<input type="hidden" id="Pr_ID" name="Pr_ID" value="'.$Pr_ID.'">
						<input type="submit" name="add_medicine" value="Add">
					</div>
				</td>
			</tr>
		</form>
		<form action="" method="POST" style="margin: 0px; padding: 0px;">
			<tr>
				<td>
					<div id="joy_congratulation">
						<select id="T_ID" name="T_ID">';
							$sql_test_list = "SELECT ID, Name
								FROM test
								WHERE Status = TRUE
								ORDER BY Name";
							$result_test_list = mysqli_query($conn, $sql_test_list);
							if(mysqli_num_rows($result_test_list) > 0){
								while ($row = mysqli_fetch_assoc($result_test_list)){
									echo '<option value="'.$row['ID'].'">'.$row['Name'].'</option>';
								}
							}
							echo '
						</select>
					</div>
				</td>
				<td>
					<div id="joy_congratulation">
						<input type="hidden" id="Pr_ID" name="Pr_ID" value="'.$Pr_ID.'">
						<input type="submit" name="add_test" value="Add">
					</div>
				</td>
			</tr>
		</form>
	</table>';
	}
	else{
		redirect_to("/chms/doctor/search-patient.php");
	}
	
}
else{
	redirect_to("/chms/doctor/search-patient.php");
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











