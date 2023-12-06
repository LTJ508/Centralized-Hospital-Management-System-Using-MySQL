<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];



if(isset($_POST['view']) || isset($_GET['D_ID'])){

	if(isset($_POST['view'])){
		$D_ID = $_POST['D_ID'];
	}
	else{
		$D_ID = $_GET['D_ID'];
	}

	$sql_info = "SELECT d.ID, d.License_Number, d.Title, d.Fname, d.Lname, d.DOB, d.Gender, u.Phone_Number, u.Email, u.Address, d.Experience_From, d.Language, d.Current_Position, d.P_Location, u.Photo, u.Status
				FROM doctor d, user_details u
				WHERE d.ID = u.ID AND d.ID = $D_ID";

        $result_info = mysqli_query($conn, $sql_info);


         if (mysqli_num_rows($result_info) == 1){
         	$found_user = mysqli_fetch_array($result_info);

        
        $ID = $found_user['ID'];
        $License_Number = $found_user['License_Number'];
        $Title = $found_user['Title'];
        $Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];
        $DOB = $found_user['DOB'];
        $Gender = $found_user['Gender'];
        $Phone_Number = $found_user['Phone_Number'];
        $Email = $found_user['Email'];
        $Address = $found_user['Address'];
        $Experience_From = $found_user['Experience_From'];
        $Language = $found_user['Language'];
        $Current_Position = $found_user['Current_Position'];
        $P_Location = $found_user['P_Location'];
        $Photo = $found_user['Photo'];
        $Status = $found_user['Status'];

        echo '<table border="1" align="center">';

        if($Photo == ""){
			echo '<div align="center"><img src="/chms/doctor/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/doctor/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px"></div>';
		}

        echo '
        	<caption>
        		<div id="joy_congratulation" align="center">
        		'.$Title.' Dr. '.$Fname.' '.$Lname.'('.$ID.')
            	</div>
            </caption>
            <tr>
				<th>
					<div id="joy_congratulation" align="center">
						License Number:
					</div>
				</th>
				<td>
					<div id="joy_congratulation" align="center">
						'.$License_Number.'
					</div>
				</td>
			</tr>
            <tr>
				<th>
					<div id="joy_congratulation" align="center">
						Date of Birth:
					</div>
				</th>
				<td>
					<div id="joy_congratulation" align="center">
						'.$DOB.'('.calculateAge($DOB).' Years)
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<div id="joy_congratulation" align="center">
						Gender:
					</div>
				</th>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Gender.'
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<div id="joy_congratulation" align="center">
						Phone Number:
					</div>
				</th>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Phone_Number.'
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<div id="joy_congratulation" align="center">
						Email:
					</div>
				</th>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Email.'
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<div id="joy_congratulation" align="center">
						Address:
					</div>
				</th>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Address.'
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<div id="joy_congratulation" align="center">
						Experience From:
					</div>
				</th>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Experience_From.'('.calculateAge($Experience_From).' Years)
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<div id="joy_congratulation" align="center">
						Language:
					</div>
				</th>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Language.'
					</div>
				</td>
			</tr>
			<tr>
				<th>
					<div id="joy_congratulation" align="center">
						Current Position:
					</div>
				</th>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Current_Position.'<br>
						at '.$P_Location.'
					</div>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<div id="joy_warning" align="center">';
					if($Status == 1){
						echo '<form action="/chms/admin/doctor-view.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
	                            <input type="hidden" id="D_ID" name="D_ID" value="'.$ID.'">
	                            <input type="submit" name="disable" value="Make Disable">
	                        </form>';
					}
					else{
						echo '<form action="/chms/admin/doctor-view.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
	                            <input type="hidden" id="D_ID" name="D_ID" value="'.$ID.'">
	                            <input type="submit" name="enable" value="Make Enable">
	                        </form>';
					}
						echo '
                        
					</div>
				</td>
			</tr>
		</table>
            ';
echo '<div id="joy_warning" align="center" style="font-size: 30; font-weight: bold;">Dashboard</div>
    <table align="center" class="joy">
        <tr> 
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/doctor/prescription.php?D_ID='.$D_ID.'" target="_blank">View All Prescription</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/doctor/appoinment.php?D_ID='.$D_ID.'" target="_blank">Check Appointment</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/doctor/awards-honors.php?D_ID='.$D_ID.'" target="_blank">Awards & Honors</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/doctor/speciality.php?D_ID='.$D_ID.'" target="_blank">Speciality</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/doctor/degree.php?D_ID='.$D_ID.'" target="_blank">Degree</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/doctor/journals-publications.php?D_ID='.$D_ID.'" target="_blank">Journals & Publications</a>
                </div>
            </td>
        </tr>
        <tr> 
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/doctor/consultation.php?D_ID='.$D_ID.'" target="_blank">Consultation Hours</a>
                </div>
            </td>
            <td colspan="2">
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/doctor/review.php?D_ID='.$D_ID.'" target="_blank">Reviews</a>
                </div>
            </td>
        </tr>
    </table>';
         }

         else{
        	echo "<script type=\"text/javascript\">window.alert('Profile Not Completed!!!');window.location.href = '/chms/admin/';</script>";
        }
        



}
else if(isset($_POST['delete'])){
	$D_ID = $_POST['D_ID'];

	$sql_delete = "DELETE FROM user_details
					WHERE ID = $D_ID";
	if (mysqli_query($conn, $sql_delete)){
		echo "<script type=\"text/javascript\">window.alert('Account Deleted Successfully..');window.location.href = '/chms/admin/';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);
}
else if(isset($_POST['enable'])){
	$D_ID = $_POST['D_ID'];
	$sql_enable = "UPDATE user_details
					SET Status = 1
					WHERE ID = $D_ID";
	if(mysqli_query($conn, $sql_enable)){
		echo "<script type=\"text/javascript\">window.alert('Account Enabled Successfully..');window.location.href = '/chms/admin/doctor-view.php?D_ID=".$D_ID."';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);
}
else if(isset($_POST['disable'])){
	$D_ID = $_POST['D_ID'];
	$sql_disable = "UPDATE user_details
					SET Status = 0
					WHERE ID = $D_ID";
	if (mysqli_query($conn, $sql_disable)){
		echo "<script type=\"text/javascript\">window.alert('Account Disabled Successfully..');window.location.href = '/chms/admin/doctor-view.php?D_ID=".$D_ID."';</script>";
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
    <title>Doctor Details</title>
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


