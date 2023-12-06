<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];


	
	$sql_ratings = "SELECT ROUND((SUM(Ratings) / COUNT(*)), 2) as Ratings
					FROM doctor_reviews
					WHERE D_ID = ". $User_ID. " AND Status = TRUE";
	$result_ratings = mysqli_query($conn, $sql_ratings);
	$found_rating = mysqli_fetch_array($result_ratings);

		$Rating = $found_rating['Ratings'];


		if($Rating == ""){
			$Rating = 0;
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

        echo '<table align="center" border="1">
        	';

        if($Photo == ""){
			echo '<div align="center"><img src="/chms/doctor/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/doctor/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px"></div>';
		}

		echo '<div id="joy_congratulation" align="center">'.
			$Title .' Dr. '. $Fname . ' '. $Lname .' - '. $Rating .'*
		</div>
		';

		$sql_reviews = "SELECT u.Photo, p.Fname, p.Lname, dr.Ratings, dr.Date, dr.Comment
						FROM doctor_reviews dr, patient p, user_details u
						WHERE dr.P_ID = p.ID AND p.ID = u.ID AND dr.Status = TRUE AND dr.D_ID = $User_ID
						ORDER BY dr.Date DESC";

		$result_reviews = mysqli_query($conn, $sql_reviews);
		if (mysqli_num_rows($result_reviews) > 0){
			while ($row = mysqli_fetch_assoc($result_reviews)){

				echo '<tr><td>';
				if($row['Photo'] == ""){
					echo '<div align="center"><img src="/chms/patient/img/null.jpg" alt="'.$row['Fname'].' '.$row['Lname'].'" width="200px" height="200px"></div></td>';
				}
				else{
					echo '<div align="center"><img src="/chms/patient/'.$row['Photo'].'" alt="'.$row['Fname'].' '.$row['Lname'].'" width="120px" height="120px"></div></td>';
				}

				echo '<td><div id="joy_congratulation">
						'. $row['Fname'] .' '. $row['Lname'] .' - '. $row['Ratings'].'* - '. $row['Date'] .'';
				echo '<br>';
				echo 'Comment: '.$row['Comment'].'';
				echo '</div></td></tr>';

			}
		}
		else{
		echo '
		<tr>
			<td colspan="1" align="center">
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
	<title>All Reviews</title>
</head>
<body>

</body>
</html>
