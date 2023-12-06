<?php
include("../common/connection.php");
include("../common/functions.php");


	$Field = $_GET['Field'];

	$sql_retrive_hospitals = "SELECT h.ID, h.Name
								FROM hospital h, user_details u
								WHERE h.ID = u.ID AND u.Status = TRUE AND u.ID IN
								(SELECT DISTINCT c.H_ID
	                            FROM consultation c
	                            WHERE c.D_ID IN
	                            (SELECT s.D_ID
                                FROM speciality s
                                WHERE s.Field = '$Field'))
                                ORDER BY h.Name";
	$sql_result = mysqli_query($conn, $sql_retrive_hospitals);


	$hospitals = array();

	if (mysqli_num_rows($sql_result) > 0){
	 	while ($row = mysqli_fetch_assoc($sql_result)){

	 		$ID = $row['ID'];
	 		$Name = $row['Name'];

	 		$hospital = array("ID" => $ID,
						      "Name" => $Name);
			$hospitals[] = $hospital;
	 	}
	}

	

	header('Content-Type: application/json');
    echo json_encode($hospitals);

// Close the database connection
$conn->close();
?>