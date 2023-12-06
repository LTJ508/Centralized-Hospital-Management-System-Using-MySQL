<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];


if(isset($_POST['add'])){
	$Degree = $_POST['Degree'];
	$Field = $_POST['Field'];
	$Institution = $_POST['Institution'];
	$Year = $_POST['Year'];

	if($Field == ""){
		$Field = "N/A";
	}

	$sql_check = "SELECT *
					FROM degrees
					WHERE D_ID = $User_ID AND Degree = '$Degree'";
	if(mysqli_num_rows(mysqli_query($conn, $sql_check)) > 0){
		echo "<script type=\"text/javascript\">window.alert('This Degree Already Exist!!!');window.location.href = '/chms/doctor/degree.php';</script>";
	}
	else{
		$sql_insert_degree = "INSERT INTO degrees(D_ID, Degree, Field, Institution, Year)
							VALUES ($User_ID, '$Degree', '$Field', '$Institution', '$Year')";

		if (mysqli_query($conn, $sql_insert_degree)){
			echo "<script type=\"text/javascript\">window.alert('New Degree Added Successfully..');window.location.href = '/chms/doctor/degree.php';</script>";
		} 
		else{
			echo "Error: " . $sql . "" . mysqli_error($conn);
 		}
 		mysqli_close($conn);
	}
}
else if(isset($_POST['delete'])){
	$Degree = $_POST['Degree'];

	$sql_delete_data = "DELETE FROM degrees
						WHERE D_ID = $User_ID AND  Degree = '$Degree'";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Degree Info Deleted Successfully..');window.location.href = '/chms/doctor/degree.php';</script>";
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
					Add New Degree Info
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<input type="text" name="Degree" placeholder="Degree Name" required>
			</td>
			<td>
				<input type="text" name="Field" placeholder="Field">
			</td>
			<td>
				<input type="text" name="Institution" placeholder="Institution" required>
			</td>
			<td>
				<input type="number" name="Year" min="1900" max="'.date('Y').'" placeholder="Year" required>
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
					All Degree History
				</div>
			</td>
		</tr>';


$sql_degree_logs = "SELECT Degree, Field, Institution, Year
                    FROM degrees
                    WHERE D_ID = $User_ID
                    ORDER BY Year DESC";

        $result_degree_logs = mysqli_query($conn, $sql_degree_logs);
        if (mysqli_num_rows($result_degree_logs) > 0){

        	echo '
        	<tr>
				<td>
					<div id="joy_warning" align="center">
						Degree
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Field
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Institution
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Year
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Action
					</div>
				</td>
			</tr>';

            while ($row = mysqli_fetch_assoc($result_degree_logs)){
         
                echo '<tr>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Degree'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Field'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Institution'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Year'].'
							</div>
						</td>
						<td>
							<div id="joy_warning">
								<form action="" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
									<input type="hidden" id="Degree" name="Degree" value="'.$row['Degree'].'">
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
					No Degree History
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
	<title>All Degree</title>
</head>
<body>
	<script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to delete this user?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>