<?php
ob_start();
include("../common/header-content.php");
include("restrict-access.php");
include("status-check.php");

$User_ID = $_SESSION['User_ID'];

echo '<form action="/chms/doctor/patient-view.php" target="_blank" method="POST">
		<table align="center">
			<caption>
				<div id="joy_congratulation">
					Search Patient
				</div>
			</caption>
			<tr>
				<td>
					<input type="number" name="ID" placeholder="Patient ID" required>
				</td>
				<td>
					<input type="submit" name="search" value="Search">
				</td>
			</tr>
		</table>
	</form>';

ob_end_flush();
?>

<<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Search Patient</title>
</head>
<body>

</body>
</html>

