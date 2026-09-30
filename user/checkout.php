<?php
session_start();
include '../dbconn.php';

if (!isset($_SESSION["USERNAME"]) || $_SESSION["ROLE"] != "user") {
    session_destroy();
    header("location:../index.php");
    exit();
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit();
}

$user_id = isset($_SESSION['USER_ID']) ? (int)$_SESSION['USER_ID'] : 1; 

$total = 0;
$valid_items = [];

foreach ($_SESSION['cart'] as $menu_id => $quantity) {
    $menu_id = (int)$menu_id;
    $quantity = (int)$quantity;

    $stmt = mysqli_prepare($conn, "SELECT * FROM food_cafe WHERE ID = ?");
    mysqli_stmt_bind_param($stmt, "i", $menu_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($result);

    // FIX: Removed strict text status logic check. Validates based purely on stock level numbers now.
    if (!$item || $quantity < 1 || $quantity > (int)$item['stock']) {
        die("An item in your cart is unavailable or does not have enough stock. <a href='cart.php'>Back to cart</a>");
    }

    $subtotal = (int)$item['price'] * $quantity;
    $total += $subtotal;

    $valid_items[] = [
        'id' => $menu_id,
        'price' => (int)$item['price'],
        'quantity' => $quantity
    ];
}

$order_query = "INSERT INTO orders (user_id, order_date, total_amount, status) VALUES (?, NOW(), ?, 'Pending')";
$stmt = mysqli_prepare($conn, $order_query);
mysqli_stmt_bind_param($stmt, "ii", $user_id, $total);

if (mysqli_stmt_execute($stmt)) {
    $order_id = mysqli_insert_id($conn);

    foreach ($valid_items as $item) {
        $item_query = "INSERT INTO order_items (order_id, menu_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt_item = mysqli_prepare($conn, $item_query);
        mysqli_stmt_bind_param($stmt_item, "iiii", $order_id, $item['id'], $item['quantity'], $item['price']);
        mysqli_stmt_execute($stmt_item);

        $update_stock = "UPDATE food_cafe SET stock = stock - ? WHERE ID = ?";
        $stmt_stock = mysqli_prepare($conn, $update_stock);
        mysqli_stmt_bind_param($stmt_stock, "ii", $item['quantity'], $item['id']);
        mysqli_stmt_execute($stmt_stock);
    }

    unset($_SESSION['cart']);

    header("Location: orders.php?success=1");
    exit();
} else {
    echo "Error processing your order. Please try again.";
}
?>
