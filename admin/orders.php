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
    <title>Customer Orders</title>
    <style>
        body{font-family:Arial;background:#f5eee8}.box{width:95%;max-width:1100px;margin:30px auto;background:white;padding:25px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ccc;padding:10px}th{background:#ead8c5}
    </style>
</head>
<body>
<div class="box">
<h1>Customer Orders</h1>
<p><a href="index.php">Menu</a> | <a href="orders.php">Orders</a> | <a href="logout.php">Logout</a></p>
<table>
<tr><th>Order ID</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th><th>Action</th></tr>
<?php
$fetch = "SELECT * FROM orders ORDER BY order_id DESC";
$result = mysqli_query($conn, $fetch);
while($row = mysqli_fetch_assoc($result)) {
?>
<tr>
<td>#<?php echo $row['order_id']; ?></td>
<td><?php echo htmlspecialchars($row['user_id']); ?></td>
<td>₱<?php echo number_format($row['total_amount'],2); ?></td>
<td><?php echo htmlspecialchars($row['status']); ?></td>
<td><?php echo $row['order_date']; ?></td>
<td><a href="order_details.php?id=<?php echo $row['id']; ?>">View / Update</a></td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>
