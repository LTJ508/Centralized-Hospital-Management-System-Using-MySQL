<?php
ob_start();
include("../common/connection.php");
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];

$query_profile = "SELECT * FROM patient WHERE ID = $User_ID";
$result = mysqli_query($conn, $query_profile);
confirm_query($result);

if(mysqli_num_rows($result) != 1){
    redirect_to("/chms/patient/edit-profile.php");
    }
    else{
        $found_user = mysqli_fetch_array($result);
        $Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];

        $qury_prescription = "SELECT * FROM prescriptions WHERE P_ID = $User_ID";
        $qury_test = "SELECT * FROM test_log WHERE P_ID = $User_ID";
        $result_prescription = mysqli_query($conn, $qury_prescription);
        $result_test = mysqli_query($conn, $qury_test);



        echo 
    '
        <div id="joy_congratulation" align="center">Welcome '.$Fname.' '.$Lname.'(ID-'.$User_ID.') <span style="float:right">Prescription: '. mysqli_num_rows($result_prescription) .' || Test: '. mysqli_num_rows($result_test) .'</span></div>
    ';
    }
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Patient</title>
</head>
<body>
    <div id="joy_warning" align="center" style="font-size: 30; font-weight: bold;">Dashboard</div>
    <table align="center" class="joy">
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/common/find-doctor.php">Find a Doctor</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/patient/book-appoinment.php">Book an Appointment</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/patient/prescription.php">See all Prescription</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/patient/test.php">See all Test</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/common/test-price.php">Test Pricing Info</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/common/medicine-price.php">Medicine Pricing Info</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/patient/allergy.php">Allergy History</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/patient/vaccine.php">Vaccine History</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/patient/review.php">Provide Reviews</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/patient/appoinment.php">Appoinment</a>
                </div>
            </td>
            <td colspan="2">
                <div id="joy_congratulation" align="center">
                    <a href="/chms/patient/card.php">Print Patient Card</a>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>