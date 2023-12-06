<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];

if($_GET['Status'] == 1){
    $sql = "SELECT d.ID, d.Title, d.Fname, d.Lname, d.License_Number 
            FROM doctor d, user_details u
            WHERE d.ID = u.ID AND u.Status = TRUE";
    $Status = "Active";
}
else if($_GET['Status'] == 0){
    $sql = "SELECT d.ID, d.Title, d.Fname, d.Lname, d.License_Number
            FROM doctor d, user_details u
            WHERE d.ID = u.ID AND u.Status = FALSE";
    $Status = "Pending";
}
else{
    redirect_to("/chms/admin/");
}

$result = mysqli_query($conn, $sql);


// Pagination variables
$results_per_page = 10; // Number of results to display per page

// Calculate the total number of pages
$total_rows = mysqli_num_rows($result);
$total_pages = ceil($total_rows / $results_per_page);

// Get the current page number from the URL query parameter
if (isset($_GET['page'])) {
    $current_page = $_GET['page'];
} else {
    $current_page = 1; // Default to the first page
}

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $results_per_page;




// Modify your SQL query to include LIMIT and OFFSET
if($_GET['Status'] == 1){
    $sql = "SELECT d.ID, d.Title, d.Fname, d.Lname, d.License_Number 
            FROM doctor d, user_details u
            WHERE d.ID = u.ID AND u.Status = TRUE
            ORDER BY d.ID
            LIMIT $results_per_page OFFSET $offset";
}
else if($_GET['Status'] == 0){
    $sql = "SELECT d.ID, d.Title, d.Fname, d.Lname, d.License_Number
            FROM doctor d, user_details u
            WHERE d.ID = u.ID AND u.Status = FALSE
            ORDER BY d.ID
            LIMIT $results_per_page OFFSET $offset";
}

// Execute the modified query
$result = mysqli_query($conn, $sql);



echo '
<table border="1" align="center">
    <caption>
        <div id="joy_congratulation" align="center">
            All '.$Status.' Doctor List (10 Per Page)
        </div>
    </caption>
    <tr>
        <th>
            <div id="joy_congratulation" align="center">
                ID
            </div>
        </th>
        <th>
            <div id="joy_congratulation" align="center">
                Name
            </div>
        </th>
        <th>
            <div id="joy_congratulation" align="center">
                License Number
            </div>
        </th>
        <th>
            <div id="joy_congratulation" align="center">
                Action
            </div>
        </th>
        <th>
            <div id="joy_warning" align="center">
                Action
            </div>
        </th>
    </tr>
';


 if (mysqli_num_rows($result) > 0){
    while ($row = mysqli_fetch_assoc($result)){
        echo '
            <tr>
                <td>
                    <div id="joy_congratulation" align="center">
                        '.$row['ID'].'
                    </div>
                </td>
                <td>
                    <div id="joy_congratulation" align="center">
                        '.$row['Title'].' Dr. '.$row['Fname'].' '.$row['Lname'].'
                    </div>
                </td>
                <td>
                    <div id="joy_congratulation" align="center">
                        '.$row['License_Number'].'
                    </div>
                </td>
                <td>
                    <div id="joy_congratulation" align="center">
                        <form action="/chms/admin/doctor-view.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
                            <input type="hidden" id="D_ID" name="D_ID" value="'.$row['ID'].'">
                            <input type="submit" name="view" value="View Details">
                        </form>
                    </div>
                </td>
                <td>
                    <div id="joy_warning" align="center">
                        <form action="/chms/admin/doctor-view.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
                            <input type="hidden" id="D_ID" name="D_ID" value="'.$row['ID'].'">
                            <input type="submit" name="delete" value="Delete User">
                        </form>
                    </div>
                </td>
            </tr>
            ';
    }
    echo '
        <tr>
            <td colspan="5" align="center">
                <div id="joy_warning">';
                    
                if($current_page > 1){
                    echo '<a href="/chms/admin/doctors.php?page='.($current_page - 1).'">Previous</a>';
                }

                // Next page link
                if($current_page < $total_pages){
                    echo '<a href="/chms/admin/doctors.php?page='.($current_page + 1).'">Next</a>';
                }

                if($current_page == $total_pages){
                    echo 'No Page';
                }
                    echo'
                </div>
            </td>
        </tr>';
    
 }
 else{
    echo '
        <tr>
            <td colspan="5" align="center">
                <div id="joy_warning">
                    No Doctors Available!!
                </div>
            </td>
        </tr>';
 }





ob_end_flush();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doctors List</title>
</head>
<body>
     <script>
        function showConfirmation() {
            // You can perform form validation here if needed

            // Show a confirmation dialog
            var userConfirmed = confirm("Are you sure you want to delete this user?");
            
            // If the user confirms, the form will submit; otherwise, it won't.
            return userConfirmed;
        }
    </script>
</body>
</html>

