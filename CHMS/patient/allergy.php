<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];


if(isset($_POST['add'])){
	$allergy = $_POST['allergy'];

	$sql_insert_allergy = "INSERT INTO allergies_logs(P_ID, Allergies) VALUES
							($User_ID, '$allergy')";

	if (mysqli_query($conn, $sql_insert_allergy)){
				echo "<script type=\"text/javascript\">window.alert('New Allergy History Added Successfully..');window.location.href = '/chms/patient/allergy.php';</script>";
			} 
			else{
				echo "Error: " . $sql . "" . mysqli_error($conn);
	 		}
	 		mysqli_close($conn);

}
else if(isset($_POST['delete'])){
	$allergy = $_POST['d_allergy'];

	$sql_delete_data = "DELETE FROM allergies_logs
						WHERE P_ID = $User_ID AND  Allergies = '$allergy'";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Allergy Deleted Successfully..');window.location.href = '/chms/patient/allergy.php';</script>";
			} 
			else{
				echo "Error: " . $sql . "" . mysqli_error($conn);
	 		}
	 		mysqli_close($conn);
}





$sql_info = "SELECT p.Fname, p.Lname, u.Photo
                FROM patient p, user_details u
                WHERE p.ID = u.ID AND p.ID = $User_ID";

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


echo '<form action="" method="POST" style="margin: 0px; padding: 0px;">
<tr>
	<td colspan="2" align="left">
		<div id="joy_congratulation">
			<label for="allergy">Add New Allergy</label>
		</div>
	</td>
</tr>
<tr>
	<td>
		<input type="text" name="allergy" id="allergy" placeholder="Allergy Name" required>
	</td>
	<td>
		<input type="submit" name="add" value="Add">
	</td>
</tr>
</form>
<tr>
	<td colspan="2" align="center">
		<div id="joy_congratulation">
			Allergy History
		</div>
	</td>
</tr>';


$sql_allergy_logs = "SELECT Allergies
                    FROM allergies_logs
                    WHERE P_ID = ".$User_ID."
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
								<form action="" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
									<input type="hidden" id="d_allergy" name="d_allergy" value="'.$row['Allergies'].'">
									<input type="submit" name="delete" value="Delete">
								</form>
							</div>
						</td>
					</tr>';
            }
        }
        else{
		echo '
		<tr>
			<td colspan="2" align="center">
				<div id="joy_warning">
					No Allergy History
				</div>
			</td>
		</tr>';
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


