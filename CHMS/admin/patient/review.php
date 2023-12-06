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


        if($Photo == ""){
            echo '<div align="center"><img src="/chms/patient/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px"></div>';
        }
        else{
            echo '<div align="center"><img src="/chms/patient/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px"></div>';
        }

        echo '<div id="joy_congratulation" align="center">'.
            $Fname . ' '. $Lname .'</div>';

       
		

		echo '<table align="center" border="1">';

		$sql_reviews = "SELECT d.ID, u.Photo, d.Title, d.Fname, d.Lname, dr.Ratings, dr.Date, dr.Comment, dr.Status
						FROM doctor_reviews dr, doctor d, user_details u
						WHERE dr.D_ID = d.ID AND d.ID = u.ID AND dr.P_ID = $P_ID
						ORDER BY dr.Date";

		$result_reviews = mysqli_query($conn, $sql_reviews);
		if (mysqli_num_rows($result_reviews) > 0){
			while ($row = mysqli_fetch_assoc($result_reviews)){
				$Status = "";
				if($row['Status'] == TRUE){
					$Status = "Approved";
				}
				else{
					$Status = "Not Approved";
				}

				echo '<tr><td>';
				if($row['Photo'] == ""){
					echo '<div align="center"><img src="/chms/doctor/img/null.jpg" alt="'.$row['Fname'].' '.$row['Lname'].'" width="200px" height="200px"></div></td>';
				}
				else{
					echo '<div align="center"><img src="/chms/doctor/'.$row['Photo'].'" alt="'.$row['Fname'].' '.$row['Lname'].'" width="120px" height="120px"></div></td>';
				}

				echo '<td><div id="joy_congratulation">
						'.$row['Title'].' Dr. '.$row['Fname'].' '. $row['Lname'] .' - '. $row['Ratings'].'* - '. $row['Date'] .'';
				echo '<br>';
				echo 'Comment: '.$row['Comment'].'';
				echo '<br>';
				echo 'Status: '.$Status.'';
				echo '<br>';
				echo '<form action="/chms/admin/patient/review.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
						<input type="hidden" id="D_ID" name="D_ID" value="'.$row['ID'].'">
						<input type="hidden" id="P_ID" name="P_ID" value="'.$P_ID.'">
						<input type="submit" name="delete" value="Delete">
					</form>';
				echo '</div></td></tr>';

			}
		}

		 else{
        	echo '
        	<td colspan="2">
				<div id="joy_warning" align="center">
					No Review History
				</div>
			</td>';
        }

		echo '</table>';
}
else if(isset($_POST['delete'])){
	$P_ID = $_POST['P_ID'];
	$D_ID = $_POST['D_ID'];

	$sql_delete_data = "DELETE FROM doctor_reviews
						WHERE P_ID = $P_ID AND D_ID = $D_ID";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Review Deleted Successfully..');window.location.href = '/chms/admin/patient/review.php?P_ID=".$P_ID."';</script>";
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
	<title>Provide Review</title>
</head>
<body>
	<script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to delete this review?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>


