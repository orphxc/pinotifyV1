<?php
include "../dbconn.php";
session_start();

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "admin") {
    session_destroy();
    header("location:../index.php");
    exit();
}

$id = (int)$_GET['id'];

$delete = "DELETE FROM food_cafe WHERE ID = '$id'";
mysqli_query($conn, $delete);

header("location:index.php");
exit();
?>
