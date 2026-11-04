<?php

include "dbconn.php";

if (isset($_POST['register'])) {

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password_hash']; // Captures the raw password string

    // 1. FIXED: Properly hash the password using standard PHP encryption
    $secure_password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 2. PRIVATE ROLE CONTROL: Baseline role fallback prevents client-side manipulation
    $role = 'user'; 

    // Check if username already exists using secure Prepared Statements
    $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Username already exists.');</script>";
    } else {

        // 3. Database operation targeting your explicit layout fields
        $insert_stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $username, $email, $secure_password_hash, $role);

        if ($insert_stmt->execute()) {
            echo "<script>
                    alert('Registration successful!');
                    window.location.href='index.php';
                  </script>";
            exit();
        } else {
            echo "Registration failed: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pinotify - Create Account</title>
</head>
<body style="font-family: Arial, sans-serif; background: linear-gradient(to bottom, #000000, #ff69b4) fixed; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh;">

    <!-- Secure Registration Card Wrapper -->
    <div style="width: 100%; max-width: 400px; background: rgba(20, 20, 20, 0.65); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); border: 1px solid rgba(255, 105, 180, 0.35); border-radius: 24px; box-shadow: 0 0 40px rgba(255, 105, 180, 0.25); padding: 30px; box-sizing: border-box; color: #ffffff;">
        
        <h2 style="text-align: center; margin-top: 0; margin-bottom: 5px; font-size: 28px; font-weight: bold;">Create Account</h2>
        <p style="text-align: center; margin-top: 0; margin-bottom: 25px; color: rgba(255, 255, 255, 0.6); font-size: 14px;">Join the Pinotify Community</p>

        <form method="POST">
            <!-- Username Input -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: rgba(255, 255, 255, 0.7); font-size: 14px;">Username:</label>
                <input type="text" name="username" required style="width: 100%; padding: 10px; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 8px; background: rgba(255, 255, 255, 0.08); color: #ffffff; box-sizing: border-box; font-size: 14px; outline: none;">
            </div>

            <!-- Email Address Input -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: rgba(255, 255, 255, 0.7); font-size: 14px;">Email Address:</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 8px; background: rgba(255, 255, 255, 0.08); color: #ffffff; box-sizing: border-box; font-size: 14px; outline: none;">
            </div>

            <!-- Password Input -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 5px; color: rgba(255, 255, 255, 0.7); font-size: 14px;">Password:</label>
                <input type="password" name="password_hash" required style="width: 100%; padding: 10px; border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 8px; background: rgba(255, 255, 255, 0.08); color: #ffffff; box-sizing: border-box; font-size: 14px; outline: none;">
            </div>

            <!-- Submit Button Trigger -->
            <button type="submit" name="register" style="width: 100%; padding: 12px; border: none; border-radius: 12px; background: linear-gradient(135deg, #ff69b4, #ff1493); color: #ffffff; font-weight: bold; font-size: 16px; cursor: pointer; transition: transform 0.15s, box-shadow 0.15s;">
                Register
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 14px;">
            <a href="index.php" style="color: #ff9ad0; text-decoration: none;">Already have an account? Login</a>
        </div>
    </div>

</body>
</html>
