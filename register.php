<?php

include "dbconn.php";

if (isset($_POST['register'])) {

    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $password_hash = mysqli_real_escape_string(
        $conn,
        $_POST['password_hash']
    );

    // Capture the user's manual selection from the dropdown
    $selected_role = mysqli_real_escape_string($conn, $_POST['role']);

    // EMAIL-BASED ROLE LOGIC WITH MANUAL OVERRIDE SAFEGUARD
    $lowercase_email = strtolower($email);

    if (str_ends_with($lowercase_email, '@listen.com')) {
        $role = 'listener';
    } elseif (str_ends_with($lowercase_email, '@artist.com')) {
        $role = 'user'; 
    } else {
        // If they use a standard email (like gmail), fall back to their dropdown choice
        $role = $selected_role; 
    }

    // Check if username already exists
    $check = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>alert('Username already exists.');</script>";
    } else {

        // Insert new user tracking the programmatic role assignment state
        $insert = "INSERT INTO users (username, email, password_hash, role)
                   VALUES ('$username', '$email', '$password_hash', '$role')";

        $result = mysqli_query($conn, $insert);

        if ($result) {
            $display_role = ($role === 'user') ? 'Artist' : 'Listener';
            echo "<script>
                    alert('Registration successful as an " . $display_role . "!');
                    window.location.href='index.php';
                  </script>";
        } else {
            echo "Registration failed: " . mysqli_error($conn);
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pinotify - Create Account</title>
</head>
<body style="font-family: Arial, sans-serif; background: linear-gradient(to bottom, #000000, #ff69b4) fixed; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; min-height: 100vh;">

    <!-- Registration Card -->
    <div style="width: 100%; max-width: 400px; background: rgba(20, 20, 20, 0.85); border: 1px solid rgba(255, 105, 180, 0.4); border-radius: 20px; box-shadow: 0 0 30px rgba(255, 105, 180, 0.25); padding: 30px; box-sizing: border-box; color: #ffffff;">
        
        <h2 style="text-align: center; margin-top: 0; margin-bottom: 5px; font-size: 28px; font-weight: bold;">Create Account</h2>
        <p style="text-align: center; margin-top: 0; margin-bottom: 25px; color: rgba(255, 255, 255, 0.6); font-size: 14px;">Join the Pinotify Community</p>

        <form method="POST">
            <!-- Username -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: rgba(255, 255, 255, 0.7); font-size: 14px;">Username:</label>
                <input type="text" name="username" required style="width: 100%; padding: 10px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px; background: rgba(255, 255, 255, 0.08); color: #ffffff; box-sizing: border-box; font-size: 14px;">
            </div>

            <!-- Account Type Selection Dropdown -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: rgba(255, 255, 255, 0.7); font-size: 14px;">I want to register as an:</label>
                <select name="role" required style="width: 100%; padding: 10px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px; background: rgba(30, 30, 30, 0.95); color: #ffffff; box-sizing: border-box; font-size: 14px; cursor: pointer;">
                    <option value="listener">Listener (Stream & Browse Music)</option>
                    <option value="user">Artist / Creator (Upload Tracks)</option>
                </select>
            </div>

            <!-- Email Address -->
            <div style="margin-bottom: 15px;">
                <label style="display: block; margin-bottom: 5px; color: rgba(255, 255, 255, 0.7); font-size: 14px;">Email Address:</label>
                <input type="email" name="email" required placeholder="name@artist.com or name@listen.com" style="width: 100%; padding: 10px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px; background: rgba(255, 255, 255, 0.08); color: #ffffff; box-sizing: border-box; font-size: 14px;">
            </div>

            <!-- Password -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; margin-bottom: 5px; color: rgba(255, 255, 255, 0.7); font-size: 14px;">Password:</label>
                <input type="password" name="password_hash" required style="width: 100%; padding: 10px; border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 8px; background: rgba(255, 255, 255, 0.08); color: #ffffff; box-sizing: border-box; font-size: 14px;">
            </div>

            <!-- Submit Button -->
            <button type="submit" name="register" style="width: 100%; padding: 12px; border: none; border-radius: 10px; background: linear-gradient(135deg, #ff69b4, #ff1493); color: #ffffff; font-weight: bold; font-size: 16px; cursor: pointer; transition: opacity 0.2s;">
                Register
            </button>
        </form>

        <div style="text-align: center; margin-top: 20px; font-size: 14px;">
            <a href="index.php" style="color: #ff9ad0; text-decoration: none;">Already have an account? Login</a>
        </div>
    </div>

</body>
</html>
