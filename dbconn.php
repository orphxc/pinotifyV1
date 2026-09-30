<?php
$conn = mysqli_connect("localhost", "root", "", "cafe");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
