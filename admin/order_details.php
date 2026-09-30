<?php
include '../dbconn.php';
session_start();

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "admin") {
    session_destroy();
    header("location:../index.php");
    exit();
}

$id = (int)$_GET['id'];

if (isset($_POST['update_status'])) {
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $allowed = ['Pending','Preparing','Ready','Completed','Cancelled'];

    if (in_array($status, $allowed, true)) {
        $update = "UPDATE orders SET status='$status' WHERE id='$id'";
        mysqli_query($conn, $update);
    }

    header("location:order_details.php?id=$id");
    exit();
}

$order_result = mysqli_query($conn, "SELECT * FROM food_cafe WHERE id='$id'");
$order = mysqli_fetch_assoc($order_result);

if (!$order) die("Order not found.");
?>
<!DOCTYPE html>
<html>
<head><title>Order Details</title></head>
<body style="font-family:Arial;background:#f5eee8;">
<div style="width:800px;margin:30px auto;background:white;padding:25px;">
<h1>Order #<?php echo $order['id']; ?></h1>
<p><b>Customer:</b> <?php echo htmlspecialchars($order['username']); ?></p>
<p><b>Total:</b> ₱<?php echo number_format($order['total'],2); ?></p>
<p><b>Date:</b> <?php echo $order['order_date']; ?></p>

<form method="post">
    <label>Status</label>
    <select name="status">
        <?php foreach(['Pending','Preparing','Ready','Completed','Cancelled'] as $status) { ?>
            <option value="<?php echo $status; ?>" <?php if($order['status']==$status) echo 'selected'; ?>><?php echo $status; ?></option>
        <?php } ?>
    </select>
    <button name="update_status">Update Status</button>
</form>

<h2>Items</h2>
<table border="1" cellpadding="10" style="width:100%;border-collapse:collapse;">
<tr><th>Item</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>
<?php
$items = mysqli_query($conn, "SELECT oi.*, m.item_name FROM order_items oi JOIN menu m ON oi.menu_id=m.ID WHERE oi.order_id='$id'");
while($item = mysqli_fetch_assoc($items)) {
?>
<tr>
<td><?php echo htmlspecialchars($item['item_name']); ?></td>
<td><?php echo $item['quantity']; ?></td>
<td>₱<?php echo number_format($item['price'],2); ?></td>
<td>₱<?php echo number_format($item['price']*$item['quantity'],2); ?></td>
</tr>
<?php } ?>
</table>
<br><a href="orders.php">Back to Orders</a>
</div>
</body>
</html>
