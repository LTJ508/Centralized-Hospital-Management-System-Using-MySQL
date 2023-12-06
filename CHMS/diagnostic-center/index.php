<?php
ob_start();
include("../common/connection.php");
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];

$query_profile = "SELECT * FROM diagnostic_center WHERE ID = $User_ID";
$result = mysqli_query($conn, $query_profile);
confirm_query($result);

if(mysqli_num_rows($result) != 1){
    redirect_to("/chms/diagnostic-center/edit-profile.php");
    }
    else{
        $found_user = mysqli_fetch_array($result);
        $Name = $found_user['Name'];

        
        $qury_test = "SELECT * FROM test_log WHERE DC_ID = ". $User_ID ."";
        $result_test = mysqli_query($conn, $qury_test);



        echo 
    '
        <br><div id="joy_congratulation" align="center">Welcome ' . $Name .' <span style="float:right">Test: '. mysqli_num_rows($result_test) .'</span></div><br>
    ';
    $query_status = "SELECT * FROM user_details WHERE ID = $User_ID";
    $result_status = mysqli_query($conn, $query_status);
    confirm_query($result_status);
    $found_user = mysqli_fetch_array($result_status);
    $Status = $found_user['Status'];
    if($Status == FALSE){
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
    <title>Dashboard - Diagnostic Center</title>
</head>
<body>
    <div id="joy_warning" align="center" style="font-size: 30; font-weight: bold;">Dashboard</div>
    <table align="center" class="joy">
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/diagnostic-center/claim-test.php">Claim Test</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/diagnostic-center/add-test.php">Add New Test</a>
                </div>
            </td>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/diagnostic-center/update-test.php">Upload Test Report</a>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div id="joy_congratulation" align="center">
                    <a href="/chms/diagnostic-center/test.php">See all Test Report</a>
                </div>
            </td>
            <td colspan="2" align="center">
                <div id="joy_congratulation" align="center">
                    <a href="/chms/diagnostic-center/missing-test.php">Add Missing Test</a>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>