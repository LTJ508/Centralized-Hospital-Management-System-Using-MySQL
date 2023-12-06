<?php
ob_start();
include("../common/connection.php");
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];

$query_profile = "SELECT * FROM doctor WHERE ID = $User_ID";
$result = mysqli_query($conn, $query_profile);
confirm_query($result);

if(mysqli_num_rows($result) != 1){
    redirect_to("/chms/doctor/edit-profile.php");
    }
    else{
        $found_user = mysqli_fetch_array($result);
        $Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];

        $qury_prescription = "SELECT * FROM prescriptions WHERE D_ID = $User_ID";
        $result_prescription = mysqli_query($conn, $qury_prescription);



        echo 
    '
        <br><div id="joy_congratulation" align="center">Welcome ' . $Fname .' '. $Lname.'<span style="float:right">Prescribed: '. mysqli_num_rows($result_prescription) .' </span></div><br>
    ';
$query_status = "SELECT * FROM user_details WHERE ID = $User_ID";
$result_status = mysqli_query($conn, $query_status);
confirm_query($result_status);
$found_user = mysqli_fetch_array($result_status);
$Status = $found_user['Status'];
    if($Status == 0){
            echo '
                <center><div id="joy_warning" align="center" style="font-size:30px; width:300px">(Account Deactivated)</div></center><br>
            ';
        }
    }
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Doctor</title>
</head>
<body>
    <div id="joy_warning" align="center" style="font-size: 30; font-weight: bold;">Dashboard</div>
    <table align="center" class="joy">
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/doctor/search-patient.php">Search Patient</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/doctor/prescription.php">View All Prescription</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/doctor/appoinment.php">Check Appointment</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/doctor/awards-honors.php">Awards & Honors</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/doctor/speciality.php">Add Speciality</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/doctor/degree.php">Add Degree</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/doctor/journals-publications.php">Journals & Publications</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/doctor/consultation.php">See Your Consultation Hours</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/doctor/review.php">See Your Reviews</a>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>