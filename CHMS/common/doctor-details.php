<?php
ob_start();
include("header-content.php");
include("functions.php");
if(isset($_POST['details'])){
	$ID = $_POST['Doctor_ID'];

$sql_info = "SELECT d.Title, d.Fname, d.Lname, d.Experience_From, d.Language, d.Current_Position, d.P_Location, u.Photo, d.DOB
FROM doctor d, user_details u
WHERE d.ID = u.ID AND d.ID = ".$ID."";

$result_info = mysqli_query($conn, $sql_info);
$found_user = mysqli_fetch_array($result_info);

		$Title = $found_user['Title'];
		$Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];
        $Experience_From = $found_user['Experience_From'];
		$Language = $found_user['Language'];
        $Current_Position = $found_user['Current_Position'];
        $P_Location = $found_user['P_Location'];
        $Photo = $found_user['Photo'];
        $DOB = $found_user['DOB'];


echo '
<table border="1px" align="center">
	<caption>
		<div id="joy_congratulation">'.
			$Title .' Dr. '. $Fname . ' '. $Lname .'
		</div>
	</caption>
	<tr>
		<td rowspan="8">';
		if($Photo == ""){
			echo '<img src="/chms/doctor/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px">';
		}
		else{
			echo '<img src="/chms/doctor/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px">';
		}
		
		echo '
		</td>
		<td>
			Speciality: <br>';

		$sql_retrive_speciality = "SELECT Field
									FROM speciality
									WHERE D_ID = $ID
									ORDER BY Field";

		$result_speciality = mysqli_query($conn, $sql_retrive_speciality);

		if (mysqli_num_rows($result_speciality) > 0){
			while ($row = mysqli_fetch_assoc($result_speciality)){
				echo $row['Field'] .', ';
			}
		}


			echo '
		</td>
	</tr>
	<tr>
		<td>
			Degrees: <br>';

	$sql_retrive_degree = "SELECT Degree, Field, Institution, Year
							FROM degrees
							WHERE D_ID = $ID
							ORDER BY Year";

		$result_degree = mysqli_query($conn, $sql_retrive_degree);
		if (mysqli_num_rows($result_degree) > 0){
			while ($row = mysqli_fetch_assoc($result_degree)){
				echo $row['Degree'] .'('. $row['Field'] .') - '. $row['Institution'].' - '. $row['Year'] .'';
				echo '<br>';
			}
		}

		echo '


		</td>
	</tr>
	<tr>
		<td>
			Language: '. $Language.'
		</td>
	</tr>
	<tr>
		<td>
			Current Position: '.$Current_Position.' at '. $P_Location.'
		</td>
	</tr>
	<tr>
		<td>
			Age: '. calculateAge($DOB).' Years
			<br>
			Experience: '. calculateAge($Experience_From) .' Years
		</td>
	</tr>
	<tr>
		<td>
			Ratings: ';

 	$sql_ratings = "SELECT ROUND((SUM(Ratings) / COUNT(*)), 2) as Ratings
					FROM doctor_reviews
					WHERE D_ID = $ID AND Status = TRUE";
	$result_ratings = mysqli_query($conn, $sql_ratings);
	$found_rating = mysqli_fetch_array($result_ratings);

		$Rating = $found_rating['Ratings'];


		if($Rating == ""){
			echo '0';
		}
		else{
			echo $Rating;
		}

			echo '
			<br>
			<form action="/chms/common/reviews.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
				<input type="hidden" id="Doctor_ID" name="Doctor_ID" value="'. $ID .'">
				<input type="hidden" id="Ratings" name="Ratings" value="'. $Rating .'">
				<input type="submit" name="reviews" value="See Reviews">
			</form>

		</td>
	</tr>
	<tr>
		<td>
			Awards & Honors: <br>';


		$sql_retrive_Awards = "SELECT Award_Name, Awarding_Organization, Year_Received, Description
								FROM awards_honors
								WHERE D_ID = $ID
								ORDER BY Year_Received";

		$result_awards = mysqli_query($conn, $sql_retrive_Awards);
		if (mysqli_num_rows($result_awards) > 0){
			while ($row = mysqli_fetch_assoc($result_awards)){
				echo $row['Award_Name'] .' by '. $row['Awarding_Organization'] .' in '. $row['Year_Received'].' - '. $row['Description'] .'';
				echo '<br>';
			}
		}

			echo '
		</td>
	</tr>
	<tr>
		<td>
			Journals & Publications: <br>';

		$sql_retrive_journal = "SELECT Title, Year, Link
								FROM journals_publications
								WHERE D_ID = $ID
								ORDER BY Year";

		$result_journal = mysqli_query($conn, $sql_retrive_journal);
		if (mysqli_num_rows($result_journal) > 0){
			while ($row = mysqli_fetch_assoc($result_journal)){
				echo $row['Title'] .' in '. $row['Year'] .'<br>Link: <a href="'. $row['Link'].'" target="_blank">Click Here</a>';
				echo '<br>';
			}
		}


			echo'
		</td>
	</tr>
	<tr>
		<td align="center">
			<form action="/chms/patient/make-appoinment.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
				<input type="hidden" id="Doctor_ID" name="Doctor_ID" value="'. $ID .'">
				<input type="submit" name="appoinment" value="Make Appoinment">
			</form>
		</td>
		<td align="center">
			<form action="/chms/common/consultant-hour.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
				<input type="hidden" id="Doctor_ID" name="Doctor_ID" value="'. $ID .'">
				<input type="submit" name="consultant" value="Consultant Hour">
			</form>
		</td>
	</tr>
</table>
';

}
else{
	redirect_to("/chms/");
}
ob_end_flush();
?>

<<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctor Details</title>
</head>
<body>

</body>
</html>



