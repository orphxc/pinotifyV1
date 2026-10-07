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


    // Check if username already exists
    $check = "SELECT * FROM users WHERE username = '$username'";
    $check_result = mysqli_query($conn, $check);

    if (mysqli_num_rows($check_result) > 0) {
        echo "<script>
                alert('Username already exists.');
              </script>";
    } else {

        // Insert new user matching your exact database columns
        $insert = "INSERT INTO users (username, email, password_hash)
                   VALUES ('$username', '$email', '$password_hash')";

        $result = mysqli_query($conn, $insert);

        if ($result) {
            echo "<script>
                    alert('Registration successful!');
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
    <title>CafeEase Registration</title>
</head>
<body>

<h1>Create Account</h1>

<form method="POST">

    <label>Username:</label>
    <br>
    <input type="text" name="username" required>
    <br><br>

    <label>Email Address:</label>
    <br>
    <input type="email" name="email" required>
    <br><br>

    <label>Password:</label>
    <br>
    <input type="password" name="password_hash" required>
    <br><br>

    <button type="submit" name="register">
        Register
    </button>

</form>

<br>
<a href="index.php">Already have an account? Login</a>

</body>
</html>
