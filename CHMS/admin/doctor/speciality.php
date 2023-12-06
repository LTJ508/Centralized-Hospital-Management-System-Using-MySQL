<?php
ob_start();
include("../../common/header-content.php");
include("../../common/functions.php");
include("restrict-access.php");

if(isset($_GET['D_ID'])){
	$D_ID = $_GET['D_ID'];

	$sql_info = "SELECT d.Title, d.Fname, d.Lname, u.Photo
		FROM doctor d, user_details u
		WHERE d.ID = u.ID AND d.ID = ".$D_ID."";

		$result_info = mysqli_query($conn, $sql_info);
		$found_user = mysqli_fetch_array($result_info);

		$Title = $found_user['Title'];
		$Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];
        $Photo = $found_user['Photo'];



        if($Photo == ""){
			echo '<div align="center"><img src="/chms/doctor/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/doctor/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px"></div>';
		}

		echo '<div id="joy_congratulation" align="center">'.
			$Title .' Dr. '. $Fname . ' '. $Lname .'</div>';

echo '
		<table align="center" border="1">
		<tr>
			<td colspan="2">
				<div id="joy_congratulation" align="center">
					All Speciality List
				</div>
			</td>
		</tr>';


$sql_speciality_logs = "SELECT Field
                    FROM speciality
                    WHERE D_ID = $D_ID
                    ORDER BY Field";

        $result_speciality_logs = mysqli_query($conn, $sql_speciality_logs);
        if (mysqli_num_rows($result_speciality_logs) > 0){

        	echo '
        	<tr>
				<td>
					<div id="joy_warning" align="center">
						Department
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Action
					</div>
				</td>
			</tr>';

            while ($row = mysqli_fetch_assoc($result_speciality_logs)){
         
                echo '<tr>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Field'].'
							</div>
						</td>
						<td>
							<div id="joy_warning">
								<form action="/chms/admin/doctor/speciality.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
									<input type="hidden" id="Field" name="Field" value="'.$row['Field'].'">
									<input type="hidden" id="D_ID" name="D_ID" value="'.$D_ID.'">
									<input type="submit" name="delete" value="Delete">
								</form>
							</div>
						</td>
					</tr>';
            }
        }
        else{
        	echo '
        	<td colspan="2">
				<div id="joy_warning" align="center">
					No Awards History
				</div>
			</td>';
        }

        
}

else if(isset($_POST['delete'])){
	$D_ID = $_POST['D_ID'];
	$Field = $_POST['Field'];

	$sql_delete_data = "DELETE FROM speciality
						WHERE D_ID = $D_ID AND  Field = '$Field'";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Department Info Deleted Successfully..');window.location.href = '/chms/admin/doctor/speciality.php?D_ID=".$D_ID."';</script>";
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
	<title>Speciality</title>
</head>
<body>
	<script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to delete this Field?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>