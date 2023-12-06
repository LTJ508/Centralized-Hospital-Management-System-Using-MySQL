<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];



if(isset($_POST['view']) || isset($_GET['P_ID'])){
	if(isset($_POST['view'])){
		$P_ID = $_POST['P_ID'];
	}
	else{
		$P_ID = $_GET['P_ID'];
	}

	$sql_info = "SELECT p.ID, p.Fname, p.Lname, p.DOB, p.Gender, u.Phone_Number, p.Emergency_Number, u.Email, u.Address, p.Blood, p.Weight, 		u.Photo
				FROM patient p, user_details u
				WHERE p.ID = u.ID AND p.ID = $P_ID";

        $result_info = mysqli_query($conn, $sql_info);

        if (mysqli_num_rows($result_info) == 1){
        	$found_user = mysqli_fetch_array($result_info);

        
        $ID = $found_user['ID'];
        $Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];
        $DOB = $found_user['DOB'];
        $Gender = $found_user['Gender'];
        $Phone_Number = $found_user['Phone_Number'];
        $Emergency_Number = $found_user['Emergency_Number'];
        $Email = $found_user['Email'];
        $Address = $found_user['Address'];
        $Blood = $found_user['Blood'];
        $Weight = $found_user['Weight'];
        $Photo = $found_user['Photo'];

        echo '<table border="1" align="center">';

        if($Photo == ""){
            echo '<div align="center"><img src="/chms/patient/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px"></div>';
        }
        else{
            echo '<div align="center"><img src="/chms/patient/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px"></div>';
        }

        echo '
        	<caption>
        		<div id="joy_congratulation" align="center">
        		'.$Fname.' '.$Lname.'('.$ID.')
            	</div>
            </caption>
            <tr>
				<td>
					<div id="joy_congratulation" align="center">
						Date of Birth:
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$DOB.'('.calculateAge($DOB).' Years)
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div id="joy_congratulation" align="center">
						Gender:
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Gender.'
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div id="joy_congratulation" align="center">
						Phone Number:
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Phone_Number.'
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div id="joy_congratulation" align="center">
						Emergency Number:
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Emergency_Number.'
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div id="joy_congratulation" align="center">
						Email:
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Email.'
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div id="joy_congratulation" align="center">
						Address:
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Address.'
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div id="joy_congratulation" align="center">
						Blood:
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Blood.'
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div id="joy_congratulation" align="center">
						Weight:
					</div>
				</td>
				<td>
					<div id="joy_congratulation" align="center">
						'.$Weight.' KG
					</div>
				</td>
			</tr>
		</table>
            ';


echo '
<div id="joy_warning" align="center" style="font-size: 30; font-weight: bold;">Activity</div>
    <table align="center" class="joy">
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/patient/prescription.php?P_ID='.$P_ID.'" target="_blank">See all Prescription</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/patient/test.php?P_ID='.$P_ID.'" target="_blank">See all Test</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/patient/allergy.php?P_ID='.$P_ID.'" target="_blank">Allergy History</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/patient/vaccine.php?P_ID='.$P_ID.'" target="_blank">Vaccine History</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/patient/review.php?P_ID='.$P_ID.'" target="_blank">Provided Reviews</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/patient/appoinment.php?P_ID='.$P_ID.'" target="_blank">Appoinment</a>
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/patient/card.php?P_ID='.$P_ID.'" target="_blank">Print Patient Card</a>
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
	$P_ID = $_POST['P_ID'];

	$sql_delete = "DELETE FROM user_details
					WHERE ID = $P_ID";
	if (mysqli_query($conn, $sql_delete)){
		echo "<script type=\"text/javascript\">window.alert('Account Deleted Successfully..');window.location.href = '/chms/admin/active-patient.php';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);
}
else{
	redirect_to("/chms/admin/active-patient.php");
}



ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Patient Details</title>
</head>
<body>
     
</body>
</html>



