<?php

session_start();
include 'db.php';

$message = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $email_safe = mysqli_real_escape_string($conn, $email);

    $query = "SELECT * FROM users WHERE email='$email_safe' LIMIT 1";

    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {

            $_SESSION['user'] = $row['name'];
            $_SESSION['id'] = $row['id'];

            header("Location: dashboard.php");
            exit();

        } else {

            $message = "Incorrect password. Please try again.";

        }

    } else {

        $message = "No account found with this email address.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>FinIntel AI | Login</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<style>

/* =========================
   GLOBAL
========================= */

* {
    box-sizing: border-box;
}

body {

    margin: 0;
    min-height: 100vh;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    background:
        linear-gradient(
            135deg,
            #08152b,
            #102a56,
            #1d4ed8
        );

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 30px;
}


/* =========================
   MAIN LOGIN BOX
========================= */

.login-wrapper {

    width: 100%;
    max-width: 1100px;
    min-height: 620px;

    display: flex;

    background: #ffffff;

    border-radius: 24px;

    overflow: hidden;

    box-shadow:
        0 25px 60px
        rgba(0, 0, 0, 0.35);

}


/* =========================
   LEFT BRAND SECTION
========================= */

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

    padding: 70px 60px;

    display: flex;

    flex-direction: column;

    justify-content: center;

}


.logo-box {

    width: 70px;
    height: 70px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 32px;

    background:
        rgba(255,255,255,0.12);

    border:
        1px solid
        rgba(255,255,255,0.20);

    border-radius: 18px;

    margin-bottom: 30px;

}


.brand-title {

    font-size: 42px;

    font-weight: 800;

    margin-bottom: 15px;

}


.brand-description {

    color: #d5e2ff;

    font-size: 17px;

    line-height: 1.7;

    margin-bottom: 35px;

}


/* FEATURES */

.feature {

    display: flex;

    align-items: center;

    margin-bottom: 20px;

    font-size: 16px;

}


.feature-icon {

    width: 40px;
    height: 40px;

    border-radius: 10px;

    display: flex;

    align-items: center;

    justify-content: center;

    margin-right: 15px;

    background:
        rgba(255,255,255,0.12);

}


/* =========================
   RIGHT LOGIN SECTION
========================= */

.login-section {

    width: 50%;

    padding: 70px 65px;

    display: flex;

    flex-direction: column;

    justify-content: center;

}


.login-title {

    font-size: 34px;

    font-weight: 800;

    color: #0f172a;

    margin-bottom: 8px;

}


.login-subtitle {

    color: #64748b;

    font-size: 16px;

    margin-bottom: 35px;

}


/* =========================
   FORM
========================= */

.form-label {

    font-weight: 600;

    color: #334155;

    margin-bottom: 8px;

}


.form-control {

    height: 52px;

    border-radius: 10px;

    border: 1px solid #cbd5e1;

    padding: 10px 15px;

}


.form-control:focus {

    border-color: #2563eb;

    box-shadow:
        0 0 0 3px
        rgba(37, 99, 235, 0.12);

}


/* =========================
   BUTTON
========================= */

.login-btn {

    width: 100%;

    border: none;

    border-radius: 10px;

    padding: 14px;

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


.login-btn:hover {

    transform:
        translateY(-2px);

    box-shadow:
        0 10px 20px
        rgba(37, 99, 235, 0.30);

}


/* =========================
   REGISTER
========================= */

.register-text {

    text-align: center;

    margin-top: 25px;

    color: #64748b;

}


.register-text a {

    color: #2563eb;

    font-weight: 700;

    text-decoration: none;

}


.register-text a:hover {

    text-decoration: underline;

}


/* =========================
   FOOTER
========================= */

.login-footer {

    text-align: center;

    margin-top: 35px;

    font-size: 13px;

    color: #94a3b8;

}


/* =========================
   TABLET
========================= */

@media (max-width: 900px) {

    body {

        padding: 20px;

    }

    .login-wrapper {

        max-width: 600px;

        flex-direction: column;

    }

    .brand-section {

        width: 100%;

        padding: 40px;

    }

    .login-section {

        width: 100%;

        padding: 45px 40px;

    }

    .brand-title {

        font-size: 34px;

    }

}


/* =========================
   MOBILE
========================= */

@media (max-width: 500px) {

    body {

        padding: 10px;

    }

    .login-wrapper {

        border-radius: 16px;

    }

    .brand-section {

        padding: 30px 25px;

    }

    .login-section {

        padding: 35px 25px;

    }

    .brand-title {

        font-size: 30px;

    }

    .login-title {

        font-size: 28px;

    }

}

</style>

</head>


<body>


<!-- MAIN LOGIN WRAPPER -->

<div class="login-wrapper">


    <!-- =========================
         LEFT SIDE
    ========================== -->

    <div class="brand-section">


        <div class="logo-box">

            🏦

        </div>


        <h1 class="brand-title">

            FinIntel AI

        </h1>


        <p class="brand-description">

            AI-powered financial intelligence platform
            for smart loan eligibility prediction and
            financial data analytics.

        </p>


        <div class="feature">

            <div class="feature-icon">

                🤖

            </div>

            <div>

                AI-Powered Loan Assessment

            </div>

        </div>


        <div class="feature">

            <div class="feature-icon">

                📊

            </div>

            <div>

                Advanced Financial Analytics

            </div>

        </div>


        <div class="feature">

            <div class="feature-icon">

                🔒

            </div>

            <div>

                Secure User Data Management

            </div>

        </div>


        <div class="feature">

            <div class="feature-icon">

                📈

            </div>

            <div>

                Smart Prediction Insights

            </div>

        </div>


    </div>



    <!-- =========================
         RIGHT SIDE
    ========================== -->

    <div class="login-section">


        <h2 class="login-title">

            Welcome Back

        </h2>


        <p class="login-subtitle">

            Sign in to access your FinIntel AI dashboard.

        </p>


        <!-- ERROR MESSAGE -->

        <?php if ($message != "") { ?>

            <div class="alert alert-danger">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php } ?>


        <!-- LOGIN FORM -->

        <form method="POST">


            <!-- EMAIL -->

            <div class="mb-4">

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

            <div class="mb-4">

                <label class="form-label">

                    Password

                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter your password"
                    required
                >

            </div>



            <!-- LOGIN BUTTON -->

            <button
                type="submit"
                name="login"
                class="login-btn"
            >

                🔐 Secure Login

            </button>


        </form>



        <!-- REGISTER -->

        <div class="register-text">

            Don't have an account?

            <a href="register.php">

                Create Account

            </a>

        </div>



        <!-- FOOTER -->

        <div class="login-footer">

            © 2026 FinIntel AI

            <br>

            AI-Powered Financial Intelligence Platform

        </div>


    </div>


</div>


</body>

</html>