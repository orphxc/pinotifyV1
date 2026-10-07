<?php
$conn = mysqli_connect("localhost", "root", "", "pinotify");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
