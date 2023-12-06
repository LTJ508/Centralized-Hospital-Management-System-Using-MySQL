<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");
include("status-check.php");

$User_ID = $_SESSION['User_ID'];

if(isset($_POST['claim'])){
	$ID = $_POST['ID'];
	$DateTime = date("Y-m-d H:i:s");
	$sql_claim_test = "UPDATE test_log 
						SET DC_ID = $User_ID, Test_Date = '$DateTime'
						WHERE ID = $ID";
	if (mysqli_query($conn, $sql_claim_test)){
        echo "<script type=\"text/javascript\">window.alert('Test Claimed Successfully..');window.location.href = '/chms/diagnostic-center/claim-test.php';</script>";
	}
    else{
        echo "<script type=\"text/javascript\">window.alert('Test Claimed Unsuccessful..');window.location.href = '/chms/diagnostic-center/claim-test.php';</script>";
    }

}
else if(isset($_POST['unclaim'])){
	$ID = $_POST['ID'];

	$sql_unclaim_test = "UPDATE test_log
						SET DC_ID = NULL , Test_Date = NULL
						WHERE ID =  $ID  ";
	if (mysqli_query($conn, $sql_unclaim_test)){
		echo "<script type=\"text/javascript\">window.alert('Test Unclaimed Successfully..');window.location.href = '/chms/diagnostic-center/claim-test.php';</script>";
    }
    else{
        echo "<script type=\"text/javascript\">window.alert('Test Unclaimed Unsuccessful..');window.location.href = '/chms/diagnostic-center/claim-test.php';</script>";
    }

}
else if(isset($_POST['search'])){
	$Prescription_ID = $_POST['Prescription_ID'];
    echo'
    <form action="" method="POST">
    	<table align="center">
			<caption>
				<div id="joy_congratulation">
					Enter The Prescription ID 
				</div>
			</caption>
			<tr> 
			<td>
					<input type="number" id = "Prescription_ID" name="Prescription_ID" required value="'.$Prescription_ID.'">
				</td>
				<td>
					<input type="submit" name="search" value="Search">
				</td>
			</tr>
		</table>
    </form>';

	$sql_patient = "SELECT p.Fname, p.Lname, p.DOB, p.Gender, u.Photo
					FROM user_details u, patient p
					WHERE u.ID = p.ID AND u.ID = (SELECT P_ID
													FROM prescriptions
													WHERE ID = $Prescription_ID)";

	$result_patient = mysqli_query($conn, $sql_patient);

    if(mysqli_num_rows($result_patient) > 0){
    	$found_patient = mysqli_fetch_array($result_patient);

    	$Fname = $found_patient['Fname'];
    	$Lname = $found_patient['Lname'];
    	$DOB  = $found_patient['DOB'];
    	$Gender = $found_patient['Gender'];
    	$Photo = $found_patient['Photo'];


    	echo'
    	<table border="1" align="center">
    		<caption>
    			<div id="joy_congratulation">
    				Patient Information
    			</div>
    		</caption>
    	     <tr>
    	     	<td colspan="3">
    	     		<div style="display: flex;">';
						if($Photo == ""){
							echo '<img src="/chms/patient/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px">';
						}
						else{
							echo '<img src="/chms/patient/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px">';
						}
						echo '
						<div id="joy_congratulation">
							Name: '.$Fname .' '. $Lname .'<br>
							Age: '.calculateAge($DOB).' Years<br>
							Gender: '.$Gender.'
						</div>
					</div>						
    	     	</td> 
    	     </tr>';


    	$query_test_list = "SELECT t.Name, t.Price, tl.ID , tl.DC_ID
    						FROM test t , test_log tl
    						WHERE tl.T_ID = t.ID AND tl.Prescription_ID = $Prescription_ID";

    	$result_test_list = mysqli_query($conn, $query_test_list);

    	if(mysqli_num_rows($result_test_list) > 0){
    		echo '
	        	<tr>
					<th>
						<div id="joy_warning" align="center">
							Test Name
						</div>
					</th>
					<th>
						<div id="joy_warning" align="center">
							Price
						</div>
					</th>
					<th>
						<div id="joy_warning" align="center">
							Action
						</div>
					</th>
				</tr>';
			while($row = mysqli_fetch_assoc($result_test_list)){
				echo '<tr>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Name'].'
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">
								'.$row['Price'].' TK
							</div>
						</td>
						<td>
							<div id="joy_congratulation" align="center">';
								if($row['DC_ID'] == ""){
									echo '
									<form action="" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
										<input type="hidden" id="ID" name="ID" value="'.$row['ID'].'">
										<input type="submit" name="claim" value="Claim">
									</form>';
								}
								else if($row['DC_ID'] == $User_ID){
									echo '
									<form action="" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
										<input type="hidden" id="ID" name="ID" value="'.$row['ID'].'">
										<input type="submit" name="unclaim" value="Unclaim">
									</form>';
								}
								else{
									$sql_dc_name = "SELECT Name
													FROM diagnostic_center
													WHERE ID = ".$row['DC_ID']."";
									$result_dc_name = mysqli_query($conn, $sql_dc_name);
									$found_diagnostic_center = mysqli_fetch_array($result_dc_name);
    								$DCName = $found_diagnostic_center['Name'];

    								echo '<input type="button" onclick="alert('.$DCName.')" value="Already Claimed!">';
								}
							echo '
							</div>
						</td>
					</tr>';
			}
			echo '</table>';
		}
		else{
    		echo '
    		<tr>
    			<td colspan="3" align="center">
    				<div id="joy_warning">
						No Test Given
					</div>
    			</td>
    		</tr>';
    	}
    }
    else{
    	echo "<script type=\"text/javascript\">window.alert('Invalid Prescription ID');window.location.href = '/chms/diagnostic-center/claim-test.php';</script>";
    }
}
else{    
    echo'
    <form action="" method="POST">
    	<table align="center">
			<caption>
				<div id="joy_congratulation">
					Enter The Prescription ID 
				</div>
			</caption>
			<tr> 
			<td>
					<input type="number" id = "Prescription_ID" name="Prescription_ID" required>
				</td>
				<td>
					<input type="submit" name="search" value="Search">
				</td>
			</tr>
		</table>
    </form>';
}


ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Claim Test - Diagnostic Center</title>
</head>
<body>
    <script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>