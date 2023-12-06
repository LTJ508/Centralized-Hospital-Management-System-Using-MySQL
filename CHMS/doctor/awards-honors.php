<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];



if(isset($_POST['add'])){
	$Award_Name = $_POST['Award_Name'];
	$Awarding_Organization = $_POST['Awarding_Organization'];
	$Year_Received = $_POST['Year_Received'];
	$Description = $_POST['Description'];

	if($Description == ""){
		$Description = "N/A";
	}

	$sql_check = "SELECT *
					FROM awards_honors
					WHERE D_ID = $User_ID AND Award_Name = '$Award_Name'";
	if(mysqli_num_rows(mysqli_query($conn, $sql_check)) > 0){
		echo "<script type=\"text/javascript\">window.alert('Awards Name Already Exist!!!');window.location.href = '/chms/doctor/awards-honors.php';</script>";
	}
	else{
		$sql_insert_awards = "INSERT INTO awards_honors(D_ID, Award_Name, Awarding_Organization, Year_Received, Description)
							VALUES ($User_ID, '$Award_Name', '$Awarding_Organization', '$Year_Received', '$Description')";

		if (mysqli_query($conn, $sql_insert_awards)){
			echo "<script type=\"text/javascript\">window.alert('New Awards History Added Successfully..');window.location.href = '/chms/doctor/awards-honors.php';</script>";
		} 
		else{
			echo "Error: " . $sql . "" . mysqli_error($conn);
 		}
 		mysqli_close($conn);
	}
}
else if(isset($_POST['delete'])){
	$Award_Name = $_POST['Award_Name'];

	$sql_delete_data = "DELETE FROM awards_honors
						WHERE D_ID = $User_ID AND  Award_Name = '$Award_Name'";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Awards Info Deleted Successfully..');window.location.href = '/chms/doctor/awards-honors.php';</script>";
			} 
			else{
				echo "Error: " . $sql . "" . mysqli_error($conn);
	 		}
	 		mysqli_close($conn);
}




$sql_info = "SELECT d.Title, d.Fname, d.Lname, u.Photo
		FROM doctor d, user_details u
		WHERE d.ID = u.ID AND d.ID = $User_ID";

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

echo '<form action="" method="POST" style="margin: 0px; padding: 0px;">
		<tr>
			<td colspan="5">
				<div id="joy_congratulation" align="center">
					Add New Awards Info
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<input type="text" name="Award_Name" placeholder="Award Name" required>
			</td>
			<td>
				<input type="text" name="Awarding_Organization" placeholder="Awarding Organization" required>
			</td>
			<td>
				<input type="number" name="Year_Received" min="1900" max="'.date('Y').'" placeholder="Year Received" required>
			</td>
			<td>
				<textarea id="Description" name="Description" rows="2" cols="30" placeholder="Short Description"></textarea>
			</td>
			<td>
				<input type="submit" name="add" value="Add">
			</td>
		</tr>
	</form>
	</table>
		<table align="center" border="1">
		<tr>
			<td colspan="5">
				<div id="joy_congratulation" align="center">
					All Awards History
				</div>
			</td>
		</tr>';


$sql_awards_logs = "SELECT Award_Name, Awarding_Organization, Year_Received, Description
                    FROM awards_honors
                    WHERE D_ID = $User_ID
                    ORDER BY Year_Received DESC";

        $result_awards_logs = mysqli_query($conn, $sql_awards_logs);
        if (mysqli_num_rows($result_awards_logs) > 0){

        	echo '
        	<tr>
				<td>
					<div id="joy_warning" align="center">
						Awards
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Organization
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Date
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Description
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Action
					</div>
				</td>
			</tr>';

            while ($row = mysqli_fetch_assoc($result_awards_logs)){
         
                echo '<tr>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Award_Name'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Awarding_Organization'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Year_Received'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Description'].'
							</div>
						</td>
						<td>
							<div id="joy_warning">
								<form action="" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
									<input type="hidden" id="Award_Name" name="Award_Name" value="'.$row['Award_Name'].'">
									<input type="submit" name="delete" value="Delete">
								</form>
							</div>
						</td>
					</tr>';
            }
        }
        else{
        	echo '
        	<td colspan="5">
				<div id="joy_warning" align="center">
					No Awards History
				</div>
			</td>';
        }
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Awards & Honors</title>
</head>
<body>
	<script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to delete this awards info?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>