<?php

include 'db.php';

$message = "";
$messageType = "danger";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    // Validation
    if ($name === '') {

        $message = "Please enter your full name.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } elseif (strlen($password) < 6) {

        $message = "Password must be at least 6 characters long.";

    } elseif ($password !== $confirm) {

        $message = "Passwords do not match.";

    } else {

        // Secure input
        $name_safe = mysqli_real_escape_string($conn, $name);
        $email_safe = mysqli_real_escape_string($conn, $email);

        // Check existing email
        $check = mysqli_query(
            $conn,
            "SELECT id FROM users WHERE email='$email_safe' LIMIT 1"
        );

        if (!$check) {

            $message = "Database error: " . mysqli_error($conn);

        } elseif (mysqli_num_rows($check) > 0) {

            $message = "An account already exists with this email.";

        } else {

            // Hash password
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $sql = "
                INSERT INTO users (name, email, password)
                VALUES ('$name_safe', '$email_safe', '$hash')
            ";

            if (mysqli_query($conn, $sql)) {

                header("Location: login.php?registered=success");
                exit();

            } else {

                $message = "Registration failed: " . mysqli_error($conn);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>FinIntel AI | Create Account</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #08152b,
                    #102a56,
                    #1d4ed8
                );

            display: flex;
            justify-content: center;
            align-items: center;

            padding: 30px;
        }

        .register-wrapper {

            width: 100%;
            max-width: 1100px;
            min-height: 650px;

            background: white;

            display: flex;

            border-radius: 24px;

            overflow: hidden;

            box-shadow:
                0 25px 60px
                rgba(0,0,0,0.35);
        }

        /* LEFT SIDE */

        .brand-section {

            width: 50%;

            background:
                linear-gradient(
                    145deg,
                    #0b1730,
                    #162b55,
                    #2563eb
                );

            color: white;

            padding: 65px 60px;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-box {

            width: 70px;
            height: 70px;

            background:
                rgba(255,255,255,0.12);

            border:
                1px solid
                rgba(255,255,255,0.20);

            border-radius: 18px;

            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 32px;

            margin-bottom: 25px;
        }

        .brand-title {

            font-size: 42px;
            font-weight: 800;

            margin-bottom: 15px;
        }

        .brand-description {

            font-size: 17px;
            line-height: 1.7;

            color: #d5e2ff;

            margin-bottom: 30px;
        }

        .feature {

            display: flex;
            align-items: center;

            gap: 15px;

            margin-bottom: 18px;
        }

        .feature-icon {

            width: 40px;
            height: 40px;

            background:
                rgba(255,255,255,0.12);

            border-radius: 10px;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* RIGHT SIDE */

        .register-section {

            width: 50%;

            padding: 55px 65px;

            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-title {

            font-size: 34px;
            font-weight: 800;

            color: #0f172a;

            margin-bottom: 8px;
        }

        .register-subtitle {

            color: #64748b;

            margin-bottom: 25px;
        }

        .form-label {

            font-weight: 600;

            color: #334155;
        }

        .form-control {

            height: 48px;

            border-radius: 10px;

            border: 1px solid #cbd5e1;

            padding: 10px 15px;
        }

        .form-control:focus {

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(37,99,235,0.12);
        }

        .register-btn {

            width: 100%;

            border: none;

            border-radius: 10px;

            padding: 13px;

            font-size: 16px;

            font-weight: 700;

            color: white;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1d4ed8
                );

            transition: 0.3s;
        }

        .register-btn:hover {

            transform: translateY(-2px);

            box-shadow:
                0 10px 20px
                rgba(37,99,235,0.30);
        }

        .login-text {

            text-align: center;

            margin-top: 20px;

            color: #64748b;
        }

        .login-text a {

            color: #2563eb;

            font-weight: 700;

            text-decoration: none;
        }

        .login-text a:hover {

            text-decoration: underline;
        }

        .register-footer {

            text-align: center;

            margin-top: 20px;

            font-size: 13px;

            color: #94a3b8;
        }

        /* TABLET */

        @media (max-width: 900px) {

            .register-wrapper {

                flex-direction: column;

                max-width: 600px;
            }

            .brand-section {

                width: 100%;

                padding: 40px;
            }

            .register-section {

                width: 100%;

                padding: 45px 40px;
            }

            .brand-title {

                font-size: 34px;
            }
        }

        /* MOBILE */

        @media (max-width: 500px) {

            body {

                padding: 10px;
            }

            .register-wrapper {

                border-radius: 16px;
            }

            .brand-section {

                padding: 30px 25px;
            }

            .register-section {

                padding: 35px 25px;
            }

            .brand-title {

                font-size: 30px;
            }

            .register-title {

                font-size: 28px;
            }
        }

    </style>

</head>

<body>

<div class="register-wrapper">

    <!-- LEFT BRAND SECTION -->

    <div class="brand-section">

        <div class="logo-box">
            🏦
        </div>

        <h1 class="brand-title">
            FinIntel AI
        </h1>

        <p class="brand-description">
            Create your account and access an AI-powered
            financial intelligence platform for smart loan
            assessment and analytics.
        </p>

        <div class="feature">

            <div class="feature-icon">
                🤖
            </div>

            <div>
                AI-Powered Loan Prediction
            </div>

        </div>

        <div class="feature">

            <div class="feature-icon">
                📊
            </div>

            <div>
                Financial Data Analytics
            </div>

        </div>

        <div class="feature">

            <div class="feature-icon">
                🔒
            </div>

            <div>
                Secure Account Access
            </div>

        </div>

        <div class="feature">

            <div class="feature-icon">
                📈
            </div>

            <div>
                Smart Financial Insights
            </div>

        </div>

    </div>


    <!-- RIGHT REGISTER SECTION -->

    <div class="register-section">

        <h2 class="register-title">
            Create Account
        </h2>

        <p class="register-subtitle">
            Join FinIntel AI and start exploring
            smart financial insights.
        </p>


        <!-- MESSAGE -->

        <?php if ($message !== "") { ?>

            <div class="alert alert-danger">

                <?php
                    echo htmlspecialchars($message);
                ?>

            </div>

        <?php } ?>


        <!-- REGISTER FORM -->

        <form
            method="POST"
            action="register.php"
            autocomplete="off"
        >

            <!-- NAME -->

            <div class="mb-3">

                <label class="form-label">
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    placeholder="Enter your full name"
                    required
                >

            </div>


            <!-- EMAIL -->

            <div class="mb-3">

                <label class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter your email address"
                    required
                >

            </div>


            <!-- PASSWORD -->

            <div class="mb-3">

                <label class="form-label">
                    Create Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Minimum 6 characters"
                    minlength="6"
                    required
                >

            </div>


            <!-- CONFIRM PASSWORD -->

            <div class="mb-4">

                <label class="form-label">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="confirm"
                    class="form-control"
                    placeholder="Confirm your password"
                    minlength="6"
                    required
                >

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                name="register"
                value="1"
                class="register-btn"
            >
                🚀 Create Secure Account
            </button>

        </form>


        <!-- LOGIN -->

        <div class="login-text">

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </div>


        <!-- FOOTER -->

        <div class="register-footer">

            © 2026 FinIntel AI

            <br>

            AI-Powered Financial Intelligence Platform

        </div>

    </div>

</div>

</body>

</html>