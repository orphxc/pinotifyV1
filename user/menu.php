<?php
session_start();
include '../dbconn.php';

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "user") {
    session_destroy();
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cafe Menu</title>
</head>
<body style="font-family:Arial;background:#f5eee8;">

<div style="width:1000px;margin:30px auto;background:white;padding:25px;">
    <h1>Cafe Menu</h1>
    <p>
        <a href="index.php">Home</a> | 
        <a href="cart.php">Cart</a> | 
        <a href="orders.php">My Orders</a> | 
        <a href="logout.php">Logout</a>
    </p>
    
    <table border="1" cellpadding="10" style="width:100%;border-collapse:collapse;">
        <tr>
            <th>Item</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php
        // FIX: Pull items that have stock > 0, ignoring the blank text 'status' column
        $result = mysqli_query($conn, "SELECT * FROM food_cafe WHERE stock > 0 ORDER BY ID DESC");
        
        if (mysqli_num_rows($result) === 0) {
            echo "<tr><td colspan='6' style='text-align:center;color:red;padding:20px;'>No available menu items found in the database.</td></tr>";
        }

        while($row = mysqli_fetch_assoc($result)) {
            $stock = (int)$row['stock'];
            
            // AUTOMATIC STATUS CONDITION LOGIC
            if ($stock >= 6) {
                $display_status = "<span style='color:green;font-weight:bold;'>Available</span>";
            } elseif ($stock >= 1 && $stock <= 5) {
                $display_status = "<span style='color:orange;font-weight:bold;'>Low Stock</span>";
            } else {
                $display_status = "<span style='color:red;font-weight:bold;'>Out of Stock</span>";
            }
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                <td><?php echo htmlspecialchars($row['category'] ?? 'General'); ?></td>
                <td>₱<?php echo number_format($row['price'], 2); ?></td>
                <td><?php echo $stock; ?></td>
                <td><?php echo $display_status; ?></td>
                <td>
                    <form method="post" action="cart.php">
                        <input type="hidden" name="menu_id" value="<?php echo $row['ID']; ?>">
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $stock; ?>" style="width:60px;">
                        <button type="submit" name="add_to_cart">Add to Cart</button>
                    </form>
                </td>
            </tr>
            <?php 
        } 
        ?>
    </table>
</div>

</body>
</html>
