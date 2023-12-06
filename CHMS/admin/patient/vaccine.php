<?php
ob_start();
include("../../common/header-content.php");
include("../../common/functions.php");
include("restrict-access.php");

if(isset($_GET['P_ID'])){
	$P_ID = $_GET['P_ID'];
	$sql_info = "SELECT p.Fname, p.Lname, u.Photo
                FROM patient p, user_details u
                WHERE p.ID = u.ID AND p.ID = $P_ID";

        $result_info = mysqli_query($conn, $sql_info);
        $found_user = mysqli_fetch_array($result_info);

        
        $Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];
        $Photo = $found_user['Photo'];

        echo '<table align="center" >';

        if($Photo == ""){
            echo '<div align="center"><img src="/chms/patient/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px"></div>';
        }
        else{
            echo '<div align="center"><img src="/chms/patient/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px"></div>';
        }

        echo '<div id="joy_congratulation" align="center">'.
            $Fname . ' '. $Lname .'</div>';

echo '
		<table align="center" border="1">
		<tr>
			<td colspan="5">
				<div id="joy_congratulation" align="center">
					All Vaccine History
				</div>
			</td>
		</tr>';


$sql_vaccine_logs = "SELECT v.Vaccine_Name, v.Doss_Number, v.Date, v.Status
                    FROM vaccination_logs v
                    WHERE P_ID = $P_ID
                    ORDER BY Date";

        $result_vaccine_logs = mysqli_query($conn, $sql_vaccine_logs);
        if (mysqli_num_rows($result_vaccine_logs) > 0){

        	echo '
        	<tr>
				<td>
					<div id="joy_warning" align="center">
						Vaccine Name
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Dose Number
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Date
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Status
					</div>
				</td>
				<td>
					<div id="joy_warning" align="center">
						Action
					</div>
				</td>
			</tr>';

            while ($row = mysqli_fetch_assoc($result_vaccine_logs)){
            	$Status = "";
            	if($row['Status'] == TRUE){
            		$Status = "Approved";
            	}
            	else{
            		$Status = "Not Approved";
            	}


                echo '<tr>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Vaccine_Name'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Doss_Number'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Date'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$Status.'
							</div>
						</td>
						<td>
							<div id="joy_warning">
								<form action="/chms/admin/patient/vaccine.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
									<input type="hidden" id="Vaccine_Name" name="Vaccine_Name" value="'.$row['Vaccine_Name'].'">
									<input type="hidden" id="Doss_Number" name="Doss_Number" value="'.$row['Doss_Number'].'">
									<input type="hidden" id="P_ID" name="P_ID" value="'.$P_ID.'">
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
					No Vaccine History
				</div>
			</td>';
        }


}
else if(isset($_POST['delete'])){
	$P_ID = $_POST['P_ID'];
	$Vaccine_Name = $_POST['Vaccine_Name'];
	$Doss_Number = $_POST['Doss_Number'];

	$sql_delete_data = "DELETE FROM vaccination_logs
						WHERE P_ID = $P_ID AND  Vaccine_Name = '$Vaccine_Name' AND Doss_Number = $Doss_Number";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Vaccine Info Deleted Successfully..');window.location.href = '/chms/admin/patient/vaccine.php?P_ID=".$P_ID."';</script>";
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
	<title>Vaccine History- Patient</title>
</head>
<body>
	<script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to delete this vaccine info?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>