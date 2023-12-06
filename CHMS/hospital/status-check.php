<?php

$User_ID = $_SESSION['User_ID'];

$query_status = "SELECT * FROM user_details WHERE ID = $User_ID";
$result_status = mysqli_query($conn, $query_status);
confirm_query($result_status);
$found_user = mysqli_fetch_array($result_status);
$Status = $found_user['Status'];

if($Status == FALSE){
	echo "<script type=\"text/javascript\">window.alert('Your Account is deactivated!!');window.location.href = '/chms/hospital/';</script>";
}
?>