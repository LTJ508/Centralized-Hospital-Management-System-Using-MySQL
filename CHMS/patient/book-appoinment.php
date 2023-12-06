<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];

if(isset($_POST['submit'])){
    $D_ID = $_POST['D_ID'];
    $H_ID = $_POST['H_ID'];
    $Date = $_POST['Date'];

    $sql_check_date = "SELECT *
                        FROM appoinment
                        WHERE D_ID = $D_ID AND H_ID = $H_ID AND Date = '$Date'";
    if(mysqli_num_rows(mysqli_query($conn, $sql_check_date)) > 0){
        echo "<script type=\"text/javascript\">window.alert('You already have an appoinment on this date!! Please choose another date..');window.location.href = '/chms/patient/book-appoinment.php';</script>";
    }
    else{
        $sql_check_capacity = "SELECT *
                                FROM consultation
                                WHERE D_ID = $D_ID AND H_ID = $H_ID AND Day = DAYNAME('$Date') AND MAX_Capacity <= 
                                (SELECT COUNT(*)
                                FROM appoinment
                                WHERE D_ID = $D_ID AND H_ID = $H_ID AND Date = '$Date')";
        if(mysqli_num_rows(mysqli_query($conn, $sql_check_capacity)) > 0){
            echo "<script type=\"text/javascript\">window.alert('MAX appoinment reached on this date!! Please choose another date..');window.location.href = '/chms/patient/book-appoinment.php';</script>";
        }
        else{
            $sql_insert = "INSERT INTO appoinment(P_ID, D_ID, H_ID, Date)
                            VALUES($User_ID, $D_ID, $H_ID, '$Date')";
            if (mysqli_query($conn, $sql_insert)){
                echo "<script type=\"text/javascript\">window.alert('Appoinment Booked Successfully..');window.location.href = '/chms/patient/appoinment.php';</script>";
            } 
            else{
                echo "Error: " . $sql . "" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

}
else{
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
        <form action="" method="POST" style="margin: 0px; padding: 0px;">
        <tr>
		    <td>
		        <div id="joy_congratulation">
		            <lebel for="Field">Select Department: </lebel>
		        </div>
		    </td>
		    <td>
		        <div id="joy_congratulation">
		            <select id="Field" name="Field">';

		          	$sql_department_list  = "SELECT DISTINCT Field
		          							FROM speciality
		          							ORDER BY Field";
		          	$result_department_list = mysqli_query($conn, $sql_department_list);
		          	if (mysqli_num_rows($result_department_list) > 0){
		                echo '<option value="0">Select Department</option>';
		                while ($row = mysqli_fetch_assoc($result_department_list)){
		                    echo '<option value="'.$row['Field'].'">'.$row['Field'].'</option>';
		                }
		            }
		            else{
		                echo '<option value="0">No Department Available</option>';
		            }

		          		echo '
		    		</select>
		    	</div>
		    </td>
		</tr>
		<tr>
		    <td>
		        <div id="joy_congratulation">
		            <lebel for="H_ID">Select Hospital: </lebel>
		        </div>
		    </td>
		    <td>
		        <div id="joy_congratulation">
		            <select id="H_ID" name="H_ID" disabled>
		          		<option value="0">Select Hospital</option>
		    		</select>
		    	</div>
		    </td>
		</tr>
		<tr>
		    <td>
		        <div id="joy_congratulation">
		            <lebel for="D_ID">Select Doctor: </lebel>
		        </div>
		    </td>
		    <td>
		        <div id="joy_congratulation">
		            <select id="D_ID" name="D_ID" disabled>
		          		<option value="0">Select Doctor</option>
		    		</select>
		    	</div>
		    </td>
		</tr>
		<tr>
		    <td>
		        <div id="joy_congratulation">
		            <lebel for="Date">Select a Date: </lebel>
		        </div>
		    </td>
		    <td>
		        <div id="joy_congratulation">
		            <select id="Date" name="Date" onchange="getSlot()" disabled>
		                <option value="0">Select a Date</option>
		            </select>
		        </div>
		    </td>
		</tr>
		<tr>
		    <td>
		        <div id="joy_congratulation">
		            <lebel for="slotDisplay">Time Slot: </lebel>
		        </div>
		    </td>
		    <td>
		        <div id="joy_congratulation">
		            <input type="test" name="slotDisplay" id="slotDisplay" placeholder="Select Date" readonly>
		        </div>
		    </td>
		</tr>
		<tr>
		    <td colspan="2" align="center">
		        <div id="joy_congratulation">
		            <input type="submit" name="submit" value="Submit">
		        </div>
		    </td>
		</tr>
        </form></table>';
}


ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Make Appoinment</title>
</head>
<body>
<script>
        document.getElementById("Field").addEventListener("change", function () {
	    var Field = this.value;
	    var H_ID = document.getElementById("H_ID");
	    var D_ID = document.getElementById("D_ID");
	    var dateSelect = document.getElementById("Date");
	    var slotDisplay = document.getElementById("slotDisplay");

	    if (Field !== "0") {
	        H_ID.removeAttribute("disabled");
	        H_ID.innerHTML = '<option value="0">Select a Hospital</option>'; // Clear existing options

	        var url = "get_hospital.php?Field=" + Field;

	        // Make an AJAX request to retrieve available hospitals
	        var xhr = new XMLHttpRequest();
	        xhr.open("GET", url, true);
	        xhr.onreadystatechange = function () {
	            if (xhr.readyState === 4 && xhr.status === 200) {
	                var availableHospitals = JSON.parse(xhr.responseText);

	                // Populate the hospital dropdown options based on the response
	                for (var i = 0; i < availableHospitals.length; i++) {
	                    var hospital = availableHospitals[i];
	                    var option = document.createElement("option");
	                    option.value = hospital.ID;
	                    option.text = hospital.Name;
	                    H_ID.appendChild(option);
	                }
	                D_ID.selectedIndex = 0;
	                D_ID.setAttribute("disabled", "disabled");
	                dateSelect.selectedIndex = 0;
	                dateSelect.setAttribute("disabled", "disabled");
	                slotDisplay.value = "Select Date";
	            }
	        };
	        xhr.send();
	    } else {
	        // If the user reverts the selection, reset and disable both date and time slot dropdowns
	        H_ID.selectedIndex = 0;
	        H_ID.setAttribute("disabled", "disabled");
	        D_ID.selectedIndex = 0;
	        D_ID.setAttribute("disabled", "disabled");
	        dateSelect.selectedIndex = 0;
	        dateSelect.setAttribute("disabled", "disabled");
	        slotDisplay.value = "Select Date";
	    }
	});





    document.getElementById("H_ID").addEventListener("change", function () {
    	var Field = document.getElementById("Field").value;
	    var H_ID = this.value;
	    var D_ID = document.getElementById("D_ID");
	    var dateSelect = document.getElementById("Date");
	    var slotDisplay = document.getElementById("slotDisplay");

	    if (H_ID !== "0") {
	        D_ID.removeAttribute("disabled");
	        D_ID.innerHTML = '<option value="0">Select a Doctor</option>'; // Clear existing options

	        var url = "get_doctor.php?Field=" + Field + "&H_ID=" + H_ID;

	        // Make an AJAX request to retrieve available hospitals
	        var xhr = new XMLHttpRequest();
	        xhr.open("GET", url, true);
	        xhr.onreadystatechange = function () {
	            if (xhr.readyState === 4 && xhr.status === 200) {
	                var availableHospitals = JSON.parse(xhr.responseText);

	                // Populate the hospital dropdown options based on the response
	                for (var i = 0; i < availableHospitals.length; i++) {
	                    var hospital = availableHospitals[i];
	                    var option = document.createElement("option");
	                    option.value = hospital.ID;
	                    option.text = hospital.Name;
	                    D_ID.appendChild(option);
	                }

	                dateSelect.selectedIndex = 0;
	                dateSelect.setAttribute("disabled", "disabled");
	                slotDisplay.value = "Select Date";
	            }
	        };
	        xhr.send();
	    } else {
	        // If the user reverts the selection, reset and disable both date and time slot dropdowns
	        D_ID.selectedIndex = 0;
	        D_ID.setAttribute("disabled", "disabled");
	        dateSelect.selectedIndex = 0;
	        dateSelect.setAttribute("disabled", "disabled");
	        slotDisplay.value = "Select Date";
	    }
	});





	document.getElementById("D_ID").addEventListener("change", function (){
        var H_ID = document.getElementById("H_ID").value;
        var D_ID = this.value;
        var dateSelect = document.getElementById("Date");
        var slotDisplay = document.getElementById("slotDisplay");

        if (D_ID !== "0") {
                dateSelect.removeAttribute("disabled");
                dateSelect.innerHTML = '<option value="0">Select a date</option>'; // Clear existing options

                var url = "get_dates.php?H_ID=" + H_ID + "&D_ID=" + D_ID;

                // Make an AJAX request to retrieve available dates
                var xhr = new XMLHttpRequest();
                xhr.open("GET", url, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var availableDates = JSON.parse(xhr.responseText);

                        // Populate the date dropdown options based on Hospital B
                        for (var i = 0; i < availableDates.length; i++) {
                            var option = document.createElement("option");
                            option.value = availableDates[i];
                            option.text = availableDates[i];
                            dateSelect.appendChild(option);
                        }

                        slotDisplay.value = "Select Date";
                    }
                };
                xhr.send();
            

        } else {
            // If the user reverts the selection, reset and disable both date and time slot dropdowns
            dateSelect.selectedIndex = 0;
            dateSelect.setAttribute("disabled", "disabled");
            slotDisplay.value = "Select Date";
        }
    });


        function getSlot() {
            const D_ID = document.getElementById("D_ID").value;
            const H_ID = document.getElementById("H_ID").value;
            const SelectedDate = document.getElementById("Date").value;
            const slotDisplay = document.getElementById("slotDisplay");

            if (SelectedDate == "0") {
                slotDisplay.value = "Select Date";
            } else {
                // Create a new XMLHttpRequest object
                const xhr = new XMLHttpRequest();

                // Define the callback function for when the request is completed
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        const slot = xhr.responseText;
                        slotDisplay.value = slot;
                    }
                };

                // Open a POST request to the PHP script (change the URL as needed)
                xhr.open("POST", "get_slot.php", true);

                // Set the request header (if necessary)
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                // Send the request with the selected test name
                xhr.send("D_ID=" + D_ID + "&H_ID=" + H_ID + "&Date=" + SelectedDate);
            }
        }
    </script>
</body>
</html>




