<?php
ob_start();
include("../../common/header-content.php");
include("../../common/functions.php");
include("restrict-access.php");



if(isset($_POST['submit'])){
	$ID = $_POST['ID'];

	$sql_patient_details = "SELECT Fname, Lname, DOB, Gender, Blood, Weight
							FROM patient
							WHERE ID = (SELECT P_ID
							            FROM prescriptions
							            WHERE ID = $ID)";

	$result_patient_details = mysqli_query($conn, $sql_patient_details);
	$found_patient = mysqli_fetch_array($result_patient_details);

        
        $Fname = $found_patient['Fname'];
        $Lname = $found_patient['Lname'];
        $DOB = $found_patient['DOB'];
        $Gender = $found_patient['Gender'];
        $Blood = $found_patient['Blood'];
        $Weight = $found_patient['Weight'];

    $sql_doctor_details = "SELECT Title, Fname, Lname, Current_Position, P_Location
							FROM doctor
							WHERE ID = (SELECT D_ID
							            FROM prescriptions
							            WHERE ID = $ID)";


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
							WHERE p.M_ID = m.ID AND p.Pr_ID = $ID
							ORDER BY m.Name, m.Strength DESC";
	$sql_test_list = "SELECT t.Name, l.Result, l.ID
						FROM test_log l, test t
						WHERE l.T_ID = t.ID AND l.Prescription_ID = $ID
						ORDER BY Name";
	$sql_problem_suggestion = "SELECT Problem, Suggestions
								FROM prescriptions
								WHERE ID = $ID";

	$result_medicine_list = mysqli_query($conn, $sql_medicine_list);
	$result_test_list = mysqli_query($conn, $sql_test_list);
	$result_problem_suggestion = mysqli_query($conn, $sql_problem_suggestion);

	$found_problem = mysqli_fetch_array($result_problem_suggestion);

        $Problem = $found_problem['Problem'];
        $Suggestions = $found_problem['Suggestions'];


     echo '
			<tr>
				<td>
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
			<tr>
				<td colspan="2" align="center">
					<button onclick="window.print()">Print Prescription</button>
				</td>
			</tr>
		</table>';


}






ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Prescriptions - Doctor</title>
</head>
<body>

</body>
</html>