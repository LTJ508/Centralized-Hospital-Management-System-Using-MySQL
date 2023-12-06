<?php
ob_start();
include("../common/header-content.php");
include("../common/functions.php");
include("restrict-access.php");


$User_ID = $_SESSION['User_ID'];

if($_GET['Status'] == 1){
    $sql = "SELECT u.ID, u.Role, p.Fname as PFname, p.Lname as PLname, d.Title, d.Fname as DFname, d.Lname as DLname, h.Name as HName, dc.Name as DCName
            FROM user_details u
            LEFT OUTER JOIN patient p ON(u.ID = p.ID)
            LEFT OUTER JOIN doctor d ON(u.ID = d.ID)
            LEFT OUTER JOIN hospital h ON(u.ID = h.ID)
            LEFT OUTER JOIN diagnostic_center dc ON(u.ID = dc.ID)
            WHERE u.Status = TRUE AND u.Role <> 'Admin'
            ORDER BY u.ID";
    $Status = "Active";
}
else if($_GET['Status'] == 0){
    $sql = "SELECT u.ID, u.Role, p.Fname as PFname, p.Lname as PLname, d.Title, d.Fname as DFname, d.Lname as DLname, h.Name as HName, dc.Name as DCName
            FROM user_details u
            LEFT OUTER JOIN patient p ON(u.ID = p.ID)
            LEFT OUTER JOIN doctor d ON(u.ID = d.ID)
            LEFT OUTER JOIN hospital h ON(u.ID = h.ID)
            LEFT OUTER JOIN diagnostic_center dc ON(u.ID = dc.ID)
            WHERE u.Status = FALSE AND u.Role <> 'Admin'
            ORDER BY u.ID";
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
    $sql = "SELECT u.ID, u.Role, p.Fname as PFname, p.Lname as PLname, d.Title, d.Fname as DFname, d.Lname as DLname, h.Name as HName, dc.Name as DCName
            FROM user_details u
            LEFT OUTER JOIN patient p ON(u.ID = p.ID)
            LEFT OUTER JOIN doctor d ON(u.ID = d.ID)
            LEFT OUTER JOIN hospital h ON(u.ID = h.ID)
            LEFT OUTER JOIN diagnostic_center dc ON(u.ID = dc.ID)
            WHERE u.Status = TRUE AND u.Role <> 'Admin'
            ORDER BY u.ID
            LIMIT $results_per_page OFFSET $offset";
}
else if($_GET['Status'] == 0){
    $sql = "SELECT u.ID, u.Role, p.Fname as PFname, p.Lname as PLname, d.Title, d.Fname as DFname, d.Lname as DLname, h.Name as HName, dc.Name as DCName
            FROM user_details u
            LEFT OUTER JOIN patient p ON(u.ID = p.ID)
            LEFT OUTER JOIN doctor d ON(u.ID = d.ID)
            LEFT OUTER JOIN hospital h ON(u.ID = h.ID)
            LEFT OUTER JOIN diagnostic_center dc ON(u.ID = dc.ID)
            WHERE u.Status = FALSE AND u.Role <> 'Admin'
            ORDER BY u.ID
            LIMIT $results_per_page OFFSET $offset";
}

// Execute the modified query
$result = mysqli_query($conn, $sql);



echo '
<table border="1" align="center">
    <caption>
        <div id="joy_congratulation" align="center">
            All '.$Status.' User List (10 Per Page)
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
                Role
            </div>
        </th>
        <th>
            <div id="joy_congratulation" align="center">
                Name
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
                        '.$row['Role'].'
                    </div>
                </td>
                <td>
                    <div id="joy_congratulation" align="center">';


if($row['PFname'] != ""){
    echo ''.$row['PFname'].' '.$row['PLname'].'';
}
else if($row['DFname'] != ""){
    echo ''.$row['Title'].' Dr. '.$row['DFname'].' '.$row['DLname'].'';
}
else if($row['HName'] != ""){
    echo ''.$row['HName'].'';
}
else if($row['DCName'] != ""){
    echo ''.$row['DCName'].'';
}

                 
                    echo '
                    </div>
                </td>
                
                <td>
                    <div id="joy_congratulation" align="center">
                        <form action="/chms/admin/user-view.php" target="_blank" method="POST" style="margin: 0px; padding: 0px;">
                            <input type="hidden" id="ID" name="ID" value="'.$row['ID'].'">
                            <input type="hidden" id="Role" name="Role" value="'.$row['Role'].'">
                            <input type="submit" name="view" value="View Details">
                        </form>
                    </div>
                </td>
                <td>
                    <div id="joy_warning" align="center">
                        <form action="/chms/admin/user-view.php" method="POST" onsubmit="return showConfirmation()" style="margin: 0px; padding: 0px;">
                            <input type="hidden" id="ID" name="ID" value="'.$row['ID'].'">
                            <input type="hidden" id="Role" name="Role" value="'.$row['Role'].'">
                            <input type="submit" name="delete" value="Delete User">
                        </form>
                    </div>
                </td>
            </tr>
            ';
    }
    echo '
        <tr>
            <td colspan="6" align="center">
                <div id="joy_warning">';
                    
                if($current_page > 1){
                    echo '<a href="/chms/admin/users.php?Status='.$_GET['Status'].'&page='.($current_page - 1).'"> Previous </a>';
                }

                // Next page link
                if($current_page < $total_pages){
                    echo '<a href="/chms/admin/users.php?Status='.$_GET['Status'].'&page='.($current_page + 1).'"> Next </a>';
                }

                if($current_page == $total_pages){
                    echo ' No Page ';
                }
                    echo'
                </div>
            </td>
        </tr>';
    
 }
 else{
    echo '
        <tr>
            <td colspan="6" align="center">
                <div id="joy_warning">
                    No Users Available!!
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
    <title>All Users List</title>
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