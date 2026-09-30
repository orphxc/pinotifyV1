<?php
session_start();
include '../dbconn.php';

if (!isset($_SESSION["USERNAME"]) || !isset($_SESSION["ROLE"]) || $_SESSION["ROLE"] != "user") {
    session_destroy();
    header("Location: ../index.php");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['add_to_cart'])) {
    $menu_id = isset($_POST['menu_id']) ? (int)$_POST['menu_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    if ($quantity < 1) {
        $quantity = 1;
    }

    if ($menu_id > 0) {
        $stmt = mysqli_prepare($conn, "SELECT stock FROM food_cafe WHERE ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $menu_id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);
        $item = mysqli_fetch_assoc($result);

        if ($item && (int)$item['stock'] > 0) {
            $stock = (int)$item['stock'];
            $old_quantity = $_SESSION['cart'][$menu_id] ?? 0;
            $new_quantity = $old_quantity + $quantity;

            if ($new_quantity > $stock) {
                $new_quantity = $stock;
            }

            $_SESSION['cart'][$menu_id] = $new_quantity;
        }
    }

    header("Location: cart.php");
    exit();
}

if (isset($_GET['remove'])) {
    $menu_id = (int)$_GET['remove'];

    if (isset($_SESSION['cart'][$menu_id])) {
        unset($_SESSION['cart'][$menu_id]);
    }

    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Cart</title>
</head>
<body style="font-family:Arial;background:#f5eee8;">

<div style="width:900px;margin:30px auto;background:white;padding:25px;">

    <h1>My Cart</h1>

    <p>
        <a href="menu.php">Menu</a> |
        <a href="orders.php">My Orders</a> |
        <a href="logout.php">Logout</a>
    </p>

    <hr>

    <?php
    $total = 0;
    $has_items = false;

    if (!empty($_SESSION['cart'])) {

        foreach ($_SESSION['cart'] as $menu_id => $quantity) {

            $menu_id = (int)$menu_id;
            $quantity = (int)$quantity;

            $stmt = mysqli_prepare(
                $conn,
                "SELECT ID as menu_id, item_name, price, stock 
                 FROM food_cafe 
                 WHERE ID = ?"
            );

            mysqli_stmt_bind_param($stmt, "i", $menu_id);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);
            $item = mysqli_fetch_assoc($result);

            if (!$item) {
                unset($_SESSION['cart'][$menu_id]);
                continue;
            }

            // FIX: Removed the text status validation check here as well
            if ((int)$item['stock'] <= 0) {
                unset($_SESSION['cart'][$menu_id]);
                continue;
            }

            if ($quantity > (int)$item['stock']) {
                $quantity = (int)$item['stock'];
                $_SESSION['cart'][$menu_id] = $quantity;
            }

            if ($quantity < 1) {
                unset($_SESSION['cart'][$menu_id]);
                continue;
            }

            $has_items = true;
            $subtotal = $item['price'] * $quantity;
            $total += $subtotal;
            ?>

            <div style="border:1px solid #ddd;padding:15px;margin-bottom:15px;border-radius:8px;">
                <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                <p>Price: ₱<?php echo number_format($item['price'], 2); ?></p>
                <p>Quantity: <?php echo $quantity; ?></p>
                <p>Subtotal: <b>₱<?php echo number_format($subtotal, 2); ?></b></p>
                <a href="cart.php?remove=<?php echo $menu_id; ?>">Remove</a>
            </div>

            <?php
        }
    }

    if (!$has_items) {
        ?>
        <p>Your cart is empty.</p>
        <a href="menu.php">Continue Shopping</a>
        <?php
    } else {
        ?>
        <hr>
        <h2>Total: ₱<?php echo number_format($total, 2); ?></h2>

        <form method="post" action="checkout.php">
            <button type="submit" name="checkout">Place Order</button>
        </form>
        <?php
    }
    ?>

</div>

</body>
</html>
