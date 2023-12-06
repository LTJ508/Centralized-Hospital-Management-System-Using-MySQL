<?php $servername = "localhost";
$uname = "root";
$password1 = "";
$dbname = "chms";

// Create connection
 $conn = mysqli_connect($servername, $uname, $password1, $dbname);
 // Check connection
  if (!$conn) {
    die("Sorry Try Again " . mysqli_connect_error());
}
?>