<?php
require_once("../common/functions.php");
$Role = "";
if(isset($_SESSION['Role'])){
	$Role = $_SESSION['Role'];	
}
if($Role != 'Doctor'){
	redirect_to("/chms/common/profile.php");
}
?>