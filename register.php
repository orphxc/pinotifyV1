<?php

include "dbconn.php";

if (isset($_POST['register'])) {

    $fname = mysqli_real_escape_string(
        $conn,
        $_POST['fname']
    );

    $lname = mysqli_real_escape_string(
        $conn,
        $_POST['lname']
    );

    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );

    $password = mysqli_real_escape_string(
        $conn,
        $_POST['password']
    );


    // Check if username already exists

    $check = "SELECT * FROM accounts
              WHERE username = '$username'";

    $check_result = mysqli_query($conn, $check);


    if (mysqli_num_rows($check_result) > 0) {

        echo "<script>
                alert('Username already exists.');
              </script>";

    } else {

        // Insert new user

        $insert = "INSERT INTO accounts
                   (username, password, firstname, lastname, role)
                   VALUES
                   ('$username',
                    '$password',
                    '$fname',
                    '$lname',
                    'user')";

        $result = mysqli_query($conn, $insert);


        if ($result) {

            echo "<script>
                    alert('Registration successful!');
                    window.location.href='index.php';
                  </script>";

        } else {

            echo "Registration failed: "
                 . mysqli_error($conn);
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

    <label>First Name:</label>
    <br>

    <input
        type="text"
        name="fname"
        required
    >

    <br><br>


    <label>Last Name:</label>
    <br>

    <input
        type="text"
        name="lname"
        required
    >

    <br><br>


    <label>Username:</label>
    <br>

    <input
        type="text"
        name="username"
        required
    >

    <br><br>


    <label>Password:</label>
    <br>

    <input
        type="password"
        name="password"
        required
    >

    <br><br>


    <button type="submit" name="register">
        Register
    </button>

</form>

<br>

<a href="index.php">
    Already have an account? Login
</a>

</body>

</html>