<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");
include("status-check.php");

$User_ID = $_SESSION['User_ID'];

if(isset($_POST['submit'])){
	$ID = $_POST['ID'];
	$Comment = $_POST['Comment'];
	$DateTime = date("Y-m-d H:i:s");

	$target_dir = "report/"; // Specify the directory where you want to save uploaded files
	
	// Define a custom file name (e.g., using a timestamp)
	$custom_file_name = "report-" . $ID; // You can customize this part as needed

	// Get the file extension from the original file name
	$file_extension = pathinfo($_FILES["Report"]["name"], PATHINFO_EXTENSION);

	// Combine the custom file name with the file extension
	$custom_target_file = $target_dir . $custom_file_name . "." . $file_extension;

	// Check if a file was uploaded and if it's a valid file
	if (!empty($_FILES["Report"]["name"])) {
	    if (file_exists($custom_target_file)) {
	        // The file already exists, so delete the old file
	        if (unlink($custom_target_file)) {
	            // Attempt to move the uploaded file to the specified directory with the custom file name
	            if (move_uploaded_file($_FILES["Report"]["tmp_name"], $custom_target_file)) {
	                echo "File " . htmlspecialchars($custom_file_name) . " has been replaced.";
	            } else {
	                echo "Sorry, there was an error uploading your file.";
	            }
	        } else {
	            echo "Sorry, there was an error deleting the old file.";
	        }
	    } else {
	        // The file does not exist, so simply move the uploaded file with the custom name
	        if (move_uploaded_file($_FILES["Report"]["tmp_name"], $custom_target_file)) {
	            echo "File " . htmlspecialchars($custom_file_name) . " has been uploaded.";
	        } else {
	            echo "Sorry, there was an error uploading your file.";
	        }
	    }
	} else {
	    echo "Please select a file to upload.";
	    $custom_target_file = "";
	}


	$sql_update_report = "UPDATE test_log
							SET Comment = '$Comment', Result_Date = '$DateTime', Result = '$custom_target_file'
							WHERE ID = $ID";
	if (mysqli_query($conn, $sql_update_report)){
		echo "<script type=\"text/javascript\">window.alert('Report Uploaded Successfully..');window.location.href = '/chms/diagnostic-center/update-test.php';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);

}

else{
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

if(isset($_POST['update'])){
	$ID = $_POST['ID'];

	echo '
		<caption>
			<div id="joy_congratulation">
				Upload Test Report
			</div>
		</caption>';

$sql_test = "SELECT t.Name, tl.Test_Date, p.Fname, p.Lname
				FROM test t, test_log tl, patient p
				WHERE t.ID = tl.T_ID AND tl.P_ID = p.ID AND tl.ID = $ID";

$result_test = mysqli_query($conn, $sql_test);
$found_details = mysqli_fetch_array($result_test);
$Name = $found_details['Name'];
$Test_Date = $found_details['Test_Date'];
$Fname = $found_details['Fname'];
$Lname = $found_details['Lname'];

echo '
<tr>
	<td colspan="2" align="center">
		<div id="joy_congratulation">
			Test Name: '.$Name.'<br>
			Test Date: '.$Test_Date.'<br>
			Patient Name: '.$Fname.' '.$Lname.'
		</div>
	</td>
</tr>';


		echo'
		<form action="" method="POST" enctype="multipart/form-data">
		<tr>
			<td>
				<div id="joy_congratulation" align="center">
					<label for="Comment">Enter Comment: </label>
				</div>
			</td>
			<td>
				<div id="joy_congratulation" align="center">
					<textarea id="Comment" name="Comment" rows="2" cols="30" required></textarea>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div id="joy_congratulation">
					<label for="Report">Select Report File: </label>
				</div>
			</td>
			<td>
				<div id="joy_congratulation">
					<input type="file" name="Report" id="Report" required>
				</div>
			</td>
		</tr>
		 <tr>
		    <td colspan="2" align="center">
		    	<div id="joy_congratulation">
		    		<input type="hidden" id="ID" name="ID" value="'.$ID.'">
			        <input type="submit" name="submit" value="Submit">    		
		    	</div>
		    </td>
		</tr>
		</form>
		</table>';

}
else{
	echo '
		<caption>
			<div id="joy_congratulation">
				All Pending Test
			</div>
		</caption>
		<tr>
			<th>
				<div id="joy_warning" align="center">
					Test Date
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Test Name
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Patient Name
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Action
				</div>
			</th>
		</tr>';




$sql_all_test = "SELECT tl.ID, t.Name, tl.Test_Date, p.Fname, p.Lname
				FROM test t, test_log tl, patient p
				WHERE t.ID = tl.T_ID AND tl.P_ID = p.ID AND tl.DC_ID = $User_ID AND tl.Result IS NULL
				ORDER BY tl.Test_Date";

	$result_all_test = mysqli_query($conn, $sql_all_test);
	if (mysqli_num_rows($result_all_test) > 0){
		while ($row = mysqli_fetch_assoc($result_all_test)){
			echo '<tr>
					<td>
						<div id="joy_congratulation">
							'.$row['Test_Date'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							'.$row['Name'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							'.$row['Fname'].' '.$row['Lname'].'
						</div>
					</td>
					<td>
						<form action="" method="POST" style="margin: 0px; padding: 0px;">
							<input type="hidden" id="ID" name="ID" value="'.$row['ID'].'">
							<input type="submit" name="update" value="Update">
						</form>
					</td>
				</tr>';
		}
	}
	else{
		echo '<tr>
					<td colspan="4">
						<div id="joy_warning" align="center">
							No Pending Test
						</div>
					</td>
				</tr>';
	}

	echo '</table>';
}
}








ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>All Test List</title>
</head>
<body>

</body>
</html>
