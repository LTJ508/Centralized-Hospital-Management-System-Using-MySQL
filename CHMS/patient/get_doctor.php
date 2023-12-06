<?php
include("../common/connection.php");
include("../common/functions.php");


	$Field = $_GET['Field'];
	$H_ID = $_GET['H_ID'];

	$sql_retrive_doctors = "SELECT d.ID, d.Title, d.Fname, d.Lname
							FROM doctor d, speciality s, consultation c
							WHERE d.ID = s.D_ID AND s.D_ID = c.D_ID AND c.H_ID = $H_ID AND s.Field = '$Field'
							GROUP BY d.ID
							ORDER BY d.Fname";
	$sql_result = mysqli_query($conn, $sql_retrive_doctors);


	$doctors = array();

	if (mysqli_num_rows($sql_result) > 0){
	 	while ($row = mysqli_fetch_assoc($sql_result)){

	 		$ID = $row['ID'];

	 		$Name = ''.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'';

	 		$doctor = array("ID" => $ID,
						      "Name" => $Name);
			$doctors[] = $doctor;
	 	}
	}

	

	header('Content-Type: application/json');
    echo json_encode($doctors);

// Close the database connection
$conn->close();
?>