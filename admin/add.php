<?php
include '../dbconn.php';
session_start();

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "admin") {
    session_destroy();
    header("location:../index.php");
    exit();
}

if (isset($_POST['add'])) {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $insert = "INSERT INTO food_cafe (item_name,category,price,stock,status)
               VALUES('$item_name','$category','$price','$stock','$status')";

    if (mysqli_query($conn, $insert)) {
        header("location:index.php");
        exit();
    }
    if ($row['stock'] == 0) {
        echo "Unavailable";
    } elseif ($row['stock'] < 5) {
        echo "Low Stock";
    } else {
        echo "Available";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Menu Item</title></head>
<body style="font-family:Arial; background:#f5eee8;">
<div style="width:500px;margin:40px auto;background:white;padding:25px;">
<h1>Add Menu Item</h1>
<form method="post">
    <p><input style="width:100%;padding:10px;" type="text" name="item_name" placeholder="Item Name" required></p>
    <p><input style="width:100%;padding:10px;" type="text" name="category" placeholder="Category" required></p>
    <p><input style="width:100%;padding:10px;" type="number" step="0.01" min="0" name="price" placeholder="Price" required></p>
    <p><input style="width:100%;padding:10px;" type="number" min="0" name="stock" placeholder="Stock" required></p>
    <!--<p>
        <select style="width:100%;padding:10px;" name="status">
            <option value="Available">Available</option>
            <option value="Unavailable">Unavailable</option>
        </select>
    </p>-->
    <button name="add">Add Item</button>
    <a href="index.php">Cancel</a>
</form>
</div>
</body>
</html>
