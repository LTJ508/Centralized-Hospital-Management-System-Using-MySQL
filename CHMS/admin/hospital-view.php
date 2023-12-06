<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];



if(isset($_POST['view']) || isset($_GET['H_ID'])){

	if(isset($_POST['view'])){
		$H_ID = $_POST['H_ID'];
	}
	else{
		$H_ID = $_GET['H_ID'];
	}

	$sql_info = "SELECT h.ID, h.License_Number, h.Name, u.Phone_Number, u.Email, u.Address, u.Photo, u.Status
				FROM hospital h, user_details u
				WHERE h.ID = u.ID AND h.ID = $H_ID";
	$result_info = mysqli_query($conn, $sql_info);

	if (mysqli_num_rows($result_info) == 1){
	 	
        $found_user = mysqli_fetch_array($result_info);

        
        $ID = $found_user['ID'];
        $License_Number = $found_user['License_Number'];
        $Name = $found_user['Name'];
        $Phone_Number = $found_user['Phone_Number'];
        $Email = $found_user['Email'];
        $Address = $found_user['Address'];
        $Photo = $found_user['Photo'];
        $Status = $found_user['Status'];

        echo '<table border="1" align="center">';

        if($Photo == ""){
			echo '<div align="center"><img src="/chms/hospital/img/null.jpg" alt="'.$Name.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/hospital/'.$Photo.'" alt="'.$Name.'" width="120px" height="120px"></div>';
		}

        echo '
        	<caption>
        		<div id="joy_congratulation" align="center">
        		'. $Name . '('.$ID.')
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
				<td colspan="2" align="center">
					<div id="joy_warning" align="center">';
					if($Status == 1){
						echo '<form action="/chms/admin/hospital-view.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
	                            <input type="hidden" id="H_ID" name="H_ID" value="'.$ID.'">
	                            <input type="submit" name="disable" value="Make Disable">
	                        </form>';
					}
					else{
						echo '<form action="/chms/admin/hospital-view.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
	                            <input type="hidden" id="H_ID" name="H_ID" value="'.$ID.'">
	                            <input type="submit" name="enable" value="Make Enable">
	                        </form>';
					}
						echo '
                        
					</div>
				</td>
			</tr>
		</table>
            ';



echo '<div id="joy_warning" align="center" style="font-size: 30; font-weight: bold;">Activity</div>
    <table align="center" class="joy">
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/hospital/appoinment.php?H_ID='.$H_ID.'" target="_blank">View Appointment</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/hospital/consultation.php?H_ID='.$H_ID.'" target="_blank">Consultation List</a>
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
	$H_ID = $_POST['H_ID'];

	$sql_delete = "DELETE FROM user_details
					WHERE ID = $H_ID";
	if (mysqli_query($conn, $sql_delete)){
		echo "<script type=\"text/javascript\">window.alert('Account Deleted Successfully..');window.location.href = '/chms/admin/';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);
}
else if(isset($_POST['enable'])){
	$H_ID = $_POST['H_ID'];
	$sql_enable = "UPDATE user_details
					SET Status = 1
					WHERE ID = $H_ID";
	if(mysqli_query($conn, $sql_enable)){
		echo "<script type=\"text/javascript\">window.alert('Account Enabled Successfully..');window.location.href = '/chms/admin/hospital-view.php?H_ID=".$H_ID."';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);
}
else if(isset($_POST['disable'])){
	$H_ID = $_POST['H_ID'];
	$sql_disable = "UPDATE user_details
					SET Status = 0
					WHERE ID = $H_ID";
	if (mysqli_query($conn, $sql_disable)){
		echo "<script type=\"text/javascript\">window.alert('Account Disabled Successfully..');window.location.href = '/chms/admin/hospital-view.php?H_ID=".$H_ID."';</script>";
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
    <title>Hospital Details</title>
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


