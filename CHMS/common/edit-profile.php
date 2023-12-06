<?php
session_start();
require_once("functions.php");
$Role = $_SESSION['Role'];
if($Role == 'Patient'){
	redirect_to("/chms/patient/edit-profile.php");
}
else if($Role == 'Doctor'){
	redirect_to("/chms/doctor/edit-profile.php");
}
else if($Role == 'Hospital'){
	redirect_to("/chms/hospital/edit-profile.php");
}
else if($Role == 'Diagnostic Center'){
	redirect_to("/chms/diagnostic-center/edit-profile.php");
}
else{
	redirect_to("/chms/common/profile.php");
}
?>