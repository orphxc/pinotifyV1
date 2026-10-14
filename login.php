<?php
session_start();

include "dbconn.php"; 

if (isset($_POST['login_btn'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // We fetch the account profile records based on the typed username matching
    $fetch = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $fetch);

    if (mysqli_num_rows($result) < 1) {
        echo "<script>alert('No account found.'); window.location='index.php';</script>";
        exit();
    }

    $row = mysqli_fetch_assoc($result);

    // Verify credential strings against row logs
    if ($password == $row['password_hash']) {
        
        // FIX: Capture the role stored directly in the database row record!
        $db_role = $row['role']; 
        
        $_SESSION["USERNAME"] = $row['username'];
        $_SESSION["ROLE"] = $db_role; // This dynamically assigns 'listener' or 'user'

        // DYNAMIC DIRECTION SYSTEM BASED ON DATABASE TRUTH
        if ($db_role === 'listener') {
            header("location:listener/index.php");
        } else {
            header("location:user/index.php");
        }
        exit();
    }

    echo "<script>alert('Incorrect password.'); window.location='index.php';</script>";
    exit();
}

header("location:index.php");
exit();
?>
