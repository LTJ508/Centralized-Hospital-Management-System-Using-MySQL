<?php
include("../common/connection.php");
include("../common/functions.php");


if(isset($_GET['D_ID']) && isset($_GET['H_ID'])){
	$D_ID = $_GET['D_ID'];
	$H_ID = $_GET['H_ID'];

	$sql_retrive_day = "SELECT Day
						FROM consultation
						WHERE D_ID = $D_ID AND H_ID = $H_ID";
	$sql_result = mysqli_query($conn, $sql_retrive_day);


	$dates = array();

	if (mysqli_num_rows($sql_result) > 0){
	 	while ($row = mysqli_fetch_assoc($sql_result)){
	 		$Day = $row['Day'];
	 		$count = 4;
		    $date = getNextDay($Day);

		    for ($i = 0; $i < $count; $i++) {
		        $dates[] = $date->format('Y-m-d');
		        $date->modify("next $Day");
		    }
	 	}
	}

	sort($dates);

	header('Content-Type: application/json');
    echo json_encode($dates);
}



// Close the database connection
$conn->close();
?>
