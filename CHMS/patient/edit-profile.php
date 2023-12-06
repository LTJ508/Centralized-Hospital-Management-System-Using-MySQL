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
    $DOB = $_POST['DOB'];
    $Gender = $_POST['Gender'];
    $Phone_Number = $_POST['Phone_Number'];
    $Emergency_Number = $_POST['Emergency_Number'];
    $Email = $_POST['Email'];
    $Address = $_POST['Address'];
    $Blood = $_POST['Blood'];
    $Weight = $_POST['Weight'];


    if($Email != ""){
        $query_email = "SELECT *
                        FROM user_details
                        WHERE Email = '$Email' AND ID != $User_ID";
        $result_email = mysqli_query($conn, $query_email);

        if ($count = mysqli_fetch_row($result_email) > 0) {
        echo "<script type=\"text/javascript\">window.alert('Email Address Already Exists!');window.location.href = '/chms/patient/edit-profile.php';</script>";
        }
    }


    $query_phone = "SELECT *
                    FROM user_details
                    WHERE Phone_Number ='$Phone_Number' AND ID != $User_ID";
    
    $result_phone = mysqli_query($conn, $query_phone);
    
    $count = mysqli_fetch_row($result_phone);
    if ($count > 0) {
        echo "<script type=\"text/javascript\">window.alert('Phone Number Already Exists!');window.location.href = '/chms/patient/edit-profile.php';</script>";
    }
    else{
        $sql_check = "SELECT *
                    FROM patient p, user_details u
                    WHERE p.ID = u.ID AND u.ID = $User_ID";

        $result_check = mysqli_query($conn, $sql_check);
        confirm_query($result_check);

        if(mysqli_num_rows($result_check) == 1){
            
            //Modify user- phone email address photo
            $sql_update_user = "";
            if($Email == ""){
                if($target_file == ""){
                    $sql_update_user = "UPDATE user_details
                                        SET Phone_Number = '$Phone_Number', Email = NULL, Address = '$Address', Photo = NULL
                                        WHERE ID = $User_ID";
                }
                else{
                    $sql_update_user = "UPDATE user_details
                                        SET Phone_Number = '$Phone_Number', Email = NULL, Address = '$Address', Photo = '$target_file'
                                        WHERE ID = $User_ID";
                }
                
            }
            else{
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
            }
            
            
            //modify patient fname lname dob gender emergency blood weight
            $sql_update_patient = "UPDATE patient
                                    SET Fname = '$Fname', Lname = '$Lname', DOB = '$DOB', Gender = '$Gender', Emergency_Number = '$Emergency_Number', Blood = '$Blood', Weight = $Weight
                                    WHERE ID = $User_ID";

            if (mysqli_query($conn, $sql_update_user) && mysqli_query($conn, $sql_update_patient)){
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
            if($Email == ""){
                if($target_file == ""){
                    $sql_update_user = "UPDATE user_details
                                        SET Phone_Number = '$Phone_Number', Email = NULL, Address = '$Address', Photo = NULL
                                        WHERE ID = $User_ID";
                }
                else{
                    $sql_update_user = "UPDATE user_details
                                        SET Phone_Number = '$Phone_Number', Email = NULL, Address = '$Address', Photo = '$target_file'
                                        WHERE ID = $User_ID";
                }
                
            }
            else{
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
            }
            //insert patient
            $sql_insert_patient = "INSERT INTO patient(ID, Fname, Lname, DOB, Gender, Emergency_Number, Blood, Weight)
                                VALUES ($User_ID,'$Fname','$Lname','$DOB', '$Gender', '$Emergency_Number', '$Blood', $Weight)";

            if (mysqli_query($conn, $sql_update_user) && mysqli_query($conn, $sql_insert_patient)){
                echo "<script type=\"text/javascript\">window.alert('Profile Successfully Updated.');window.location.href = '/chms/common/profile.php';</script>";
            } 
            else{
                echo "Error: " . $sql . "" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
    }

}



$sql = "SELECT p.Fname, p.Lname, p.DOB, p.Gender, u.Phone_Number, p.Emergency_Number, u.Email, u.Address, p.Blood, p.Weight
        FROM patient p, user_details u
        WHERE p.ID = u.ID AND u.ID = $User_ID";

$result = mysqli_query($conn, $sql);
confirm_query($result);

if(mysqli_num_rows($result) == 1){
    $found_user = mysqli_fetch_array($result);
                $Fname = $found_user['Fname'];
                $Lname = $found_user['Lname'];
                $DOB = $found_user['DOB'];
                $Gender = $found_user['Gender'];
                $Phone_Number = $found_user['Phone_Number'];
                $Emergency_Number = $found_user['Emergency_Number'];
                $Email = $found_user['Email'];
                $Address = $found_user['Address'];
                $Blood = $found_user['Blood'];
                $Weight = $found_user['Weight'];
                
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
                <input type="text" name="Fname" id="Fname" value="'.$Fname.'" required>
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
                <label for="DOB">Date of Birth<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="Date" name="DOB" id="DOB" value="'.$DOB.'" max="'.date('Y-m-d').'" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Gender">Select Gender<font color="red">*</font>: </label>
            </td>
            <td>
                <select id="Gender" name="Gender" required>';
                if($Gender == 'Male'){
                    echo 
                        '<option value="Male" selected>Male</option>
                        <option value="Female">Female</option>';
                }
                else{
                    echo 
                        '<option value="Male" >Male</option>
                        <option value="Female" selected>Female</option>';
                }
                echo 
                     '
                    </select>
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
                <label for="Emergency_Number">Emergency Number<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Emergency_Number" id="Emergency_Number" value="'.$Emergency_Number.'" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Email">Email: </label>
            </td>
            <td>
                <input type="email" name="Email" id="Email" value="'.$Email.'">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Address">Address: </label>
            </td>
            <td>
               <textarea id="Address" name="Address" rows="2" cols="30">'.$Address.'</textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Blood">Select Blood Group: </label>
            </td>
            <td>
                <select id="Blood" name="Blood">';

                if($Blood == 'A+'){
                    echo
                    '
                    <option value="NULL">Select</option>
                        <option value="A+" selected>A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>';
                }
                else if($Blood == 'A-'){
                    echo
                    '
                    <option value="NULL">Select</option>
                        <option value="A+" >A+</option>
                        <option value="A-" selected>A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>';
                }
                else if($Blood == 'B+'){
                    echo
                    '
                    <option value="NULL">Select</option>
                        <option value="A+" >A+</option>
                        <option value="A-">A-</option>
                        <option value="B+" selected>B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>';
                }
                else if($Blood == 'B-'){
                    echo
                    '
                    <option value="NULL">Select</option>
                        <option value="A+" >A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-" selected>B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>';
                }
                else if($Blood == 'O+'){
                    echo
                    '
                    <option value="NULL">Select</option>
                        <option value="A+" >A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+" selected>O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>';
                }
                else if($Blood == 'O-'){
                    echo
                    '
                    <option value="NULL">Select</option>
                        <option value="A+" >A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-" selected>O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>';
                }
                else if($Blood == 'AB+'){
                    echo
                    '
                    <option value="NULL">Select</option>
                        <option value="A+" >A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+" selected>AB+</option>
                        <option value="AB-">AB-</option>';
                }
                else if($Blood == 'AB-'){
                    echo
                    '
                    <option value="NULL">Select</option>
                        <option value="A+" >A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-" selected>AB-</option>';
                }
                else{
                    echo
                    '
                    <option value="NULL" selected>Select</option>
                        <option value="A+" >A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>';
                }
                        

                    echo 
                    '
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Weight">Weight: </label>
            </td>
            <td>
                <input type="number" name="Weight" id="Weight" value="'.$Weight.'" required>
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
        $sql_retrive = "SELECT u.Phone_Number, u.Email, u.Address FROM user_details u WHERE u.ID = $User_ID";

        $result = mysqli_query($conn, $sql_retrive);
        confirm_query($result);
        $found_user = mysqli_fetch_array($result);

         $Phone_Number = $found_user['Phone_Number'];

        echo 

        '<form action="" method="POST" enctype="multipart/form-data">
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
                <label for="Phone_Number">Phone Number<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Phone_Number" id="Phone_Number" value="'.$Phone_Number.'" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Emergency_Number">Emergency Number<font color="red">*</font>: </label>
            </td>
            <td>
                <input type="text" name="Emergency_Number" id="Emergency_Number" required>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Email">Email: </label>
            </td>
            <td>
                <input type="email" name="Email" id="Email">
            </td>
        </tr>
        <tr>
            <td>
                <label for="Address">Address: </label>
            </td>
            <td>
                <textarea id="Address" name="Address" rows="2" cols="30"></textarea>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Blood">Select Blood Group: </label>
            </td>
            <td>
                <select id="Blood" name="Blood">
                        <option value="NULL">Select</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <label for="Weight">Weight: </label>
            </td>
            <td>
                <input type="number" name="Weight" id="Weight" required>
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
</form>';
    }
ob_end_flush();
?>

<<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Edit - Patient</title>
</head>
<body>

</body>
</html>
