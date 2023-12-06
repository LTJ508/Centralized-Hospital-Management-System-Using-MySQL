<?php
ob_start();
include("header-content.php");
include("functions.php");
if(isset($_POST['consultant'])){
	$ID = $_POST['Doctor_ID'];

	$sql_info = "SELECT d.Title, d.Fname, d.Lname, u.Photo
		FROM doctor d, user_details u
		WHERE d.ID = u.ID AND d.ID = ".$ID."";

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

		echo '<div id="joy_congratulation" align="center">'.
			$Title .' Dr. '. $Fname . ' '. $Lname .'</div>';

		$sql_hospital = "SELECT DISTINCT H_ID
							FROM consultation
							WHERE D_ID = $ID
							ORDER BY H_ID";

		$result_hospital = mysqli_query($conn, $sql_hospital);
		if (mysqli_num_rows($result_hospital) > 0){
			echo '<tr>
			<th>Hospital Name</th>
			<th>Day</th>
			<th>Time Slot</th>
			<th>Room No.</th>
			<th>Fees</th>
			<th>Max Capacity</th></tr>';
			while ($row = mysqli_fetch_assoc($result_hospital)){
				$hospital_ID = $row['H_ID'];

				$sql_hospital_details = "SELECT h.Name, u.Address
										FROM hospital h, user_details u
										WHERE u.ID = h.ID AND h.ID = $hospital_ID";
				$result_hospital_details = mysqli_query($conn, $sql_hospital_details);
				$found_hospital_details = mysqli_fetch_array($result_hospital_details);
				$Name = $found_hospital_details['Name'];
				$Address = $found_hospital_details['Address'];


				$sql_info = "SELECT Day, Start_Time, End_Time, Room, Fees, MAX_Capacity
							FROM consultation
							WHERE H_ID = $hospital_ID AND D_ID = $ID";
				$result_info = mysqli_query($conn, $sql_info);

				echo '<tr>
						<td rowspan="'.mysqli_num_rows($result_info).'">'.$Name.'<br>'.$Address.'</td>';
				$flag = false;

				while ($row = mysqli_fetch_assoc($result_info)){
					if($flag){
						echo '<tr>';
					}
					echo '<td>'.$row['Day'].'</td>';
					echo '<td>'.$row['Start_Time'].' - '.$row['End_Time'].'</td>';
					echo '<td>'.$row['Room'].'</td>';
					echo '<td>'.$row['Fees'].' TK</td>';
					echo '<td>'.$row['MAX_Capacity'].' Appoinments</td></tr>';
					$flag = true;
				}


				

			}
		}
		else{
			echo '<div id="joy_warning" align="center">No Hour Available</div>';
		}
		
		echo '</table>';
}
else{
	redirect_to("/chms/");
}
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Consultant Hour</title>
</head>
<body>

</body>
</html>
