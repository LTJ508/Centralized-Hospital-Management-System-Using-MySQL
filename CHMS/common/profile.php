<?php
session_start();
require_once("functions.php");
$Role = $_SESSION['Role'];
if($Role == 'Patient'){
	redirect_to("/chms/patient");
}
else if($Role == 'Doctor'){
	redirect_to("/chms/doctor");
}
else if($Role == 'Hospital'){
	redirect_to("/chms/hospital");
}
else if($Role == 'Diagnostic Center'){
	redirect_to("/chms/diagnostic-center");
}
else if($Role == 'Admin'){
	redirect_to("/chms/admin");
}
else{
	redirect_to("/chms/");
}
?>