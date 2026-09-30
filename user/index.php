<?php
session_start();
if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "user") {
    session_destroy();
    header("location:../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>User Home - CafeEase</title></head>
<body style="font-family:Arial;background:#f5eee8;">
<div style="width:800px;margin:30px auto;background:white;padding:25px;">
<h1>Welcome <?php echo htmlspecialchars($_SESSION['FNAME']); ?>!</h1>
<hr>
<h3><a href="index.php">Homepage</a></h3>
<h3><a href="menu.php">Cafe Menu</a></h3>
<h3><a href="cart.php">My Cart</a></h3>
<h3><a href="orders.php">My Orders</a></h3>
<h3><a href="logout.php">Logout</a></h3>
</div>
</body>
</html>
