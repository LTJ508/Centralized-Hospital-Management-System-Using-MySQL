<?php
ob_start();
include("../../common/header-content.php");
include("../../common/functions.php");
include("restrict-access.php");

if(isset($_GET['DC_ID'])){
	$DC_ID = $_GET['DC_ID'];

	$sql_info = "SELECT dc.Name, u.Photo
		FROM diagnostic_center dc, user_details u
		WHERE dc.ID = u.ID AND dc.ID = $DC_ID";

		$result_info = mysqli_query($conn, $sql_info);
		$found_user = mysqli_fetch_array($result_info);

        $Name = $found_user['Name'];
        $Photo = $found_user['Photo'];

        echo '<table align="center" border="1">';

        if($Photo == ""){
			echo '<div align="center"><img src="/chms/diagnostic-center/img/null.jpg" alt="'.$Name.'" width="200px" height="200px"></div>';
		}
		else{
			echo '<div align="center"><img src="/chms/diagnostic-center/'.$Photo.'" alt="'.$Name.'" width="120px" height="120px"></div>';
		}

		echo '<div id="joy_congratulation" align="center">
		'. $Name . '</div>';

echo '
		<caption>
			<div id="joy_congratulation">
				All Test
			</div>
		</caption>
		<tr>
			<th>
				<div id="joy_warning" align="center">
					Test Date
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Test Name
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Patient Name
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Result
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Comment
				</div>
			</th>
		</tr>';




$sql_all_test = "SELECT tl.ID, t.Name, tl.Test_Date, p.Fname, p.Lname, tl.Result_Date, tl.Result, tl.Comment
				FROM test t, test_log tl, patient p
				WHERE t.ID = tl.T_ID AND tl.P_ID = p.ID AND tl.DC_ID = $DC_ID
				ORDER BY tl.Test_Date DESC";

	$result_all_test = mysqli_query($conn, $sql_all_test);
	if (mysqli_num_rows($result_all_test) > 0){
		while ($row = mysqli_fetch_assoc($result_all_test)){
			echo '<tr>
					<td>
						<div id="joy_congratulation">
							'.$row['Test_Date'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							'.$row['Name'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">
							'.$row['Fname'].' '.$row['Lname'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">';
						if($row['Result_Date'] == ""){
							echo 'Not Done Yet';
						}
						else{
							echo '<a href="/chms/diagnostic-center/'.$row['Result'].'" download="report-'.$row['ID'].'.pdf">
								        <button>'.$row['Result_Date'].'</button>
								    </a>';
						}
						echo '
						</div>
					</td>
					<td>
						<div id="joy_congratulation">';
						if($row['Comment'] == ""){
							echo 'Not Published';
						}
						else{
							echo ''.$row['Comment'].'';
						}
						echo '
						</div>
					</td>
				</tr>';
		}
	}

	echo '</table>';
}
else{
	redirect_to("/chms/admin/");
}





ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>All Test List</title>
</head>
<body>

</body>
</html>