<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];

$sql_info = "SELECT p.ID, p.Fname, p.Lname, p.DOB, u.Phone_Number, p.Emergency_Number, u.Address, p.Blood, u.Photo
				FROM patient p, user_details u
				WHERE p.ID = u.ID AND p.ID = $User_ID";

        $result_info = mysqli_query($conn, $sql_info);
        $found_user = mysqli_fetch_array($result_info);

        
        $ID = $found_user['ID'];
        $Fname = $found_user['Fname'];
        $Lname = $found_user['Lname'];
        $DOB = $found_user['DOB'];
        $Phone_Number = $found_user['Phone_Number'];
        $Emergency_Number = $found_user['Emergency_Number'];
        $Address = $found_user['Address'];
        $Blood = $found_user['Blood'];
        $Photo = $found_user['Photo'];



echo '
<center>
	<div class="id-card">
        <div class="id-card-header">';

        if($Photo == ""){
            echo '<img src="/chms/patient/img/null.jpg" alt="'.$Fname.' '.$Lname.'">';
        }
        else{
            echo '<img src="/chms/patient/'.$Photo.'" alt="'.$Fname.' '.$Lname.'" >';
        }
            echo '
        </div>
        <div class="id-card-details">
            <h2>'.$Fname.' '.$Lname.'</h2>
            <p>Patient ID: '.$ID.'</p>
            <p>Date of Birth: '.$DOB.'</p>
            <p>Phone: '.$Phone_Number.'</p>
            <p>Emergency Contact: '.$Emergency_Number.'</p>
            <p>Address: '.$Address.'</p>
            <p>Blood Type: '.$Blood.'</p>
        </div>
    </div><br>
    <button class="print-button" onclick="window.print()">Print Card</button></center>
    ';




ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Patient Card</title>
</head>
<body>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    
</body>
</html>