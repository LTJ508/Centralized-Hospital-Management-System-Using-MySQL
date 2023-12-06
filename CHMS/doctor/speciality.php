<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];



if(isset($_POST['add'])){
	$Speciality = $_POST['Speciality'];

	$sql_check = "SELECT *
					FROM speciality
					WHERE D_ID = $User_ID AND Field = '$Speciality'";
	if(mysqli_num_rows(mysqli_query($conn, $sql_check)) > 0){
		echo "<script type=\"text/javascript\">window.alert('Department Already Exist!!!');window.location.href = '/chms/doctor/speciality.php';</script>";
	}
	else{
		$sql_insert_field = "INSERT INTO speciality(D_ID, Field)
							VALUES ($User_ID, '$Speciality')";

		if (mysqli_query($conn, $sql_insert_field)){
				echo "<script type=\"text/javascript\">window.alert('New Speciality Added Successfully..');window.location.href = '/chms/doctor/speciality.php';</script>";
		} 
		else{
			echo "Error: " . $sql . "" . mysqli_error($conn);
 		}
 		mysqli_close($conn);
	}
}
else if(isset($_POST['delete'])){
	$Field = $_POST['Field'];

	$sql_delete_data = "DELETE FROM speciality
						WHERE D_ID = $User_ID AND  Field = '$Field'";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Department Info Deleted Successfully..');window.location.href = '/chms/doctor/speciality.php';</script>";
			} 
			else{
				echo "Error: " . $sql . "" . mysqli_error($conn);
	 		}
	 		mysqli_close($conn);
}




$sql_info = "SELECT d.Title, d.Fname, d.Lname, u.Photo
		FROM doctor d, user_details u
		WHERE d.ID = u.ID AND d.ID = ".$User_ID."";

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
			<td colspan="2">
				<div id="joy_congratulation" align="center">
					Add Speciality or Department
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<select name="Speciality" id="Speciality" required>
                    <option value="NULL">Select A Department</option>
                    <option value="Anaesthesia">Anaesthesia</option>
                    <option value="Blood Bank">Blood Bank</option>
                    <option value="Breast, Colorectal & Laparoscopic Surgery">Breast, Colorectal &amp; Laparoscopic Surgery</option>
                    <option value="Cardiac surgery">Cardiac surgery</option>
                    <option value="Cardiology">Cardiology</option>
                    <option value="Clinical Hematology">Clinical Hematology</option>
                    <option value="Colorectal Surgery">Colorectal Surgery</option>
                    <option value="Corporate Affairs Department">Corporate Affairs Department</option>
                    <option value="Dental and Maxillofacial Surgery">Dental and Maxillofacial Surgery</option>
                    <option value="Dermatology">Dermatology</option>
                    <option value="Diabetes & Endocrinology">Diabetes &amp; Endocrinology</option>
                    <option value="EMERGENCY">EMERGENCY</option>
                    <option value="ENT, Head & Neck Surgery">ENT, Head &amp; Neck Surgery</option>
                    <option value="Gastroenterology & Hepatology">Gastroenterology &amp; Hepatology</option>
                    <option value="General & Laparoscopic Surgery">General &amp; Laparoscopic Surgery</option>
                    <option value="Health Check Up">Health Check Up</option>
                    <option value="Hepatobiliary & Pancreatic Surgery">Hepatobiliary &amp; Pancreatic Surgery</option>
                    <option value="ICU">ICU</option>
                    <option value="Internal Medicine">Internal Medicine</option>
                    <option value="Laboratory Medicine">Laboratory Medicine</option>
                    <option value="MRD SERVICES">MRD SERVICES</option>
                    <option value="Nephrology">Nephrology</option>
                    <option value="Neurology">Neurology</option>
                    <option value="Neurosurgery">Neurosurgery</option>
                    <option value="NICU">NICU</option>
                    <option value="Nutrition & Dietetic Department">Nutrition &amp; Dietetic Department</option>
                    <option value="Obstetrics & Gynecology">Obstetrics &amp; Gynecology</option>
                    <option value="Oncology">Oncology</option>
                    <option value="Orthopedics, Arthoscopy & Joint Replacement">Orthopedics, Arthoscopy &amp; Joint Replacement</option>
                    <option value="Pediatric & Neonatology">Pediatric &amp; Neonatology</option>
                    <option value="Pediatric Surgery">Pediatric Surgery</option>
                    <option value="Physical Medicine">Physical Medicine</option>
                    <option value="Plastic & Aesthetic Surgery">Plastic &amp; Aesthetic Surgery</option>
                    <option value="Psychiatry">Psychiatry</option>
                    <option value="Radiology & Imaging">Radiology &amp; Imaging</option>
                    <option value="Respiratory Medicine">Respiratory Medicine</option>
                    <option value="Rheumatology">Rheumatology</option>
                    <option value="Surgical Oncology">Surgical Oncology</option>
                    <option value="Thoracic Surgery">Thoracic Surgery</option>
                    <option value="Urology">Urology</option>
                    <option value="Vaccination Center">Vaccination Center</option>
                    <option value="Vascular Surgery">Vascular Surgery</option>
                </select>
			</td>
			<td>
				<input type="submit" name="add" value="Add">
			</td>
		</tr>
	</form>
	</table>
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
                    WHERE D_ID = $User_ID
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
								<form action="" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
									<input type="hidden" id="Field" name="Field" value="'.$row['Field'].'">
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