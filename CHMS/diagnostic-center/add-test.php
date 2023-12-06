<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");
include("status-check.php");

$User_ID = $_SESSION['User_ID'];

$sql_info = "SELECT dc.Name, u.Photo
		FROM diagnostic_center dc, user_details u
		WHERE dc.ID = u.ID AND dc.ID = $User_ID";

		$result_info = mysqli_query($conn, $sql_info);
		$found_user = mysqli_fetch_array($result_info);

        $Name = $found_user['Name'];
        $Photo = $found_user['Photo'];

        echo '<table align="center" border="1">';

        if($Photo == ""){
			echo '<div align="center"><img src="/chms/diagnostic-center/img/null.jpg" alt="'.$Name.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/diagnostic-center/'.$Photo.'" alt="'.$Name.'" width="120px" height="120px"></div>';
		}

		echo '<div id="joy_congratulation" align="center">
		'. $Name . '</div>';

if(isset($_POST['add'])){
	$P_ID = $_POST['P_ID'];
	$T_ID = $_POST['T_ID'];
	$DateTime = date("Y-m-d H:i:s");

	$sql_add_test = "INSERT INTO test_log(T_ID, P_ID, DC_ID, Test_Date)
						VALUES($T_ID, $P_ID, $User_ID, '$DateTime')";
	if (mysqli_query($conn, $sql_add_test)){
				echo "<script type=\"text/javascript\">window.alert('Test Added Successfully..');window.location.href = '/chms/diagnostic-center/test.php';</script>";
		} 
		else{
			echo "Error: " . $sql . "" . mysqli_error($conn);
 		}
 		mysqli_close($conn);
}


if(isset($_POST['search'])){
	$P_ID = $_POST['P_ID'];
	echo '
		<caption>
		<div id="joy_congratulation">
			Search Patient
		</div>
	</caption>
	<form action="" method="POST" style="margin: 0px; padding: 0px;">
	<tr>
		<td>
			<input type="number" name="P_ID" placeholder="Patient ID" value="'.$P_ID.'" required>
		</td>
		<td>
			<input type="submit" name="search" value="Search">
		</td>
	</tr>
	</form></table>';

	$sql_patient = "SELECT p.Fname, p.Lname, p.DOB, p.Gender, u.Photo
					FROM user_details u, patient p
					WHERE u.ID = p.ID AND u.ID = $P_ID";

	$result_patient = mysqli_query($conn, $sql_patient);

    if(mysqli_num_rows($result_patient) == 1){
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
    	     	<td>
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
    	     </tr></table>';


    	 echo'
    	<table border="1" align="center">
    		<caption>
    			<div id="joy_congratulation">
    				Add Test
    			</div>
    		</caption>
    		<form action="" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
    	     <tr>
    	     	<th>
    	     		<div id="joy_congratulation">
    	     			<label for="T_ID">Select Test</label>
    	     		</div>						
    	     	</th>
    	     	<td>
    	     		<div id="joy_congratulation">
    	     			<select id="T_ID" name="T_ID" required>';

    	     			$sql_test_list  ="SELECT ID, Name, Price
    	     								FROM test
    	     								WHERE Status = TRUE
    	     								ORDER BY Name";
    	     			$result_test_list = mysqli_query($conn, $sql_test_list);
    	     			if(mysqli_num_rows($result_test_list) > 0){
    	     				while($row = mysqli_fetch_assoc($result_test_list)){
    	     					echo '<option value="'.$row['ID'].'">'.$row['Name'].' - '.$row['Price'].' TK</option>';
    	     				}
    	     			}

    	     			echo '
						</select>
    	     		</div>
    	     	</td>
    	     </tr>
    	     <tr>
    	     	<td colspan="2" align="center">
    	     		<div id="joy_congratulation">
    	     			<input type="hidden" id="P_ID" name="P_ID" value="'.$P_ID.'">
    	     			<input type="submit" name="add" value="Add Test">
    	     		</div>
    	     	</td>
    	     </tr></form></table>';


   	}
   	else{
   		echo'
    	<table border="1" align="center">
    		<caption>
    			<div id="joy_congratulation">
    				Patient Information
    			</div>
    		</caption>
    	     <tr>
    	     	<td>
    	     		<div id="joy_warning">
	    				Patient Not Found!!
	    			</div>						
    	     	</td> 
    	     </tr></table>';
   	}
}
else{
	echo '
		<caption>
		<div id="joy_congratulation">
			Search Patient
		</div>
	</caption>
	<form action="" method="POST" style="margin: 0px; padding: 0px;">
	<tr>
		<td>
			<input type="number" name="P_ID" placeholder="Patient ID" required>
		</td>
		<td>
			<input type="submit" name="search" value="Search">
		</td>
	</tr>
	</form></table>';
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
            var userConfirmed = confirm("Are you sure you want to add this test?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>

