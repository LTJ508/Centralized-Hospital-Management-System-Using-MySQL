<?php 
ob_start();
include("header-content.php");

$sql_test_name ="SELECT ID, Name
					FROM test
					WHERE Status = TRUE
					ORDER BY Name";
$result_name_list = mysqli_query($conn, $sql_test_name);

echo'

<form action="" method="POST" style="margin: 0px; padding: 0px;">
	<table align="center">
		<tr>
			<td colspan="2">
				<div id="joy_congratulation">
					Search Test Name
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<label for="test_name">Select Test</label>
			</td>
			<td>
				<select id="test_name" name="test_name" onchange="getTestPrice()">
				<option value="0">Select Test</option>';

				if (mysqli_num_rows($result_name_list) > 0){
					while ($row = mysqli_fetch_assoc($result_name_list)){
						echo '<option value="'.$row['ID'].'">'.$row['Name'].'</option>';
					}
				}

					echo'
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="test" name="price_display" id="price_display" placeholder="Select Test" readonly>
			</td>
		</tr>
	</table>
</form>';


echo '<table border="1" align="center">
	<caption>
		<div id="joy_congratulation">
			All Test Price
		</div>
	</caption>
	<tr>
		<th>
			Test Name
		</th>
		<th>
			Price
		</th>
	</tr>';


$sql_retrive_test ="SELECT Name, Price
					FROM test
					WHERE Status = TRUE
					ORDER BY Name";
$result_test_list = mysqli_query($conn, $sql_retrive_test);
if (mysqli_num_rows($result_test_list) > 0){
	while ($row = mysqli_fetch_assoc($result_test_list)){
		echo '<tr>
				<td>
					'.$row['Name'].'
				</td>
				<td>
					'.$row['Price'].' TK
				</td>
			</tr>';
	}
}
echo'
</table>';

ob_end_flush();
?>


<<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Test Price List</title>
</head>
<body>
	<script>
        // Function to fetch and display the test price
        function getTestPrice() {
            const selectedTest = document.getElementById("test_name").value;
            const priceDisplay = document.getElementById("price_display");

            if (selectedTest == "0") {
                priceDisplay.value = "Select Test";
            } else {
                // Create a new XMLHttpRequest object
                const xhr = new XMLHttpRequest();

                // Define the callback function for when the request is completed
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const price = xhr.responseText;
                        priceDisplay.value = price + " TK";
                    }
                };

                // Open a POST request to the PHP script (change the URL as needed)
                xhr.open("POST", "get_price.php", true);

                // Set the request header (if necessary)
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                // Send the request with the selected test name
                xhr.send("test_name=" + selectedTest);
            }
        }     
    </script>
</body>
</html>

