<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");
include("status-check.php");

$User_ID = $_SESSION['User_ID'];


if(isset($_POST['search'])){
	$ID = $_POST['ID'];

	$sql_patient = "SELECT p.Fname, p.Lname, p.DOB, p.Gender, p.Blood, p.Weight, u.Photo
					FROM user_details u, patient p
					WHERE u.ID = p.ID AND u.ID = $ID";

	$result_patient = mysqli_query($conn, $sql_patient);

    if(mysqli_num_rows($result_patient) == 1){
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
    	     </tr>
    	     <tr>
    	     	<td colspan="4" align="center">
    	     		<form action="/chms/doctor/add-prescription.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
						<input type="hidden" id="ID" name="ID" value="'.$ID.'">
						<input type="submit" name="add" value="Add New Prescription">
					</form>
    	     	</td>
    	     </tr>
    	     <tr>
    	     	<td>
    	     		<div id="joy_congratulation">
    	     			All Prescription List:<br>';

    	     			$sql_all_prescription = "SELECT p.ID, p.Problem, p.Date, d.Title, d.Fname, d.Lname
								FROM prescriptions p, doctor d
								WHERE d.ID = p.D_ID AND p.P_ID = $ID
								ORDER BY Date DESC";

						$result_all_prescription = mysqli_query($conn, $sql_all_prescription);

						if (mysqli_num_rows($result_all_prescription) > 0){
							while ($row = mysqli_fetch_assoc($result_all_prescription)){
								echo '
									<form action="/chms/doctor/prescriptions-details.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
										<input type="hidden" id="ID" name="ID" value="'.$row['ID'].'">
										<input type="submit" name="submit" value="'.$row['Problem'].' - prescribed by '.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].' - On '.$row['Date'].'">
									</form>';
							}
						}
						else{
							echo 'No Prescription History!!';
						}
    	     	echo '
    	     		</div>
    	     	</td>
    	     	<td>
    	     		<div id="joy_congratulation">
    	     			All Test List:';
    	     			$sql_all_test = "SELECT l.ID, t.Name, l.Test_Date, d.Name as 'DCName', l.Result_Date, l.Result, l.Comment
								FROM test t
								JOIN test_log l
								ON(t.ID = l.T_ID)
								LEFT OUTER JOIN diagnostic_center d
								ON(l.DC_ID = d.ID)
								WHERE l.P_ID = $ID
								ORDER BY l.Test_Date DESC";

						$result_all_test = mysqli_query($conn, $sql_all_test);
						if (mysqli_num_rows($result_all_test) > 0){
							while ($row = mysqli_fetch_assoc($result_all_test)){
								echo '<br>'.$row['Name'].'';
								if($row['Test_Date'] != ""){
									if($row['Result'] != ""){
										echo ' - <a href="/chms/diagnostic-center/'.$row['Result'].'" download="report-'.$row['ID'].'.pdf">
											        <button>Download Report ('.$row['Result_Date'].')</button>
											    </a>';
									}
									else{
										echo ' - Waiting for Result ('.$row['Test_Date'].')';
									}
								}
								else{
									echo ' - Not Done Yet';
								}
							}
						}
						else{
							echo 'No Test History!!';
						}

    	     		echo '
    	     		</div>
    	     	</td>
    	     	<td>
    	     		<div id="joy_congratulation">
    	     			Vaccine History:';
    	     			$sql_vaccine_logs = "SELECT v.Vaccine_Name, v.Doss_Number, v.Date
						                    FROM vaccination_logs v
						                    WHERE P_ID = $ID AND Status = TRUE
						                    ORDER BY Date DESC";

					    $result_vaccine_logs = mysqli_query($conn, $sql_vaccine_logs);
					    if (mysqli_num_rows($result_vaccine_logs) > 0){
					    	while ($row = mysqli_fetch_assoc($result_vaccine_logs)){
					    		echo '<br>'.$row['Vaccine_Name'].' - Doss: '.$row['Doss_Number'].' - '.$row['Date'].'';
					    	}
					    }
					    else{
					    	echo 'No Vaccine History!!';
					    }

					    echo '
    	     		</div>
    	     	</td>
    	     	<td>
    	     		<div id="joy_congratulation">
    	     			Allergy History:';
    	     			$sql_allergy_logs = "SELECT Allergies
						                    FROM allergies_logs
						                    WHERE P_ID = ".$ID."
						                    ORDER BY Allergies";

				        $result_allergy_logs = mysqli_query($conn, $sql_allergy_logs);
				        if (mysqli_num_rows($result_allergy_logs) > 0){
					    	while ($row = mysqli_fetch_assoc($result_allergy_logs)){
					    		echo '<br>'.$row['Allergies'].'';
					    	}
					    }
					    else{
					    	echo 'No Allergy History!!';
					    }

					    echo '
    	     		</div>
    	     	</td>
    	     </tr>';
    }
    else{
		echo "<script type=\"text/javascript\">window.alert('Patient Not Found!!');window.location.href = '/chms/doctor/search-patient.php';</script>";
    }

}
else{
	redirect_to("/chms/doctor/search-patient.php");
}

ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Patient View</title>
</head>
<body>

</body>
</html>