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
        echo "<script type=\"text/javascript\">window.alert('You already have an appoinment on this date!! Please choose another date..');window.location.href = '/chms/patient/make-appoinment.php?D_ID=".$D_ID."';</script>";
    }
    else{
        $sql_check_capacity = "SELECT *
                                FROM consultation
                                WHERE D_ID = $D_ID AND H_ID = $H_ID AND Day = DAYNAME('$Date') AND MAX_Capacity <= 
                                (SELECT COUNT(*)
                                FROM appoinment
                                WHERE D_ID = $D_ID AND H_ID = $H_ID AND Date = '$Date')";
        if(mysqli_num_rows(mysqli_query($conn, $sql_check_capacity)) > 0){
            echo "<script type=\"text/javascript\">window.alert('MAX appoinment reached on this date!! Please choose another date..');window.location.href = '/chms/patient/make-appoinment.php?D_ID=".$D_ID."';</script>";
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
        if(isset($_POST['appoinment']) || isset($_GET['D_ID'])){
        $D_ID = 0;
        if(isset($_POST['appoinment'])){
            $D_ID = $_POST['Doctor_ID'];
        }
        else if(isset($_GET['D_ID'])){
            $D_ID = $_GET['D_ID'];
        }
        $sql_info = "SELECT d.Title, d.Fname, d.Lname, u.Photo
                    FROM doctor d, user_details u
                    WHERE d.ID = u.ID AND d.ID = ".$D_ID."";

            $result_info = mysqli_query($conn, $sql_info);
            $found_user = mysqli_fetch_array($result_info);

            $Title = $found_user['Title'];
            $Fname = $found_user['Fname'];
            $Lname = $found_user['Lname'];
            $Photo = $found_user['Photo'];

            echo '<table align="center" >';

            if($Photo == ""){
                echo '<div align="center"><img src="/chms/doctor/img/null.jpg" alt="'.$Fname.' '.$Lname.'" width="200px" height="200px"></div>';
            }
            else{
                echo '<div align="center"><img src="/chms/doctor/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" width="120px" height="120px"></div>';
            }

            echo '<div id="joy_congratulation" align="center">'.
                $Title .' Dr. '. $Fname . ' '. $Lname .'</div>';

            echo '
                <form action="" method="POST" style="margin: 0px; padding: 0px;">
                <tr>
                    <td>
                        <div id="joy_congratulation">
                            <lebel for="H_ID">Select Hospital: </lebel>
                        </div>
                    </td>
                    <td>
                        <div id="joy_congratulation">
                            <select id="H_ID" name="H_ID">';

            $sql_hospital_list = "SELECT c.H_ID, h.Name, u.Address
                                FROM consultation c, hospital h, user_details u
                                WHERE c.D_ID = ".$D_ID." AND c.H_ID = h.ID AND h.ID = u.ID
                                GROUP BY c.H_ID";

            $result_hospital_list = mysqli_query($conn, $sql_hospital_list);
            if (mysqli_num_rows($result_hospital_list) > 0){
                echo '<option value="0">Select Hospital</option>';
                while ($row = mysqli_fetch_assoc($result_hospital_list)){
                    echo '<option value="'.$row['H_ID'].'">'.$row['Name'] .' - '.$row['Address'].'</option>';
                }
            }
            else{
                echo '<option value="0">No Hospital Available</option>';
            }
                    echo '</select></div></td></tr>
                    <tr>
                        <td>
                            <div id="joy_congratulation">
                                <lebel for="Date">Select a Date: </lebel>
                            </div>
                        </td>
                        <td>
                            <div id="joy_congratulation">
                                <select id="Date" name="Date" disabled onchange="getSlot()">
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
                                <input type="hidden" id="D_ID" name="D_ID" value="'.$D_ID.'">
                                <input type="submit" name="submit" value="Submit">
                            </div>
                        </td>
                    </tr></form></table>';


    }
    else{
        redirect_to("/chms/");
    }
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
        document.getElementById("H_ID").addEventListener("change", function (){
            var H_ID = this.value;
            var D_ID = document.getElementById("D_ID").value;
            var dateSelect = document.getElementById("Date");
            var slotDisplay = document.getElementById("slotDisplay");

            if (H_ID !== "0") {
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
