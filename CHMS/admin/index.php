<?php
ob_start();
include("../common/connection.php");
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];


$sql_count_total = "SELECT COUNT(*) as Total
                    FROM user_details
                    WHERE Status = TRUE";
$result_count_total = mysqli_query($conn, $sql_count_total);
$found_total = mysqli_fetch_array($result_count_total);
$total_active_user = $found_total['Total'] - 1;


$total_active_patient = 0;
$total_active_diagnostic_center = 0;
$total_active_hospital = 0;
$total_active_doctor = 0;



$sql_count_active_user = "SELECT Role, COUNT(*) as User_Number
                            FROM user_details
                            WHERE Status = TRUE
                            GROUP BY Role
                            ORDER BY Role";
$result_count_active_user = mysqli_query($conn, $sql_count_active_user);


if (mysqli_num_rows($result_count_active_user) > 0){
    while ($row = mysqli_fetch_assoc($result_count_active_user)){

        if($row['Role'] == 'Patient'){
            $total_active_patient = $row['User_Number'];
        }
        else if($row['Role'] == 'Doctor'){
            $total_active_doctor = $row['User_Number'];
        }
        else if($row['Role'] == 'Hospital'){
            $total_active_hospital = $row['User_Number'];
        }
        else if($row['Role'] == 'Diagnostic Center'){
            $total_active_diagnostic_center = $row['User_Number'];
        }
    }
}



$sql_count_total = "SELECT COUNT(*) as Total
                    FROM user_details
                    WHERE Status = FALSE";
$result_count_total = mysqli_query($conn, $sql_count_total);
$found_total = mysqli_fetch_array($result_count_total);
$total_pending_user = $found_total['Total'];


$total_pending_diagnostic_center = 0;
$total_pending_hospital = 0;
$total_pending_doctor = 0;



$sql_count_pending_user = "SELECT Role, COUNT(*) as User_Number
                            FROM user_details
                            WHERE Status = FALSE
                            GROUP BY Role
                            ORDER BY Role";
$result_count_pending_user = mysqli_query($conn, $sql_count_pending_user);



if (mysqli_num_rows($result_count_pending_user) > 0){
    while ($row = mysqli_fetch_assoc($result_count_pending_user)){

        if($row['Role'] == 'Doctor'){
            $total_pending_doctor = $row['User_Number'];
        }
        else if($row['Role'] == 'Hospital'){
            $total_pending_hospital = $row['User_Number'];
        }
        else if($row['Role'] == 'Diagnostic Center'){
            $total_pending_diagnostic_center = $row['User_Number'];
        }
    }
}


$sql_count_pending_Reviews = "SELECT COUNT(*) as Pending_Reviews
                            FROM doctor_reviews
                            WHERE Status = FALSE";
$result_count_pending_Reviews = mysqli_query($conn, $sql_count_pending_Reviews);

$found_pending_review = mysqli_fetch_array($result_count_pending_Reviews);

$total_pending_reviews = $found_pending_review['Pending_Reviews'];




$sql_count_pending_test = "SELECT COUNT(*) as Pending_Test
                            FROM test
                            WHERE Status = FALSE";
$result_count_pending_test = mysqli_query($conn, $sql_count_pending_test);

$found_pending_test = mysqli_fetch_array($result_count_pending_test);

$total_pending_test = $found_pending_test['Pending_Test'];


$sql_count_pending_vaccine = "SELECT COUNT(*) as Pending_Vaccine
                            FROM vaccination_logs
                            WHERE Status = FALSE";
$result_count_pending_vaccine = mysqli_query($conn, $sql_count_pending_vaccine);

$found_pending_vaccine = mysqli_fetch_array($result_count_pending_vaccine);

$total_pending_vaccine = $found_pending_vaccine['Pending_Vaccine'];


echo '
<div id="joy_warning" align="center" style="font-size: 30; font-weight: bold;">Dashboard</div>
    <table align="center" class="joy">
    <caption>
        <div id="joy_congratulation">
            All Stats
        </div>
    </caption>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/users.php?Status=1">Total Active User: '.$total_active_user.'</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/active-patient.php">Total Active Patient: '.$total_active_patient.'</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/doctors.php?Status=1">Total Active Doctor: '.$total_active_doctor.'</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/hospitals.php?Status=1">Total Active Hospital: '.$total_active_hospital.'</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/admin/diagnostic-centers.php?Status=1">Total Active Diagnostic Center: '.$total_active_diagnostic_center.'</a>
                </div>
            </td>
            <td>
                <div id="joy_warning" align="center">
                    <a href="/chms/admin/users.php?Status=0">Total Pending User: '.$total_pending_user.'</a>
                </div>
            </td>
            <td>
                <div id="joy_warning" align="center">
                    <a href="/chms/admin/doctors.php?Status=0">Total Pending Doctor: '.$total_pending_doctor.'</a>
                </div>
            </td>
            <td>
                <div id="joy_warning" align="center">
                    <a href="/chms/admin/hospitals.php?Status=0">Total Pending Hospital: '.$total_pending_hospital.'</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_warning" align="center">
                    <a href="/chms/admin/diagnostic-centers.php?Status=0">Total Pending Diagnostic Center: '.$total_pending_diagnostic_center.'</a>
                </div>
            </td>
            <td>
                <div id="joy_warning" align="center">
                    <a href="/chms/admin/pending-reviews.php">Total Pending Reviews: '.$total_pending_reviews.'</a>
                </div>
            </td>
            <td>
                <div id="joy_warning" align="center">
                    <a href="/chms/admin/pending-test.php">Total Pending Test: '.$total_pending_test.'</a>
                </div>
            </td>
            <td>
                <div id="joy_warning" align="center">
                    <a href="/chms/admin/pending-vaccine.php">Total Pending Vaccine Info: '.$total_pending_vaccine.'</a>
                </div>
            </td>
        </tr>
    </table>';
ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - Admin</title>
</head>
<body>
    
</body>
</html>