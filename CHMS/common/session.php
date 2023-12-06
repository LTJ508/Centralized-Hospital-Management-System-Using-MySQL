<?php

	function logged_in() {
		return isset($_SESSION['User_ID']);
	}

	function confirm_logged_in() {
		if (logged_in()) {
			redirect_to("/chms/common/login.php");
		}
	}
?>
