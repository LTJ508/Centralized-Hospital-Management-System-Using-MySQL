<?php
ob_start();
include("../../common/header-content.php");
include("../../common/functions.php");
include("restrict-access.php");

if(isset($_GET['D_ID'])){
	$D_ID = $_GET['D_ID'];

	$sql_info = "SELECT d.Title, d.Fname, d.Lname, u.Photo
		FROM doctor d, user_details u
		WHERE d.ID = u.ID AND d.ID = $D_ID";

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

        echo '<table align="center" border="1">
		<tr>
			<td colspan="5">
				<div id="joy_congratulation" align="center">
					All Awards History
				</div>
			</td>
		</tr>';


$sql_awards_logs = "SELECT Award_Name, Awarding_Organization, Year_Received, Description
                    FROM awards_honors
                    WHERE D_ID = $D_ID
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
								<form action="/chms/admin/doctor/awards-honors.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
									<input type="hidden" id="Award_Name" name="Award_Name" value="'.$row['Award_Name'].'">
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
        	<td colspan="5">
				<div id="joy_warning" align="center">
					No Awards History
				</div>
			</td>';
        }

        
}

else if(isset($_POST['delete'])){
	$D_ID = $_POST['D_ID'];
	$Award_Name = $_POST['Award_Name'];

	$sql_delete_data = "DELETE FROM awards_honors
						WHERE D_ID = $D_ID AND  Award_Name = '$Award_Name'";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Awards Info Deleted Successfully..');window.location.href = '/chms/admin/doctor/awards-honors.php?D_ID=".$D_ID."';</script>";
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