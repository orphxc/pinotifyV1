<?php
include '../dbconn.php';
session_start();

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "admin") {
    session_destroy();
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - CafeEase</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f5eee8; }
        .box { width:95%; max-width:1100px; margin:30px auto; background:white; padding:25px; }
        table { width:100%; border-collapse:collapse; }
        th,td { border:1px solid #ccc; padding:10px; text-align:left; }
        th { background:#ead8c5; }
        a, button { margin-right:8px; }
    </style>
</head>
<body>
<div class="box">
    <h1>Welcome Admin <?php echo htmlspecialchars($_SESSION['FNAME']); ?></h1>
    <p><a href="index.php">Menu</a> | <a href="orders.php">Orders</a> | <a href="logout.php">Logout</a></p>
    <hr>

    <h2>Cafe Menu</h2>
    <p><a href="add.php">+ Add New Menu Item</a></p>

    <table>
        <tr>
            <th>ID</th><th>Item Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th>
        </tr>
        <?php
        $fetch = "SELECT * FROM food_cafe ORDER BY ID DESC";
        $result = mysqli_query($conn, $fetch);
        while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo $row['ID']; ?></td>
            <td><?php echo htmlspecialchars($row['item_name']); ?></td>
            <td><?php echo htmlspecialchars($row['category']); ?></td>
            <td>₱<?php echo number_format($row['price'], 2); ?></td>
            <td><?php echo $row['stock']; ?></td>
            <td>
                <?php

                if ($row['stock'] == 0) {

                    echo "Unavailable";

                } elseif ($row['stock'] <= 5) {

                    echo "Low Stock";

                } else {

                    echo "Available";

                }

                ?>
            </td>
            <td>
                <a href="update.php?id=<?php echo $row['ID']; ?>">Update</a>
                <a href="delete.php?id=<?php echo $row['ID']; ?>" onclick="return confirm('Delete this menu item?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
