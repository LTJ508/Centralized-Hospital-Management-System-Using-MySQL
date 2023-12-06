<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];



if(isset($_POST['view'])){
	$ID = $_POST['ID'];
	$Role = $_POST['Role'];
	
	if($Role == 'Patient'){
		redirect_to("/chms/admin/patient-view.php?P_ID=".$ID."");
	}
	else if($Role == 'Doctor'){
		redirect_to("/chms/admin/doctor-view.php?D_ID=".$ID."");
	}
	else if($Role == 'Hospital'){
		redirect_to("/chms/admin/hospital-view.php?H_ID=".$ID."");
	}
	else if($Role == 'Diagnostic Center'){
		redirect_to("/chms/admin/diagnostic-center-view.php?DC_ID=".$ID."");
	}
	else{
		redirect_to("/chms/");
	}

}
else if(isset($_POST['delete'])){
	$ID = $_POST['ID'];

	$sql_delete = "DELETE FROM user_details
					WHERE ID = $ID";
	if (mysqli_query($conn, $sql_delete)){
		echo "<script type=\"text/javascript\">window.alert('Account Deleted Successfully..');window.location.href = '/chms/admin/';</script>";
	} 
	else{
		echo "Error: " . $sql . "" . mysqli_error($conn);
	}
	mysqli_close($conn);
}
else{
	redirect_to("/chms/admin/");
}



ob_end_flush();
?>



