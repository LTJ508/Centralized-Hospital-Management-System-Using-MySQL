<?php
ob_start();
include("header-content.php");
include("functions.php");
if(isset($_POST['reviews'])){
	$ID = $_POST['Doctor_ID'];
	$Rating = $_POST['Ratings'];

	if($Rating == ""){
			$Rating = 0;
		}

	$sql_info = "SELECT d.Title, d.Fname, d.Lname, u.Photo
		FROM doctor d, user_details u
		WHERE d.ID = u.ID AND d.ID = $ID";

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
						WHERE dr.P_ID = p.ID AND p.ID = u.ID AND dr.Status = TRUE AND dr.D_ID = $ID
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

		echo '</table>';
}
else{
	redirect_to("/chms/");
}
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Doctor - Reviews</title>
</head>
<body>

</body>
</html>
