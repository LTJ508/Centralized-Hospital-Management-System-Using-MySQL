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
<tr>
	<td colspan="2" align="center">
		<div id="joy_congratulation">
			Allergy History
		</div>
	</td>
</tr>';


$sql_allergy_logs = "SELECT Allergies
                    FROM allergies_logs
                    WHERE P_ID = $P_ID
                    ORDER BY Allergies";

        $result_allergy_logs = mysqli_query($conn, $sql_allergy_logs);
        if (mysqli_num_rows($result_allergy_logs) > 0){
            while ($row = mysqli_fetch_assoc($result_allergy_logs)){
                echo '<tr>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Allergies'].'
							</div>
						</td>
						<td>
							<div id="joy_warning">
								<form action="/chms/admin/patient/allergy.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
									<input type="hidden" id="d_allergy" name="d_allergy" value="'.$row['Allergies'].'">
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
					No Allergy History
				</div>
			</td>';
        }
}
else if(isset($_POST['delete'])){
	$P_ID = $_POST['P_ID'];
	$allergy = $_POST['d_allergy'];

	$sql_delete_data = "DELETE FROM allergies_logs
						WHERE P_ID = $P_ID AND  Allergies = '$allergy'";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Allergy Deleted Successfully..');window.location.href = '/chms/admin/patient/allergy.php?P_ID=".$P_ID."';</script>";
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
	<title>Allergy History - Patient</title>
</head>
<body>
	<script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to delete this allergy info?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>


