<?php
session_start();

include "dbconn.php"; 

if (isset($_POST['login_btn'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // Raw text entered in the login form
    
    // 1. Safe prepared query matching your actual table structure: user_id, username, password_hash, role
    $stmt = $conn->prepare("SELECT user_id, username, password_hash, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows < 1) {
        echo "<script>alert('No account found.'); window.location='index.php';</script>";
        exit();
    }

    $row = $result->fetch_assoc();

    // 2. FIXED: Verifies raw input text against the cryptographic hash inside your database
    if (password_verify($password, $row['password_hash'])) {
        
        // Pull role securely from database record row
        $db_role = $row['role']; 
        
        $_SESSION["USER_ID"] = $row['user_id'];
        $_SESSION["USERNAME"] = $row['username'];
        $_SESSION["ROLE"] = $db_role; 

        // 3. Automated Server-Side Router
        if ($db_role === 'listener') {
            header("Location: listener/index.php");
        } else {
            header("Location: user/index.php");
        }
        exit();
    }

    echo "<script>alert('Incorrect password.'); window.location='index.php';</script>";
    exit();
}

header("Location: index.php");
exit();
?>
