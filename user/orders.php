<?php
include '../dbconn.php';
session_start();
if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "user") {
    session_destroy();
    header("location:../index.php");
    exit();
}

$username = mysqli_real_escape_string($conn, $_SESSION['USERNAME']);
?>
<!DOCTYPE html>
<html>
<head><title>My Orders</title></head>
<body style="font-family:Arial;background:#f5eee8;">
<div style="width:950px;margin:30px auto;background:white;padding:25px;">
<h1>My Orders</h1>
<p><a href="index.php">Home</a> | <a href="menu.php">Menu</a> | <a href="cart.php">Cart</a> | <a href="logout.php">Logout</a></p>
<?php if(isset($_GET['success'])) { ?><p style="color:green;"><b>Order placed successfully!</b></p><?php } ?>
<table border="1" cellpadding="10" style="width:100%;border-collapse:collapse;">
<tr><th>Order ID</th><th>Total</th><th>Status</th><th>Date</th><th>Items</th></tr>
<?php
$query = "SELECT * FROM orders WHERE user_id = (SELECT user_id FROM accounts WHERE username='$username') ORDER BY order_id DESC";
$result = mysqli_query($conn, $query);

while($order = mysqli_fetch_assoc($result)) {
?>
<tr>
<td>#<?php echo $order['order_id']; ?></td>
<td>₱<?php echo number_format($order['total_amount'], 2); ?></td>
<td><?php echo htmlspecialchars($order['status']); ?></td>
<td><?php echo $order['order_date']; ?></td>
<td>
<?php
$order_id = $order['order_id'];
$items = mysqli_query($conn, "SELECT oi.*, m.item_name FROM order_items oi JOIN menu m ON oi.menu_id=m.ID WHERE oi.order_id='$order_id'");
while($item = mysqli_fetch_assoc($items)) {
    echo htmlspecialchars($item['item_name']) . ' × ' . $item['quantity'] . '<br>';
}
?>
</td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>
