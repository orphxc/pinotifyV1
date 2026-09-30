<?php
include '../dbconn.php';
session_start();

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "admin") {
    session_destroy();
    header("location:../index.php");
    exit();
}

$id = (int)$_GET['id'];
$fetch = "SELECT * FROM food_cafe WHERE ID = '$id'";
$result = mysqli_query($conn, $fetch);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Menu item not found.");
}

if (isset($_POST['update'])) {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $update = "UPDATE food_cafe SET
               item_name='$item_name',
               category='$category',
               price='$price',
               stock='$stock',
               status='$status'
               WHERE ID='$id'";

    if (mysqli_query($conn, $update)) {
        header("location:index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Update Menu Item</title></head>
<body style="font-family:Arial; background:#f5eee8;">
<div style="width:500px;margin:40px auto;background:white;padding:25px;">
<h1>Update Menu Item</h1>
<form method="post">
    <p><input style="width:100%;padding:10px;" type="text" name="item_name" value="<?php echo htmlspecialchars($row['item_name']); ?>" required></p>
    <p><input style="width:100%;padding:10px;" type="text" name="category" value="<?php echo htmlspecialchars($row['category']); ?>" required></p>
    <p><input style="width:100%;padding:10px;" type="number" step="0.01" min="0" name="price" value="<?php echo $row['price']; ?>" required></p>
    <p><input style="width:100%;padding:10px;" type="number" min="0" name="stock" value="<?php echo $row['stock']; ?>" required></p>
    <button name="update">Update Item</button>
    <a href="index.php">Cancel</a>
</form>
</div>
</body>
</html>
