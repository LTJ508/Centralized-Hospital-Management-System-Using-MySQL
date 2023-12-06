<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];

if(isset($_POST['approve'])){
	$P_ID = $_POST['P_ID'];
	$D_ID = $_POST['D_ID'];

	$sql_update_data = "UPDATE doctor_reviews
						SET Status = 1
						WHERE P_ID = $P_ID AND D_ID = $D_ID";

	if (mysqli_query($conn, $sql_update_data)){
				echo "<script type=\"text/javascript\">window.alert('Review Approved Successfully..');window.location.href = '/chms/admin/pending-reviews.php';</script>";
			} 
			else{
				echo "Error: " . $sql . "" . mysqli_error($conn);
	 		}
	 		mysqli_close($conn);
}



echo '<table align="center" border="1">
	<caption>
		<div id="joy_congratulation">
			Pending Review
		</div>
	</caption>';

		$sql_reviews = "SELECT dr.P_ID, dr.D_ID, u.Photo, d.Title, d.Fname, d.Lname, dr.Ratings, dr.Date, dr.Comment, p.Fname as PFname, p.Lname as PLname
						FROM doctor_reviews dr, doctor d, user_details u, patient p
						WHERE dr.D_ID = d.ID AND dr.P_ID = p.ID AND d.ID = u.ID AND dr.Status = FALSE
						ORDER BY dr.Date";

		$result_reviews = mysqli_query($conn, $sql_reviews);
		if (mysqli_num_rows($result_reviews) > 0){
			while ($row = mysqli_fetch_assoc($result_reviews)){

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
				echo 'By - '.$row['PFname'].' '.$row['PLname'].'';
				echo '<br>';
				echo '<form action="" onsubmit="return showConfirmation()" method="POST" style="margin: 0px; padding: 0px;">
						<input type="hidden" id="P_ID" name="P_ID" value="'.$row['P_ID'].'">
						<input type="hidden" id="D_ID" name="D_ID" value="'.$row['D_ID'].'">
						<input type="submit" name="approve" value="Make Approve">
					</form>';
				echo '</div></td></tr>';

			}
		}
		else{
			echo '
			<tr><td>
				<div id="joy_warning">
					No Pending Review
				</div>
			</td></tr>';
		}

		echo '</table>';







ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pending Review</title>
</head>
<body>
      <script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to approve this review?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>