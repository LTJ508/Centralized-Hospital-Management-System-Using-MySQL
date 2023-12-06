<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");
include("status-check.php");

$User_ID = $_SESSION['User_ID'];


if(isset($_POST['add'])){
	$D_ID = $_POST['D_ID'];
	$Day = $_POST['Day'];
	$Start_Time = $_POST['Start_Time'];
	$End_Time = $_POST['End_Time'];
	$Room = $_POST['Room'];
	$Fees = $_POST['Fees'];
	$MAX_Capacity = $_POST['MAX_Capacity'];


	$sql_day_check = "SELECT *
						FROM consultation
						WHERE D_ID = $D_ID AND H_ID = $User_ID AND Day = '$Day'";
	if(mysqli_num_rows(mysqli_query($conn, $sql_day_check)) > 0){
		echo "<script type=\"text/javascript\">window.alert('This doctor already have a Consultation hour on this day in your hospital!! Please select another day...');window.location.href = '/chms/hospital/add-consultation.php';</script>";
	}
	else{

		$sql_time_check = "SELECT Start_Time, End_Time
							FROM consultation
							WHERE D_ID = $D_ID AND Day = '$Day'";
		$result_time_list = mysqli_query($conn, $sql_time_check);
		$flag = FALSE;
		if (mysqli_num_rows($result_time_list) > 0){
			while ($row = mysqli_fetch_assoc($result_time_list)){
				if($Start_Time > $row['Start_Time'] && $Start_Time < $row['End_Time']){
					$flag = TRUE;
					break;
				}
				else if($End_Time > $row['Start_Time'] && $End_Time < $row['End_Time']){
					$flag = TRUE;
					break;
				}
			}
		}

		if($flag){
			echo "<script type=\"text/javascript\">window.alert('This doctor already have a Consultation hour on this time in another hospital!! Please select another day...');window.location.href = '/chms/hospital/add-consultation.php';</script>";
		}
		else{
			$sql_instert_data = "INSERT INTO consultation(D_ID, H_ID, Day, Start_Time, End_Time, Room, Fees, MAX_Capacity)
								VALUES($D_ID, $User_ID, '$Day', '$Start_Time', '$End_Time', $Room, $Fees, $MAX_Capacity)";
			if (mysqli_query($conn, $sql_instert_data)){
				echo "<script type=\"text/javascript\">window.alert('Consultation Hour Added Successfully..');window.location.href = '/chms/hospital/consultation.php';</script>";
			} 
			else{
				echo "Error: " . $sql . "" . mysqli_error($conn);
			}
			mysqli_close($conn);
		}
	}
}


$sql_info = "SELECT h.Name, u.Photo
		FROM hospital h, user_details u
		WHERE h.ID = u.ID AND h.ID = ".$User_ID."";

		$result_info = mysqli_query($conn, $sql_info);
		$found_user = mysqli_fetch_array($result_info);

        $Name = $found_user['Name'];
        $Photo = $found_user['Photo'];

        echo '<table align="center" border="1">';

        if($Photo == ""){
			echo '<div align="center"><img src="/chms/hospital/img/null.jpg" alt="'.$Name.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/hospital/'.$Photo.'" alt="'.$Name.'" width="120px" height="120px"></div>';
		}

		echo '<div id="joy_congratulation" align="center">
		'. $Name . '</div>';

echo '
	<caption>
		<div id="joy_congratulation">
			Add New Consultation Hour
		</div>
	</caption>
	<form action="" method="POST" style="margin: 0px; padding: 0px;">
	<tr>
		<th>
			<div id="joy_congratulation">
				<label for="D_ID">Select Doctor: </label>
			</div>
		</th>
		<td>
			<div id="joy_congratulation">
				<select id="D_ID" name="D_ID" required>';

				$sql_doctor_list = "SELECT d.ID, d.Title, d.Fname, d.Lname
									FROM doctor d, user_details u
									WHERE d.ID = u.ID AND u.Status = TRUE";
				$result_doctor_list = mysqli_query($conn, $sql_doctor_list);
				if (mysqli_num_rows($result_doctor_list) > 0){
					while ($row = mysqli_fetch_assoc($result_doctor_list)){
						echo '<option value="'.$row['ID'].'">'.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'</option>';
					}
				}

				echo '
				</select>
			</div>
		</td>
	</tr>
	<tr>
		<th>
			<div id="joy_congratulation">
				<label for="Day">Select Day: </label>
			</div>
		</th>
		<td>
			<div id="joy_congratulation">
				<select id="Day" name="Day" required>
					<option value="Sunday">Sunday</option>
					<option value="Monday">Monday</option>
					<option value="Tuesday">Tuesday</option>
					<option value="Wednesday">Wednesday</option>
					<option value="Thursday">Thursday</option>
					<option value="Friday">Friday</option>
					<option value="Saturday">Saturday</option>
				</select>
			</div>
		</td>
	</tr>
	<tr>
		<th>
			<div id="joy_congratulation">
				<label for="Start_Time">Select Start Time: </label>
			</div>
		</th>
		<td>
			<div id="joy_congratulation">
				<input type="time" name="Start_Time" id="Start_Time" required>
			</div>
		</td>
	</tr>
	<tr>
		<th>
			<div id="joy_congratulation">
				<label for="End_Time">Select End Time: </label>
			</div>
		</th>
		<td>
			<div id="joy_congratulation">
				<input type="time" id="End_Time" name="End_Time" required>
			</div>
		</td>
	</tr>
	<tr>
		<th>
			<div id="joy_congratulation">
				<label for="Room">Room Number: </label>
			</div>
		</th>
		<td>
			<div id="joy_congratulation">
				<input type="Number" name="Room" id="Room" required>
			</div>
		</td>
	</tr>
	<tr>
		<th>
			<div id="joy_congratulation">
				<label for="Fees">Fees: </label>
			</div>
		</th>
		<td>
			<div id="joy_congratulation">
				<input type="Number" name="Fees" id="Fees" required>
			</div>
		</td>
	</tr>
	<tr>
		<th>
			<div id="joy_congratulation">
				<label for="MAX_Capacity">MAX Capacity: </label>
			</div>
		</th>
		<td>
			<div id="joy_congratulation">
				<input type="Number" name="MAX_Capacity" id="MAX_Capacity" required>
			</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<div id="joy_congratulation">
				<input type="submit" name="add" value="Submit">
			</div>
		</td>
	</tr></form></table>
	';




ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Consultation Hour List</title>
</head>
<body>

</body>
</html>

