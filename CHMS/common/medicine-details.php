<?php 
ob_start();
include("header-content.php");
include("functions.php");


if(isset($_POST['submit'])){

	if(isset($_POST['ID'])){
		$ID = $_POST['ID'];
		if($ID != 0){
			$sql_medicine_details = "SELECT Name, Strength, Group_Name, Brand_Name, Price
										FROM medicine
										WHERE ID = $ID";
			$result_medicine_details = mysqli_query($conn, $sql_medicine_details);
			$found_medicine = mysqli_fetch_array($result_medicine_details);

	        
	        $Name = $found_medicine['Name'];
	        $Strength = $found_medicine['Strength'];
	        $Group_Name = $found_medicine['Group_Name'];
	        $Brand_Name = $found_medicine['Brand_Name'];
	        $Price = $found_medicine['Price'];

	        echo '<table border="1" align="center">
	        		<caption>
	        			<div id="joy_congratulation">
	        				Medicine Details
	        			</div>
	        		</caption>
					<tr>
						<th>Name: </th>
						<td>'.$Name.'</td>
					</tr>
					<tr>
						<th>Strength: </th>
						<td>'.$Strength.'';
	$sql_variant = "SELECT ID, Strength
					FROM medicine
					WHERE Group_Name = '$Group_Name' AND Brand_Name = '$Brand_Name' AND Strength <> '$Strength'
					ORDER BY Strength DESC";
	$result_variant_list = mysqli_query($conn, $sql_variant);
	if (mysqli_num_rows($result_variant_list) > 0){
		echo '<br>Also Available: ';
		while ($row = mysqli_fetch_assoc($result_variant_list)){
			echo'<form action="" method="POST" style="margin: 0px; padding: 0px;">
					<input type="hidden" id="ID" name="ID" value="'.$row['ID'].'">
					<input type="submit" name="submit" value="'.$row['Strength'].'">
				</form>';
		}
	}
					echo '</td>
					</tr>
					<tr>
						<th>Group Name: </th>
						<td>'.$Group_Name.'</td>
					</tr>
					<tr>
						<th>Brand Name: </th>
						<td>'.$Brand_Name.'<br>
							<form action="" method="POST" style="margin: 0px; padding: 0px;">
								<input type="hidden" id="Group" name="Group" value="'.$Group_Name.'">
								<input type="submit" name="submit" value="Alternate Brands">
							</form>
				</td>
					</tr>
					<tr>
						<th>Price: </th>
						<td>'.$Price.' TK</td>
					</tr>
				</table>';

		}
		else{
			redirect_to("/chms/common/medicine-price.php");
		}
	}
	else if(isset($_POST['Group'])){
		$Group = $_POST['Group'];
		if($Group != "Not Selected"){
			$sql_medicine_list = "SELECT ID, Name, Brand_Name, Price
									FROM medicine
									WHERE Group_Name = '$Group'
									GROUP BY Name, Brand_Name
									ORDER BY Name";
			$result_medicine_list = mysqli_query($conn, $sql_medicine_list);
	        if (mysqli_num_rows($result_medicine_list) > 0){
	        	$count = 0;
				echo '<table align="center">
				<caption>
					<div id="joy_congratulation">
						All Brands of '.$Group.'
					</div>
				</caption>';
			    while ($row = mysqli_fetch_assoc($result_medicine_list)){
			    	$ID = $row['ID'];
			    	$Name = $row['Name'];
			    	$Brand_Name = $row['Brand_Name'];
			    	$Price = $row['Price'];
			    	if($count % 3 == 0){
			    		echo "<tr>";
			    	}

			    	echo '<td>
			    			<div id="joy_congratulation">
			    				'.$Name.'<br>'.$Brand_Name.'<br>'.$Price.'<br>
			    				<form action="" method="POST" style="margin: 0px; padding: 0px;">
									<input type="hidden" id="ID" name="ID" value="'.$ID.'">
									<input type="submit" name="submit" value="See Details">
								</form>
			    			</div>';

			    		 echo "</td>";
				        $count++;

				        if($count % 3 == 0){
				    		echo "</tr>";
				    	}

			    }

			    if($count % 3 != 0){
		    		echo "</tr>";
		    	}
		    echo '</table>';
	        }
		}
		else{
			redirect_to("/chms/common/medicine-price.php");
		}
	}
}
else{
	redirect_to("/chms/common/medicine-price.php");
}



ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Medicine Details</title>
</head>
<body>

</body>
</html>











