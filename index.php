<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pinotify</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #000000, #ff69b4) fixed;
        }

        /* Card */
        .login-card {
            max-width: 430px;
            background: rgba(20, 20, 20, 0.65);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 105, 180, 0.35);
            border-radius: 24px;
            box-shadow: 0 0 40px rgba(255, 105, 180, 0.25);
            color: #fff;
        }

        /* Round logo icon */
        .logo-circle {
            width: 70px;
            height: 70px;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #fff;
            background: linear-gradient(135deg, #ff69b4, #ff1493);
            border-radius: 50%;
            box-shadow: 0 0 20px rgba(255, 20, 147, 0.6);
        }

        /* Inputs */
        .login-card .input-group-text {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-right: 0;
            color: #ff69b4;
        }
        .login-card .form-control {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-left: 0;
            color: #fff;
        }
        .login-card .form-control::placeholder { color: rgba(255, 255, 255, 0.5); }
        .login-card .form-control:focus {
            box-shadow: none;
            border-color: #ff69b4;
        }

        /* Button */
        .btn-pink {
            background: linear-gradient(135deg, #ff69b4, #ff1493);
            border: 0;
            color: #fff;
            font-weight: 600;
            border-radius: 12px;
            transition: transform .15s, box-shadow .15s;
        }
        .btn-pink:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(255, 20, 147, 0.5);
        }

        .login-card a { color: #ff9ad0; text-decoration: none; }
        .login-card a:hover { color: #fff; text-decoration: underline; }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center p-3">

    <div class="login-card card w-100 p-2">
        <div class="card-body p-4">

            <div class="logo-circle"><i class="bi bi-music-note-beamed"></i></div>
            <h2 class="text-center fw-bold mb-1">Pinotify</h2>
            <p class="text-center mb-4" style="color: rgba(255,255,255,.6);">Homegrown Music</p>

            <form method="post" action="login.php">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                    <input type="text" name="username" placeholder="Username" class="form-control" required>
                </div>
                <div class="input-group mb-4">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                </div>

                <button type="submit" name="login_btn" class="btn btn-pink btn-lg w-100">Login</button>
            </form>

            <p class="text-center mt-4 mb-0">
                No account yet? <a href="register.php">Register</a>
            </p>
        </div>
    </div>

</body>
</html>