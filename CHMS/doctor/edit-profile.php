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



    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];
    $Title = $_POST['Title'];
    $License_Number = $_POST['License_Number'];
    $DOB = $_POST['DOB'];
    $Gender = $_POST['Gender'];
    $Experience_From = $_POST['Experience_From'];
    $Language = $_POST['Language'];
    $Current_Position = $_POST['Current_Position'];
    $P_Location = $_POST['P_Location'];
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
                    FROM doctor
                    WHERE License_Number = $License_Number AND ID != $User_ID";
    $result_email = mysqli_query($conn, $query_email);
    $result_phone = mysqli_query($conn, $query_phone);
    $result_license = mysqli_query($conn, $query_license);
    
    $count = mysqli_fetch_row($result_phone);
    if ($count > 0) {
        echo "<script type=\"text/javascript\">window.alert('Phone Number Already Exists!');window.location.href = '/chms/doctor/edit-profile.php';</script>";
    }

    else if ($count = mysqli_fetch_row($result_email) > 0) {
        echo "<script type=\"text/javascript\">window.alert('Email Address Already Exists!');window.location.href = '/chms/doctor/edit-profile.php';</script>";
    }
    else if ($count = mysqli_fetch_row($result_license) > 0) {
        echo "<script type=\"text/javascript\">window.alert('License Number Already Exists!');window.location.href = '/chms/doctor/edit-profile.php';</script>";
    }

    else{
        $sql_check = "SELECT *
                    FROM doctor d, user_details u
                    WHERE d.ID = u.ID AND u.ID = $User_ID";

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
                                        WHERE ID = $User_ID";
                }
           
            //modify doctor license fname lname dob gender experience position location language bio
            $sql_update_doctor = "UPDATE doctor
                                SET License_Number = $License_Number, Fname = '$Fname', Lname = '$Lname', Title = '$Title', DOB = '$DOB', Gender = '$Gender', Experience_From = '$Experience_From', Language = '$Language', Current_Position = '$Current_Position', P_Location = '$P_Location'
                                WHERE ID = $User_ID";

            if (mysqli_query($conn, $sql_update_user) && mysqli_query($conn, $sql_update_doctor)){
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

            //insert doctor
            $sql_insert_doctor = "INSERT INTO doctor(ID, License_Number, Fname, Lname, Title, DOB, Gender, Experience_From, Language, Current_Position, P_Location)
                                VALUES ($User_ID, $License_Number, '$Fname', '$Lname', '$Title', '$DOB', '$Gender', '$Experience_From', '$Language', '$Current_Position', '$P_Location')";

            if (mysqli_query($conn, $sql_update_user) && mysqli_query($conn, $sql_insert_doctor)){
                echo "<script type=\"text/javascript\">window.alert('Profile Successfully Updated.');window.location.href = '/chms/common/profile.php';</script>";
            } 
            else{
                echo "Error: " . $sql . "" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

}



$sql = "SELECT d.Fname, d.Lname, d.Title, d.License_Number, d.DOB, d.Gender, d.Experience_From, d.Language, d.Current_Position, d.P_Location, u.Phone_Number, u.Email, u.Address
        FROM doctor d, user_details u
        WHERE d.ID = u.ID AND u.ID = $User_ID";

$result = mysqli_query($conn, $sql);
confirm_query($result);

if(mysqli_num_rows($result) == 1){
    $found_user = mysqli_fetch_array($result);
                $Fname = $found_user['Fname'];
                $Lname = $found_user['Lname'];
                $Title = $found_user['Title'];
                $License_Number = $found_user['License_Number'];
                $DOB = $found_user['DOB'];
                $Gender = $found_user['Gender'];
                $Experience_From = $found_user['Experience_From'];
                $Language = $found_user['Language'];
                $Current_Position = $found_user['Current_Position'];
                $P_Location = $found_user['P_Location'];
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
                <label for="Fname">First Name<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Fname" id="Fname" required value="'.$Fname.'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Lname">Last Name: </label>
            </td>
            <td>
                <input type="text" name="Lname" id="Lname" value="'.$Lname.'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Title">Title: </label>
            </td>
            <td>
                <input type="text" name="Title" id="Title" value="'.$Title.'">
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
                <label for="DOB">Date of Birth<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="Date" name="DOB" id="DOB" required value="'.$DOB.'" max="'.date('Y-m-d').'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Gender">Select Gender<font color="red">*</font>: </label>
            </td>
            <td>
                <select id="Gender" name="Gender" required>';
                if($Gender == "Male"){
                    echo
                        '
                        <option value="Male" selected>Male</option>
                        <option value="Female">Female</option>
                        ';
                }
                else{
                    echo
                        '<option value="Male">Male</option>
                        <option value="Female" selected>Female</option>';
                }
                 
                   echo'
                   </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Experience_From">Experience From<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="Date" name="Experience_From" id="Experience_From" required value="'.$Experience_From.'" max="'.date('Y-m-d').'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Language">Language<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Language" id="Language" required value="'.$Language.'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Current_Position">Current Position<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Current_Position" id="Current_Position" required value="'.$Current_Position.'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="P_Location">Location of your Position<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="P_Location" id="P_Location" required value="'.$P_Location.'">
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
                <label for="Email">Email<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="email" name="Email" id="Email" required value="'.$Email.'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Address">Address<font color="red">*</font>: </label>
            </td>
            <td>
                <textarea id="Address" name="Address" rows="2" cols="30" required>'.$Address.'</textarea>
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
        $sql_retrive = "SELECT u.Phone_Number FROM user_details u WHERE u.ID = $User_ID";

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
                <label for="Fname">First Name<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Fname" id="Fname" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Lname">Last Name: </label>
            </td>
            <td>
                <input type="text" name="Lname" id="Lname">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Title">Title: </label>
            </td>
            <td>
                <input type="text" name="Title" id="Title">
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
                <label for="DOB">Date of Birth<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="Date" name="DOB" id="DOB" max="'.date('Y-m-d').'" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Gender">Select Gender<font color="red">*</font>: </label>
            </td>
            <td>
                <select id="Gender" name="Gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Experience_From">Experience From<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="Date" name="Experience_From" id="Experience_From" required max="'.date('Y-m-d').'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Language">Language<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Language" id="Language" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Current_Position">Current Position<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Current_Position" id="Current_Position" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="P_Location">Location of your Position<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="P_Location" id="P_Location" required>
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
                <label for="Address">Address<font color="red">*</font>: </label>
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
    <title>Profile Edit - Doctor</title>
</head>
<body>

</body>
</html>