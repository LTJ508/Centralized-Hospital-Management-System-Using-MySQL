<?php
include("../common/connection.php");
include("../common/functions.php");

$D_ID = $_POST["D_ID"];
$H_ID = $_POST["H_ID"];
$Date = $_POST["Date"];

// Retrieve the price of the selected test from the database
$sql = "SELECT Start_Time, End_Time
		FROM consultation
		WHERE D_ID = $D_ID AND H_ID = $H_ID AND Day = DAYNAME('$Date')";
$result = mysqli_query($conn, $sql);
$found_slot = mysqli_fetch_array($result);
$timeSlot = ''.$found_slot['Start_Time'].' - '.$found_slot['End_Time'].'';

echo $timeSlot;

// Close the database connection
$conn->close();
?>
