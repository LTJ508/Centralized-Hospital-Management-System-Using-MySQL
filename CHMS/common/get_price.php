<?php
include("connection.php");

$selectedTest = $_POST["test_name"];

// Retrieve the price of the selected test from the database
$sql = "SELECT Price FROM test WHERE ID = $selectedTest";
$result = mysqli_query($conn, $sql);
$found_price = mysqli_fetch_array($result);
$Price = $found_price['Price'];

echo $Price;

// Close the database connection
$conn->close();
?>
