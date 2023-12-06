<?php
ob_start();
include("../../common/header-content.php");
include("../../common/functions.php");
include("restrict-access.php");

if(isset($_GET['P_ID'])){
	$P_ID = $_GET['P_ID'];

	$sql_info = "SELECT p.Fname, p.Lname, u.Photo
                FROM patient p, user_details u
                WHERE p.ID = u.ID AND p.ID = $P_ID";

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
				Upcomming Appoinment List
			</div>
		</caption>
		<tr>
			<th>
				<div id="joy_warning" align="center">
					Date
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Time Slot
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Hospital
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Room Number
				</div>
			</th>
			<th>
				<div id="joy_warning" align="center">
					Doctor Name
				</div>
			</th>
		</tr>';

		$today = date("Y-m-d");

		$sql_upcomming_appoinment = "SELECT a.Date, c.Start_Time, c.End_Time, h.Name, c.Room, d.Title, d.Fname, d.Lname
										FROM doctor d
										JOIN appoinment a ON(d.ID = a.D_ID)
										JOIN hospital h ON(h.ID = a.H_ID)
										JOIN consultation c ON(a.H_ID = c.H_ID AND a.D_ID = c.D_ID AND c.Day = DAYNAME(a.Date))
										WHERE a.P_ID = $P_ID AND a.Date > '$today'
										ORDER BY a.Date";
		$result_all_appoinment = mysqli_query($conn, $sql_upcomming_appoinment);
		if (mysqli_num_rows($result_all_appoinment) > 0){
			while ($row = mysqli_fetch_assoc($result_all_appoinment)){
				echo '<tr>
					<td>
						<div id="joy_congratulation" align="center">
							'.$row['Date'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation" align="center">
							'.$row['Start_Time'].' - '.$row['End_Time'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation" align="center">
							'.$row['Name'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation" align="center">
							'.$row['Room'].'
						</div>
					</td>
					<td>
						<div id="joy_congratulation" align="center">
							'.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'
						</div>
					</td>
				</tr>';
			}
		}
		else{
			echo '
			<tr>
				<td colspan="5" align="center">
					<div id="joy_warning" align="center">
						No Appoinment Available
					</div>
				</td>
			</tr>';
			
		}


		
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
	<title>Appoinment List</title>
</head>
<body>

</body>
</html>