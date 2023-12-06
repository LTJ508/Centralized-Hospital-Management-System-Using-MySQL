<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];

if(isset($_POST['submit'])){
	$D_ID = $_POST['D_ID'];
	$Ratings = $_POST['Ratings'];
	$Comment = $_POST['Comment'];

	$Date = date('Y-m-d');

	$sql_add_review = "INSERT INTO doctor_reviews(P_ID, D_ID, Date, Comment, Ratings)
						VALUES($User_ID, $D_ID, '$Date', '$Comment', $Ratings)";

	if (mysqli_query($conn, $sql_add_review)){
				echo "<script type=\"text/javascript\">window.alert('Review Added Successfully..');window.location.href = '/chms/patient/review.php';</script>";
			} 
			else{
				echo "Error: " . $sql . "" . mysqli_error($conn);
	 		}
	 		mysqli_close($conn);
}
else if(isset($_POST['delete'])){
	$D_ID = $_POST['D_ID'];

	$sql_delete_data = "DELETE FROM doctor_reviews
						WHERE P_ID = $User_ID AND D_ID = $D_ID";

	if (mysqli_query($conn, $sql_delete_data)){
				echo "<script type=\"text/javascript\">window.alert('Review Deleted Successfully..');window.location.href = '/chms/patient/review.php';</script>";
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

       $sql_pending_review = "SELECT D_ID
								FROM prescriptions
								WHERE P_ID = $User_ID AND D_ID NOT IN(SELECT D_ID
								                                  FROM doctor_reviews
								                                  WHERE P_ID = $User_ID)
								GROUP BY D_ID";

		$result_pending_review = mysqli_query($conn, $sql_pending_review);
		if (mysqli_num_rows($result_pending_review) > 0){
			echo '<form action="" method="POST">
					<tr>
						<td colspan="2">
							<div id="joy_congratulation" align="center">
								Add New Review:
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<label for="D_ID">Select Doctor: </label>
						</td>
						<td>
							<select id="D_ID" name="D_ID" required>';

			while ($row = mysqli_fetch_assoc($result_pending_review)){
				$sql_doctor_details = "SELECT Title, Fname, Lname
										FROM doctor
										WHERE ID = ".$row['D_ID']."";
				$result_doctor_details = mysqli_query($conn, $sql_doctor_details);
				$found_user = mysqli_fetch_array($result_doctor_details);

					$Title = $found_user['Title'];
					$Fname = $found_user['Fname'];
			        $Lname = $found_user['Lname'];

			        echo'<option value="'.$row['D_ID'].'">'.$Title.' Dr. '.$Fname.' '.$Lname.'</option>';
			}
			echo'		
						</select></td>
					</tr>
					<tr>
						<td>
							<label for="Ratings">Select Ratings</label>
						</td>
						<td>
							<select id="Ratings" name="Ratings" required>
								<option value="5">5*</option>
								<option value="4">4*</option>
								<option value="3">3*</option>
								<option value="2">2*</option>
								<option value="1">1*</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<textarea id="Comment" name="Comment" rows="2" cols="30">Write Comments Here</textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="submit" name="submit" value="Submit">
						</td>
					</tr>
				</form></table>';
		}
		else{
			echo '<div id="joy_warning" align="center">
						No Pending Review
					</div>';
		}


		echo '<table align="center" border="1">';

		$sql_reviews = "SELECT d.ID, u.Photo, d.Title, d.Fname, d.Lname, dr.Ratings, dr.Date, dr.Comment, dr.Status
						FROM doctor_reviews dr, doctor d, user_details u
						WHERE dr.D_ID = d.ID AND d.ID = u.ID AND dr.P_ID = $User_ID
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
				echo '<form action="" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
						<input type="hidden" id="D_ID" name="D_ID" value="'.$row['ID'].'">
						<input type="submit" name="delete" value="Delete">
					</form>';
				echo '</div></td></tr>';

			}
		}
		else{
		echo '
		<tr>
			<td colspan="2" align="center">
				<div id="joy_warning">
					No Reviews
				</div>
			</td>
		</tr>';
	}

		echo '</table>';


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


