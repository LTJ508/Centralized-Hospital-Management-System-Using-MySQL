<?php 
include("header-content.php");

if (isset($_POST['submit'])){
	$Speciality = $_POST['Speciality'];
	$Name = $_POST['Name'];
	$result_doctor_list = "";

	if($Speciality != "NULL" && $Name != ""){
		$sql_retrive = "SELECT d.ID as ID
				FROM doctor d, speciality s, user_details u
				WHERE d.ID = s.D_ID AND s.D_ID = u.ID AND u.Status = True AND s.Field = '$Speciality' AND (d.Fname LIKE '%". $Name ."%' OR d.Lname LIKE '%". $Name ."%')
				ORDER BY d.ID";
	}
	else if($Speciality != "NULL"){
		$sql_retrive = "SELECT s.D_ID as ID
				FROM speciality s, user_details u
				WHERE s.D_ID = u.ID AND u.Status = True AND s.Field = '$Speciality'
				ORDER BY s.D_ID";
	}
	else if($Name != ""){
		$sql_retrive = "SELECT d.ID as ID
				FROM doctor d, user_details u
				WHERE d.ID = u.ID AND u.Status = True AND (d.Fname LIKE '%". $Name ."%' OR d.Lname LIKE '%". $Name ."%')
				ORDER BY d.ID";
	}
	else{
		echo "<script type=\"text/javascript\">window.alert('Please select department or type name');window.location.href = '/chms/common/find-doctor.php';</script>";
	}

	$result_doctor_list = mysqli_query($conn, $sql_retrive);

	if (mysqli_num_rows($result_doctor_list) > 0) {
	$count = 0;
	echo '<table align="center">';
    while ($row = mysqli_fetch_assoc($result_doctor_list)) {
    	$ID = $row['ID'];
    	if($count % 3 == 0){
    		echo "<tr>";
    	}
		
		echo '<td align="center">';
		$sql_retrive_details = "SELECT d.Title, d.Fname, d.Lname, d.Current_Position, d.P_Location, u.Photo
				FROM doctor d, user_details u
				WHERE d.ID = u.ID AND d.ID = ". $ID. "";

		$result_doctor_details = mysqli_query($conn, $sql_retrive_details);

		$found_user = mysqli_fetch_array($result_doctor_details);
		$Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];
        $Title = $found_user['Title'];
        $Current_Position = $found_user['Current_Position'];
        $P_Location = $found_user['P_Location'];
        $Photo = $found_user['Photo'];

		echo '
		<div style="display: flex;">';
		if($Photo == ""){
			echo '<img src="/chms/doctor/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px">';
		}
		else{
			echo '<img src="/chms/doctor/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px">';
		}
			echo '
			<div id="joy_congratulation">' .
				$Title .' Dr. '. $Fname .' '. $Lname .'
				<br>';

		$sql_retrive_degree = "SELECT Degree, Field, Institution
								FROM degrees
								WHERE D_ID = $ID
								ORDER BY Year";

		$result_degree = mysqli_query($conn, $sql_retrive_degree);
		if (mysqli_num_rows($result_degree) > 0){
			while ($row = mysqli_fetch_assoc($result_degree)){
				echo $row['Degree'] .'('. $row['Field'] .')('. $row['Institution'].'), ';
			}
			echo '<br>';
		}


		$sql_retrive_speciality = "SELECT Field
									FROM speciality
									WHERE D_ID = $ID
									ORDER BY Field";

		$result_speciality = mysqli_query($conn, $sql_retrive_speciality);

		if (mysqli_num_rows($result_speciality) > 0){
			while ($row = mysqli_fetch_assoc($result_speciality)){
				echo $row['Field'] .', ';
			}
			echo '<br>';
		}

		echo $Current_Position .' at ' . $P_Location .'

		<form action="/chms/patient/make-appoinment.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
			<input type="hidden" id="Doctor_ID" name="Doctor_ID" value="'. $ID .'">
			<input type="submit" name="appoinment" value="Make Appoinment">
		</form>
		<form action="/chms/common/doctor-details.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
			<input type="hidden" id="Doctor_ID" name="Doctor_ID" value="'. $ID .'">
			<input type="submit" name="details" value="More Details">
		</form>
		
	</div>
</div>

		';

        echo "</td>";
        $count++;

        if($count % 3 == 0){
    		echo "</tr>";
    	}
    }

    if($count % 3 != 0){
    		echo "</tr>";
    	}
    echo '</table>';

}


}
else{
	echo
	'
	<form action="" method="POST">
		<table align="center">
			<caption>
				<div id="joy_congratulation">
					Find your Doctor
				</div>
			</caption>
			<tr>
				<td colspan="3" align="center">
					<label for="department">Choose a department or type doctors name</label>
				</td>
			</tr>
			<tr>
				<td>
					<select name="Speciality" id="department">
						<option value="NULL">Select A Department</option>';

					$sql_department_list = "SELECT DISTINCT Field
											FROM speciality
											ORDER BY Field";
					$result_department_list = mysqli_query($conn, $sql_department_list);
					if (mysqli_num_rows($result_department_list) > 0){
						while ($row = mysqli_fetch_assoc($result_department_list)){
							echo '<option value="'.$row['Field'].'">'.$row['Field'].'</option>';
						}
					}
                        echo '
                    </select>
				</td>
				<td>
					<input type="text" name="Name" placeholder="Doctor Name">
				</td>
				<td>
					<input type="submit" name="submit" value="Submit">
				</td>
			</tr>
		</table>
	</form>

';
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Find Doctor</title>
</head>
<body>

</body>
</html>