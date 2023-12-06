<?php
ob_start();
include("../../common/header-content.php");
include("../../common/functions.php");
include("restrict-access.php");

if(isset($_GET['H_ID'])){
	$H_ID = $_GET['H_ID'];

	$sql_info = "SELECT h.Name, u.Photo
		FROM hospital h, user_details u
		WHERE h.ID = u.ID AND h.ID = $H_ID";

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
			All Consultation Hour List
		</div>
	</caption>
	<tr>
		<th>
			<div id="joy_congratulation" align="center">
				Day
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Doctor Name
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Time Slot
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Room Number
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Fees
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				MAX Capacity
			</div>
		</th>
		<th>
			<div id="joy_congratulation" align="center">
				Action
			</div>
		</th>
	</tr>';


$sunday = "SELECT c.D_ID, d.Title, d.Fname, d.Lname, c.Start_Time, c.End_Time, c.Room, c.Fees, c.MAX_Capacity
				FROM consultation c, doctor d
				WHERE c.D_ID = d.ID AND c.H_ID = $H_ID AND Day = 'Sunday'
				ORDER BY c.Start_Time";
$monday = "SELECT c.D_ID, d.Title, d.Fname, d.Lname, c.Start_Time, c.End_Time, c.Room, c.Fees, c.MAX_Capacity
				FROM consultation c, doctor d
				WHERE c.D_ID = d.ID AND c.H_ID = $H_ID AND Day = 'Monday'
				ORDER BY c.Start_Time";
$tuesday = "SELECT c.D_ID, d.Title, d.Fname, d.Lname, c.Start_Time, c.End_Time, c.Room, c.Fees, c.MAX_Capacity
				FROM consultation c, doctor d
				WHERE c.D_ID = d.ID AND c.H_ID = $H_ID AND Day = 'Tuesday'
				ORDER BY c.Start_Time";
$wednesday = "SELECT c.D_ID, d.Title, d.Fname, d.Lname, c.Start_Time, c.End_Time, c.Room, c.Fees, c.MAX_Capacity
				FROM consultation c, doctor d
				WHERE c.D_ID = d.ID AND c.H_ID = $H_ID AND Day = 'Wednesday'
				ORDER BY c.Start_Time";
$thursday = "SELECT c.D_ID, d.Title, d.Fname, d.Lname, c.Start_Time, c.End_Time, c.Room, c.Fees, c.MAX_Capacity
				FROM consultation c, doctor d
				WHERE c.D_ID = d.ID AND c.H_ID = $H_ID AND Day = 'Thursday'
				ORDER BY c.Start_Time";
$friday = "SELECT c.D_ID, d.Title, d.Fname, d.Lname, c.Start_Time, c.End_Time, c.Room, c.Fees, c.MAX_Capacity
				FROM consultation c, doctor d
				WHERE c.D_ID = d.ID AND c.H_ID = $H_ID AND Day = 'Friday'
				ORDER BY c.Start_Time";
$saturday = "SELECT c.D_ID, d.Title, d.Fname, d.Lname, c.Start_Time, c.End_Time, c.Room, c.Fees, c.MAX_Capacity
				FROM consultation c, doctor d
				WHERE c.D_ID = d.ID AND c.H_ID = $H_ID AND Day = 'Saturday'
				ORDER BY c.Start_Time";

$result_sunday = mysqli_query($conn, $sunday);
$result_monday = mysqli_query($conn, $monday);
$result_tuesday = mysqli_query($conn, $tuesday);
$result_wednesday = mysqli_query($conn, $wednesday);
$result_thursday = mysqli_query($conn, $thursday);
$result_friday = mysqli_query($conn, $friday);
$result_saturday = mysqli_query($conn, $saturday);


$flag = FALSE;
if (mysqli_num_rows($result_sunday) > 0){
	echo '
		<tr>
			<td rowspan="'.mysqli_num_rows($result_sunday).'">
				<div id="joy_congratulation">
					Sunday
				</div>
			</td>';
		while ($row = mysqli_fetch_assoc($result_sunday)){
			if($flag){
				echo '<tr>';
			}
			$flag = TRUE;
			echo '
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Start_Time'].' - '.$row['End_Time'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Room'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Fees'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['MAX_Capacity'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						<form action="/chms/admin/hospital/consultation.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="D_ID" name="D_ID" value="'.$row['D_ID'].'">
							<input type="hidden" id="H_ID" name="H_ID" value="'.$H_ID.'">
							<input type="hidden" id="Day" name="Day" value="Sunday">
							<input type="submit" name="delete" value="Delete">
						</form>
					</div>
				</th>
			</tr>
			';
		}
}
else{
	echo '
	<tr>
		<td>
			<div id="joy_congratulation" align="center">
				Sunday
			</div>
		</td>
		<td colspan="6">
			<div id="joy_warning" align="center">
				No Doctor Available!!
			</div>
		</td>
	</tr>';
}






$flag = FALSE;
if (mysqli_num_rows($result_monday) > 0){
	echo '
		<tr>
			<td rowspan="'.mysqli_num_rows($result_monday).'">
				<div id="joy_congratulation">
					Monday
				</div>
			</td>';
		while ($row = mysqli_fetch_assoc($result_monday)){
			if($flag){
				echo '<tr>';
			}
			$flag = TRUE;
			echo '
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Start_Time'].' - '.$row['End_Time'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Room'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Fees'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['MAX_Capacity'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						<form action="/chms/admin/hospital/consultation.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="D_ID" name="D_ID" value="'.$row['D_ID'].'">
							<input type="hidden" id="H_ID" name="H_ID" value="'.$H_ID.'">
							<input type="hidden" id="Day" name="Day" value="Monday">
							<input type="submit" name="delete" value="Delete">
						</form>
					</div>
				</th>
			</tr>
			';
		}
}
else{
	echo '
	<tr>
		<td>
			<div id="joy_congratulation" align="center">
				Monday
			</div>
		</td>
		<td colspan="6">
			<div id="joy_warning" align="center">
				No Doctor Available!!
			</div>
		</td>
	</tr>';
}



$flag = FALSE;
if (mysqli_num_rows($result_tuesday) > 0){
	echo '
		<tr>
			<td rowspan="'.mysqli_num_rows($result_tuesday).'">
				<div id="joy_congratulation">
					Tuesday
				</div>
			</td>';
		while ($row = mysqli_fetch_assoc($result_tuesday)){
			if($flag){
				echo '<tr>';
			}
			$flag = TRUE;
			echo '
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Start_Time'].' - '.$row['End_Time'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Room'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Fees'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['MAX_Capacity'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						<form action="/chms/admin/hospital/consultation.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="D_ID" name="D_ID" value="'.$row['D_ID'].'">
							<input type="hidden" id="H_ID" name="H_ID" value="'.$H_ID.'">
							<input type="hidden" id="Day" name="Day" value="Tuesday">
							<input type="submit" name="delete" value="Delete">
						</form>
					</div>
				</th>
			</tr>
			';
		}
}
else{
	echo '
	<tr>
		<td>
			<div id="joy_congratulation" align="center">
				Tuesday
			</div>
		</td>
		<td colspan="6">
			<div id="joy_warning" align="center">
				No Doctor Available!!
			</div>
		</td>
	</tr>';
}





$flag = FALSE;
if (mysqli_num_rows($result_wednesday) > 0){
	echo '
		<tr>
			<td rowspan="'.mysqli_num_rows($result_wednesday).'">
				<div id="joy_congratulation">
					Wednesday
				</div>
			</td>';
		while ($row = mysqli_fetch_assoc($result_wednesday)){
			if($flag){
				echo '<tr>';
			}
			$flag = TRUE;
			echo '
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Start_Time'].' - '.$row['End_Time'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Room'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Fees'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['MAX_Capacity'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						<form action="/chms/admin/hospital/consultation.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="D_ID" name="D_ID" value="'.$row['D_ID'].'">
							<input type="hidden" id="H_ID" name="H_ID" value="'.$H_ID.'">
							<input type="hidden" id="Day" name="Day" value="Wednesday">
							<input type="submit" name="delete" value="Delete">
						</form>
					</div>
				</th>
			</tr>
			';
		}
}
else{
	echo '
	<tr>
		<td>
			<div id="joy_congratulation" align="center">
				Wednesday
			</div>
		</td>
		<td colspan="6">
			<div id="joy_warning" align="center">
				No Doctor Available!!
			</div>
		</td>
	</tr>';
}





$flag = FALSE;
if (mysqli_num_rows($result_thursday) > 0){
	echo '
		<tr>
			<td rowspan="'.mysqli_num_rows($result_thursday).'">
				<div id="joy_congratulation">
					Thursday
				</div>
			</td>';
		while ($row = mysqli_fetch_assoc($result_thursday)){
			if($flag){
				echo '<tr>';
			}
			$flag = TRUE;
			echo '
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Start_Time'].' - '.$row['End_Time'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Room'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Fees'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['MAX_Capacity'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						<form action="/chms/admin/hospital/consultation.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="D_ID" name="D_ID" value="'.$row['D_ID'].'">
							<input type="hidden" id="H_ID" name="H_ID" value="'.$H_ID.'">
							<input type="hidden" id="Day" name="Day" value="Thursday">
							<input type="submit" name="delete" value="Delete">
						</form>
					</div>
				</th>
			</tr>
			';
		}
}
else{
	echo '
	<tr>
		<td>
			<div id="joy_congratulation" align="center">
				Thursday
			</div>
		</td>
		<td colspan="6">
			<div id="joy_warning" align="center">
				No Doctor Available!!
			</div>
		</td>
	</tr>';
}





$flag = FALSE;
if (mysqli_num_rows($result_friday) > 0){
	echo '
		<tr>
			<td rowspan="'.mysqli_num_rows($result_friday).'">
				<div id="joy_congratulation">
					Friday
				</div>
			</td>';
		while ($row = mysqli_fetch_assoc($result_friday)){
			if($flag){
				echo '<tr>';
			}
			$flag = TRUE;
			echo '
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Start_Time'].' - '.$row['End_Time'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Room'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Fees'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['MAX_Capacity'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						<form action="/chms/admin/hospital/consultation.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="D_ID" name="D_ID" value="'.$row['D_ID'].'">
							<input type="hidden" id="H_ID" name="H_ID" value="'.$H_ID.'">
							<input type="hidden" id="Day" name="Day" value="Friday">
							<input type="submit" name="delete" value="Delete">
						</form>
					</div>
				</th>
			</tr>
			';
		}
}
else{
	echo '
	<tr>
		<td>
			<div id="joy_congratulation" align="center">
				Friday
			</div>
		</td>
		<td colspan="6">
			<div id="joy_warning" align="center">
				No Doctor Available!!
			</div>
		</td>
	</tr>';
}




$flag = FALSE;
if (mysqli_num_rows($result_saturday) > 0){
	echo '
		<tr>
			<td rowspan="'.mysqli_num_rows($result_saturday).'">
				<div id="joy_congratulation">
					Saturday
				</div>
			</td>';
		while ($row = mysqli_fetch_assoc($result_saturday)){
			if($flag){
				echo '<tr>';
			}
			$flag = TRUE;
			echo '
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Start_Time'].' - '.$row['End_Time'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Room'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['Fees'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$row['MAX_Capacity'].'
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						<form action="/chms/admin/hospital/consultation.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="D_ID" name="D_ID" value="'.$row['D_ID'].'">
							<input type="hidden" id="H_ID" name="H_ID" value="'.$H_ID.'">
							<input type="hidden" id="Day" name="Day" value="Saturday">
							<input type="submit" name="delete" value="Delete">
						</form>
					</div>
				</th>
			</tr>
			';
		}
}
else{
	echo '
	<tr>
		<td>
			<div id="joy_congratulation" align="center">
				Saturday
			</div>
		</td>
		<td colspan="6">
			<div id="joy_warning" align="center">
				No Doctor Available!!
			</div>
		</td>
	</tr>';
}


}

else if(isset($_POST['delete'])){
	$H_ID = $_POST['H_ID'];
	$D_ID = $_POST['D_ID'];
	$Day = $_POST['Day'];

	$sql_delete_data = "DELETE FROM consultation
						WHERE D_ID = $D_ID AND  H_ID = $H_ID AND Day = '$Day'";

	if (mysqli_query($conn, $sql_delete_data)){
		echo "<script type=\"text/javascript\">window.alert('Consultation Hour Deleted Successfully..');window.location.href = '/chms/admin/hospital/consultation.php?H_ID=".$H_ID."';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);
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
	<title>Consultation Hour List</title>
</head>
<body>
	<script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to delete this Hour?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>



