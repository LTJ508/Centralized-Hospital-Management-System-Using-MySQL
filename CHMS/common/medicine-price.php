<?php 
ob_start();
include("header-content.php");

echo '<form action="/chms/common/medicine-details.php" method="POST" style="margin: 0px; padding: 0px;">
		<table align="center">
			<tr>
				<td colspan="2">
					<div id="joy_congratulation" align="center">
						Search Medicine
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<select id="ID" name="ID">
					<option value="0">Select Medicine</option>';

	$sql_medicine_list = "SELECT ID, Name, Strength
							FROM medicine
							ORDER BY Name";

	$result_medicine_list = mysqli_query($conn, $sql_medicine_list);
	if (mysqli_num_rows($result_medicine_list) > 0){
		while ($row = mysqli_fetch_assoc($result_medicine_list)){
			echo '<option value="'.$row['ID'].'">'.$row['Name'].' '.$row['Strength'].'</option>';
		}
	}
				      echo'      
				</td>
				<td>
					<select id="Group" name="Group">
					<option value="Not Selected">Select Group</option>';

	$sql_group_list = "SELECT DISTINCT Group_Name
							FROM medicine
							ORDER BY Group_Name";

	$result_group_list = mysqli_query($conn, $sql_group_list);
	if (mysqli_num_rows($result_group_list) > 0){
		while ($row = mysqli_fetch_assoc($result_group_list)){
			echo '<option value="'.$row['Group_Name'].'">'.$row['Group_Name'].'</option>';
		}
	}
				      echo'      
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" name="submit" value="Search">
				</td>
			</tr>
		</table>
	</form>';


ob_end_flush();
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Search Medicine</title>
</head>
<body>
	<script>
        document.getElementById("ID").addEventListener("change", function () {
            var medicine_id = this.value;
            var group_select = document.getElementById("Group");

            if (medicine_id !== "0") {
            	group_select.setAttribute("disabled", "disabled");
            } else {
               group_select.removeAttribute("disabled");
            }
        });
        document.getElementById("Group").addEventListener("change", function () {
            var group_select = this.value;
            var medicine_id = document.getElementById("ID");

            if (group_select !== "Not Selected") {
            	medicine_id.setAttribute("disabled", "disabled");
            } else {
               medicine_id.removeAttribute("disabled");
            }
        });
    </script>
</body>
</html>



	
