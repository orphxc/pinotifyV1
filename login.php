<?php
session_start();


if (isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $fetch = "SELECT * FROM accounts WHERE username = '$username'";
    $result = mysqli_query($conn, $fetch);

    if (mysqli_num_rows($result) < 1) {
        echo "<script>alert('No account found.'); window.location='index.php';</script>";
        exit();
    }

    $row = mysqli_fetch_assoc($result);

    if ($password == $row['password']) {
        $_SESSION["USERNAME"] = $row['username'];
        $_SESSION["FNAME"] = $row['firstname'];
        $_SESSION["LNAME"] = $row['lastname'];
        $_SESSION["ROLE"] = $row['role'];

        if ($row['role'] == "admin") {
            header("location:admin/index.php");
            exit();
        }

        header("location:user/index.php");
        exit();
    }

    echo "<script>alert('Incorrect password.'); window.location='index.php';</script>";
    exit();
}

header("location:index.php");
exit();
?>
