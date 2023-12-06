<?php
ob_start();
include("../common/connection.php");
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");

$User_ID = $_SESSION['User_ID'];

if(isset($_POST['submit'])){
    
    $target_dir = "img/"; // Specify the directory where you want to save uploaded files
    
    // Define a custom file name (e.g., using a timestamp)
    $custom_file_name = "profile-" . $User_ID; // You can customize this part as needed

    // Get the file extension from the original file name
    $file_extension = pathinfo($_FILES["Photo"]["name"], PATHINFO_EXTENSION);

    // Combine the custom file name with the file extension
    $target_file = $target_dir . $custom_file_name . "." . $file_extension;

    // Check if a file was uploaded and if it's a valid file
    if (!empty($_FILES["Photo"]["name"])) {
        if (file_exists($target_file)) {
            // The file already exists, so delete the old file
            if (unlink($target_file)) {
                // Attempt to move the uploaded file to the specified directory with the custom file name
                if (move_uploaded_file($_FILES["Photo"]["tmp_name"], $target_file)) {
                    echo "File " . htmlspecialchars($custom_file_name) . " has been replaced.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "Sorry, there was an error deleting the old file.";
            }
        } else {
            // The file does not exist, so simply move the uploaded file with the custom name
            if (move_uploaded_file($_FILES["Photo"]["tmp_name"], $target_file)) {
                echo "File " . htmlspecialchars($custom_file_name) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "Please select a file to upload.";
        $target_file = "";
    }



    $Name = $_POST['Name'];
    $License_Number = $_POST['License_Number'];
    $Phone_Number = $_POST['Phone_Number'];
    $Email = $_POST['Email'];
    $Address = $_POST['Address'];

    $query_phone = "SELECT *
                    FROM user_details
                    WHERE Phone_Number = '$Phone_Number' AND ID != $User_ID";
    $query_email = "SELECT *
                    FROM user_details
                    WHERE Email ='$Email' AND ID != $User_ID";
    $query_license = "SELECT *
                    FROM hospital
                    WHERE License_Number = $License_Number AND ID != $User_ID";
    $result_email = mysqli_query($conn, $query_email);
    $result_phone = mysqli_query($conn, $query_phone);
    $result_license = mysqli_query($conn, $query_license);
    
    $count = mysqli_fetch_row($result_phone);
    if ($count > 0) {
        echo "<script type=\"text/javascript\">window.alert('Phone Number Already Exists!');window.location.href = '/chms/hospital/edit-profile.php';</script>";
    }

    else if ($count = mysqli_fetch_row($result_email) > 0) {
        echo "<script type=\"text/javascript\">window.alert('Email Address Already Exists!');window.location.href = '/chms/hospital/edit-profile.php';</script>";
    }
    else if ($count = mysqli_fetch_row($result_license) > 0) {
        echo "<script type=\"text/javascript\">window.alert('License Number Already Exists!');window.location.href = '/chms/hospital/edit-profile.php';</script>";
    }

    else{
        $sql_check = "SELECT *
                        FROM hospital h, user_details u
                        WHERE h.ID = u.ID AND u.ID = $User_ID ";

        $result_check = mysqli_query($conn, $sql_check);
        confirm_query($result_check);

        if(mysqli_num_rows($result_check) == 1){
            
            //Modify user- phone email address photo
            $sql_update_user = "";

                if($target_file == ""){
                    $sql_update_user = "UPDATE user_details
                                        SET Phone_Number = '$Phone_Number', Email = '$Email', Address = '$Address', Photo = NULL
                                        WHERE ID = $User_ID";
                }
                else{
                    $sql_update_user = "UPDATE user_details
                                        SET Phone_Number = '$Phone_Number', Email = '$Email', Address = '$Address', Photo = '$target_file'
                                        WHERE ID = $User_ID ";
                }
           
            //modify doctor license fname lname dob gender experience position location language bio
            $sql_update_hospital = "UPDATE hospital
                                    SET License_Number = $License_Number, Name = '$Name'
                                    WHERE ID = $User_ID";

            if (mysqli_query($conn, $sql_update_user) && mysqli_query($conn, $sql_update_hospital)){
                echo "<script type=\"text/javascript\">window.alert('Profile Successfully Updated.');window.location.href = '/chms/common/profile.php';</script>";
            } 
            else{
                echo "Error: " . $sql . "" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
        else{
            //Modify user
            $sql_update_user = "";

                if($target_file == ""){
                    $sql_update_user = "UPDATE user_details
                                        SET Phone_Number = '$Phone_Number', Email = '$Email', Address = '$Address', Photo = NULL
                                        WHERE ID = $User_ID";
                }
                else{
                    $sql_update_user = "UPDATE user_details
                                        SET Phone_Number = '$Phone_Number', Email = '$Email', Address = '$Address', Photo = '$target_file'
                                        WHERE ID = $User_ID";
                }

            //insert hospital
            $sql_insert_hospital = "INSERT INTO hospital(ID, License_Number, Name)
                                    VALUES ($User_ID, $License_Number, '$Name')";

            if (mysqli_query($conn, $sql_update_user) && mysqli_query($conn, $sql_insert_hospital)){
                echo "<script type=\"text/javascript\">window.alert('Profile Successfully Updated.');window.location.href = '/chms/common/profile.php';</script>";
            } 
            else{
                echo "Error: " . $sql . "" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

}



$sql = "SELECT h.Name, h.License_Number, u.Phone_Number, u.Email, u.Address
        FROM hospital h, user_details u
        WHERE h.ID = u.ID AND u.ID = $User_ID";

$result = mysqli_query($conn, $sql);
confirm_query($result);

if(mysqli_num_rows($result) == 1){
    $found_user = mysqli_fetch_array($result);
                $Name = $found_user['Name'];
                $License_Number = $found_user['License_Number'];
                $Phone_Number = $found_user['Phone_Number'];
                $Email = $found_user['Email'];
                $Address = $found_user['Address'];
                
    echo
        '
<form action="" method="POST" enctype="multipart/form-data">
    <table align="center">
        <caption><div id="joy_congratulation">Edit Profile</div></caption>
        <tr>
            <td>
                <label for="Name">Hospital Name<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Name" id="Name" required value="'.$Name.'">
            </td>
        </tr>
        
        <tr>
            <td>
                <label for="License_Number">License Number<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="number" name="License_Number" id="License_Number" required value="'.$License_Number.'">
            </td>
        </tr>

        <tr>
            <td>
                <label for="Phone_Number">Phone Number<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Phone_Number" id="Phone_Number" value="'.$Phone_Number.'" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Email">Email <font color="red">*</font>: </label>
            </td>
            <td>
                <input type="email" name="Email" id="Email" required value="'.$Email.'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Address">Hospital Location<font color="red">*</font>: </label>
            </td>
            <td>
                <textarea id="Address" name="Address" rows="2" cols="30" required> '.$Address.' </textarea>
            </td>
        </tr>
       
        <tr>
            <td>
                <label for="Photo">Select Profile Photo: </label>
            </td>
            <td>
                <input type="file" name="Photo" id="Photo">
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="submit" value="Submit">
            </td>
        </tr>
    </table>
</form>
        ';
    }

    else{
        $sql_retrive = "SELECT Phone_Number
                        FROM user_details
                        WHERE ID = $User_ID";

        $result = mysqli_query($conn, $sql_retrive);
        confirm_query($result);
        $found_user = mysqli_fetch_array($result);

         $Phone_Number = $found_user['Phone_Number'];

        echo 

        '
        <form action="" method="POST" enctype="multipart/form-data">
    <table align="center">
        <caption><div id="joy_congratulation">Complete Your Profile First</div></caption>
        <tr>
            <td>
                <label for="Name">Hospital Name<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Name" id="Name" required>
            </td>
        </tr>
        
        <tr>
            <td>
                <label for="License_Number">License Number<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="number" name="License_Number" id="License_Number" required>
            </td>
        </tr>

        <tr>
            <td>
                <label for="Phone_Number">Phone Number<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Phone_Number" id="Phone_Number" value="'.$Phone_Number.'" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Email">Email <font color="red">*</font>: </label>
            </td>
            <td>
                <input type="email" name="Email" id="Email" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Address">Hospital Location<font color="red">*</font>: </label>
            </td>
            <td>
                <textarea id="Address" name="Address" rows="2" cols="30" required></textarea>
            </td>
        </tr>
        
        <tr>
            <td>
                <label for="Photo">Select Profile Photo: </label>
            </td>
            <td>
                <input type="file" name="Photo" id="Photo">
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" name="submit" value="Submit">
            </td>
        </tr>
    </table>
</form>

        ';
    }
ob_end_flush();
?>

<<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Edit - Hospital</title>
</head>
<body>

</body>
</html>





