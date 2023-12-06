<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];



if(isset($_POST['view']) || isset($_GET['DC_ID'])){

	if(isset($_POST['view'])){
		$DC_ID = $_POST['DC_ID'];
	}
	else{
		$DC_ID = $_GET['DC_ID'];
	}

	$sql_info = "SELECT dc.ID, dc.License_Number, dc.Name, u.Phone_Number, u.Email, u.Address, u.Photo, u.Status
				FROM diagnostic_center dc, user_details u
				WHERE dc.ID = u.ID AND dc.ID = $DC_ID";

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
			echo '<div align="center"><img src="/chms/diagnostic-center/img/null.jpg" alt="'.$Name.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/diagnostic-center/'.$Photo.'" alt="'.$Name.'" width="120px" height="120px"></div>';
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
						echo '<form action="/chms/admin/diagnostic-center-view.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
	                            <input type="hidden" id="DC_ID" name="DC_ID" value="'.$ID.'">
	                            <input type="submit" name="disable" value="Make Disable">
	                        </form>';
					}
					else{
						echo '<form action="/chms/admin/diagnostic-center-view.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
	                            <input type="hidden" id="DC_ID" name="DC_ID" value="'.$ID.'">
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
                    <a href="/chms/admin/diagnostic-center/test.php?DC_ID='.$DC_ID.'" target="_blank">View All Test</a>
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
	$DC_ID = $_POST['DC_ID'];

	$sql_delete = "DELETE FROM user_details
					WHERE ID = $DC_ID";
	if (mysqli_query($conn, $sql_delete)){
		echo "<script type=\"text/javascript\">window.alert('Account Deleted Successfully..');window.location.href = '/chms/admin/';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);
}
else if(isset($_POST['enable'])){
	$DC_ID = $_POST['DC_ID'];
	$sql_enable = "UPDATE user_details
					SET Status = 1
					WHERE ID = $DC_ID";
	if(mysqli_query($conn, $sql_enable)){
		echo "<script type=\"text/javascript\">window.alert('Account Enabled Successfully..');window.location.href = '/chms/admin/diagnostic-center-view.php?DC_ID=".$DC_ID."';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);
}
else if(isset($_POST['disable'])){
	$DC_ID = $_POST['DC_ID'];
	$sql_disable = "UPDATE user_details
					SET Status = 0
					WHERE ID = $DC_ID";
	if (mysqli_query($conn, $sql_disable)){
		echo "<script type=\"text/javascript\">window.alert('Account Disabled Successfully..');window.location.href = '/chms/admin/diagnostic-center-view.php?DC_ID=".$DC_ID."';</script>";
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
    <title>Diagnostic Center Details</title>
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


