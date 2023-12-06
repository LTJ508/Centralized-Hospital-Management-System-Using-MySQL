<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];

$sql_info = "SELECT p.Fname, p.Lname, u.Photo
                FROM patient p, user_details u
                WHERE p.ID = u.ID AND p.ID = $User_ID";

        $result_info = mysqli_query($conn, $sql_info);
        $found_user = mysqli_fetch_array($result_info);

        
        $Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];
        $Photo = $found_user['Photo'];

        echo '<table border="1" align="center">';

        if($Photo == ""){
            echo '<div align="center"><img src="/chms/patient/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px"></div>';
        }
        else{
            echo '<div align="center"><img src="/chms/patient/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px"></div>';
        }

        echo '<div id="joy_congratulation" align="center">'.
            $Fname . ' '. $Lname .'</div>';

echo '
		<caption>
			<div id="joy_congratulation">
				All Test
			</div>
		</caption>
		<tr>
			<th>
				<div id="joy_warning" align="center">
					Test Name
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Test Date
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					From
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




$sql_all_test = "SELECT l.ID, t.Name, l.Test_Date, d.Name as 'DCName', l.Result_Date, l.Result, l.Comment
					FROM test t
					JOIN test_log l
					ON(t.ID = l.T_ID)
					LEFT OUTER JOIN diagnostic_center d
					ON(l.DC_ID = d.ID)
					WHERE l.P_ID = $User_ID
					ORDER BY l.Test_Date DESC";

	$result_all_test = mysqli_query($conn, $sql_all_test);
	if (mysqli_num_rows($result_all_test) > 0){
		while ($row = mysqli_fetch_assoc($result_all_test)){
			echo '<tr>
					<td>
						<div id="joy_congratulation">
							'.$row['Name'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation">';
						if($row['Test_Date'] == ""){
							echo "Not Claimed";
						}
						else{
							echo ''.$row['Test_Date'].'';
						}
						echo '
						</div>
					</td>
					<td>
						<div id="joy_congratulation">';
						if($row['DCName'] == ""){
							echo 'Not Claimed';
						}
						else{
							echo ''.$row['DCName'].'';
						}
						echo '
						</div>
					</td>
					<td>
						<div id="joy_congratulation">';
						if($row['Result_Date'] == ""){
							echo 'Not Published';
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
	else{
		echo '
		<tr>
			<td colspan="5" align="center">
				<div id="joy_warning">
					No Test Report
				</div>
			</td>
		</tr>';
	}

	echo '</table>';




ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>All Test - Patient</title>
</head>
<body>

</body>
</html>