<?php

session_start();

include 'db.php';


/* =====================================
   LOGIN CHECK
===================================== */

if (!isset($_SESSION['id'])) {

    header("Location: login.php");
    exit();

}


$user_id = (int) $_SESSION['id'];

$message = "";
$prediction = "";
$recommendations = [];
$rawPrediction = "";


/* =====================================
   FORM SUBMISSION
===================================== */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    /* =====================================
       GET FORM DATA
    ===================================== */

    $loan_type = trim($_POST['loan_type'] ?? "");

    $income = (int)($_POST['income'] ?? 0);

    $credit_score = (int)($_POST['credit_score'] ?? 0);

    $employment = trim($_POST['employment'] ?? "");

    $loan_amount = (int)($_POST['loan_amount'] ?? 0);

    $tenure = (int)($_POST['tenure'] ?? 0);

    $age = (int)($_POST['age'] ?? 0);


    /* =====================================
       INPUT VALIDATION
    ===================================== */

    if ($loan_type === "") {

        $message = "Please select a loan type.";

    }

    elseif ($income <= 0) {

        $message = "Income must be greater than ₹0.";

    }

    elseif ($credit_score < 300 || $credit_score > 900) {

        $message = "Credit score must be between 300 and 900.";

    }

    elseif ($employment === "") {

        $message = "Please select employment type.";

    }

    elseif ($loan_amount <= 0) {

        $message = "Loan amount must be greater than ₹0.";

    }

    elseif ($tenure <= 0) {

        $message = "Loan tenure must be greater than 0.";

    }

    elseif ($age < 18 || $age > 100) {

        $message = "Age must be between 18 and 100.";

    }

    else {


        /* =====================================
           PYTHON CONFIGURATION
        ===================================== */

        $pythonPath = "/opt/anaconda3/bin/python3";

        $scriptPath = __DIR__ . "/predict.py";


        /* =====================================
           CHECK PYTHON FILE
        ===================================== */

        if (!file_exists($scriptPath)) {

            $message = "Prediction system error: predict.py not found.";

        }

        elseif (!is_executable($pythonPath)) {

            $message = "Prediction system error: Python executable not found.";

        }

        else {


            /* =====================================
               BUILD PYTHON COMMAND
            ===================================== */

            $command =
                escapeshellarg($pythonPath)
                . " "
                . escapeshellarg($scriptPath)
                . " "
                . escapeshellarg((string)$income)
                . " "
                . escapeshellarg((string)$credit_score)
                . " "
                . escapeshellarg((string)$loan_amount)
                . " "
                . escapeshellarg((string)$age)
                . " "
                . escapeshellarg($employment);


            /* =====================================
               EXECUTE PYTHON
            ===================================== */

            $output = shell_exec($command . " 2>&1");

            $rawPrediction = trim($output ?? "");


            /* =====================================
               GET LAST NON-EMPTY LINE
            ===================================== */

            $predictionOutput = "";

            $lines = preg_split(
                "/\r\n|\n|\r/",
                $rawPrediction
            );

            foreach ($lines as $line) {

                $line = trim($line);

                if ($line !== "") {

                    $predictionOutput = $line;

                }

            }


            /* =====================================
               NORMALIZE AI RESULT
            ===================================== */

            if (
                strcasecmp($predictionOutput, "Eligible") === 0
                ||
                strcasecmp($predictionOutput, "Yes") === 0
            ) {

                $prediction = "Eligible";

            }

            elseif (
                strcasecmp($predictionOutput, "Not Eligible") === 0
                ||
                strcasecmp($predictionOutput, "No") === 0
            ) {

                $prediction = "Not Eligible";

            }

            else {

                $prediction = "";

            }


            /* =====================================
               PREDICTION ERROR
            ===================================== */

            if ($prediction === "") {

                $message =
                    "Prediction failed. Python output: "
                    . ($rawPrediction !== ""
                        ? $rawPrediction
                        : "No output received.");

            }

            else {


                /* =====================================
                   DATABASE INSERT
                ===================================== */

                $stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO loan_details
                    (
                        user_id,
                        loan_type,
                        income,
                        credit_score,
                        employment_type,
                        loan_amount,
                        tenure,
                        age,
                        prediction
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
                );


                if (!$stmt) {

                    $message =
                        "Database Error: "
                        . mysqli_error($conn);

                }

                else {


                    mysqli_stmt_bind_param(
                        $stmt,
                        "isiisiiis",
                        $user_id,
                        $loan_type,
                        $income,
                        $credit_score,
                        $employment,
                        $loan_amount,
                        $tenure,
                        $age,
                        $prediction
                    );


                    if (mysqli_stmt_execute($stmt)) {

                        $message =
                            "Loan application submitted and analyzed successfully.";

                    }

                    else {

                        $message =
                            "Database Error: "
                            . mysqli_stmt_error($stmt);

                    }


                    mysqli_stmt_close($stmt);

                }


                /* =====================================
                   SMART RECOMMENDATIONS
                ===================================== */

                if ($prediction === "Eligible") {

                    $recommendations[] =
                        "Your financial profile meets the AI model eligibility criteria.";

                    $recommendations[] =
                        "Maintain a strong credit score and positive repayment history.";

                    $recommendations[] =
                        "Review the loan tenure and estimated EMI before making a final decision.";

                    if ($credit_score >= 750) {

                        $recommendations[] =
                            "Your credit score is strong, which supports a healthy financial profile.";

                    }

                }

                else {


                    if ($credit_score < 650) {

                        $recommendations[] =
                            "Improve your credit score to increase future loan eligibility.";

                    }

                    if ($loan_amount > ($income * 12)) {

                        $recommendations[] =
                            "Consider reducing the requested loan amount.";

                    }

                    if ($income < 25000) {

                        $recommendations[] =
                            "Increasing your monthly income may improve your eligibility.";

                    }

                    $recommendations[] =
                        "Maintain stable income and a positive repayment record.";

                }

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
content="width=device-width, initial-scale=1.0">

<title>
FinIntel AI | Apply for Loan
</title>


<!-- Bootstrap -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<!-- Font Awesome -->

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


<style>

/* =====================================
   VARIABLES
===================================== */

:root {

    --navy: #0F172A;

    --navy-light: #1E293B;

    --blue: #2563EB;

    --blue-dark: #1D4ED8;

    --background: #F8FAFC;

    --border: #E2E8F0;

    --text: #0F172A;

    --muted: #64748B;

}


/* =====================================
   BODY
===================================== */

body {

    margin: 0;

    background: var(--background);

    color: var(--text);

    font-family:
        Arial,
        Helvetica,
        sans-serif;

}


/* =====================================
   NAVBAR
===================================== */

.navbar {

    background: var(--navy);

    padding: 15px 0;

    box-shadow:
        0 4px 15px
        rgba(0,0,0,0.10);

}


.navbar-brand {

    color: white !important;

    font-size: 24px;

    font-weight: 800;

    letter-spacing: -0.5px;

}


.nav-buttons {

    display: flex;

    gap: 8px;

    flex-wrap: wrap;

}


.nav-buttons .btn {

    border-radius: 8px;

    font-weight: 600;

}


/* =====================================
   PAGE
===================================== */

.page-container {

    max-width: 1150px;

    margin: auto;

    padding: 40px 15px 20px;

}


/* =====================================
   HERO
===================================== */

.page-hero {

    color: white;

    padding: 40px;

    border-radius: 22px;

    margin-bottom: 30px;

    background:

        linear-gradient(
            135deg,
            #0F172A,
            #1E293B,
            #2563EB
        );

    box-shadow:
        0 15px 35px
        rgba(15,23,42,0.20);

}


.page-hero h1 {

    font-size: 36px;

    font-weight: 800;

    margin-bottom: 10px;

}


.page-hero p {

    color: #E2E8F0;

    font-size: 16px;

}


.hero-icon {

    font-size: 70px;

    opacity: 0.95;

}


/* =====================================
   ALERT
===================================== */

.alert {

    border-radius: 12px;

    font-weight: 600;

}


/* =====================================
   FORM CARD
===================================== */

.form-card {

    background: white;

    padding: 35px;

    border-radius: 20px;

    border: 1px solid var(--border);

    box-shadow:
        0 10px 30px
        rgba(0,0,0,0.06);

}


.form-card h3 {

    font-weight: 800;

}


/* =====================================
   SECTION TITLE
===================================== */

.section-title {

    font-size: 18px;

    font-weight: 700;

    margin-top: 28px;

    margin-bottom: 20px;

    padding-bottom: 10px;

    border-bottom:
        1px solid
        var(--border);

}


/* =====================================
   FORM
===================================== */

.form-label {

    font-weight: 600;

    color: #334155;

}


.form-control,
.form-select {

    padding: 12px 14px;

    border-radius: 9px;

    border:
        1px solid
        #CBD5E1;

}


.form-control:focus,
.form-select:focus {

    border-color: var(--blue);

    box-shadow:
        0 0 0 3px
        rgba(37,99,235,0.15);

}


/* =====================================
   APPLY BUTTON
===================================== */

.apply-btn {

    width: 100%;

    padding: 14px;

    border: none;

    border-radius: 10px;

    font-size: 17px;

    font-weight: 700;

    background: var(--blue);

    color: white;

    transition: 0.2s;

}


.apply-btn:hover {

    background: var(--blue-dark);

    transform: translateY(-1px);

}


.apply-btn:disabled {

    opacity: 0.8;

    cursor: not-allowed;

}


/* =====================================
   RESULT
===================================== */

.result-card {

    margin-top: 30px;

    padding: 35px;

    border-radius: 20px;

    box-shadow:
        0 10px 30px
        rgba(0,0,0,0.05);

}


.eligible {

    background: #F0FDF4;

    border:
        1px solid
        #BBF7D0;

}


.not-eligible {

    background: #FEF2F2;

    border:
        1px solid
        #FECACA;

}


.result-icon {

    font-size: 55px;

    margin-bottom: 10px;

}


.result-title {

    font-size: 30px;

    font-weight: 800;

}


.result-subtitle {

    color: var(--muted);

}


/* =====================================
   RECOMMENDATIONS
===================================== */

.recommendations {

    margin-top: 25px;

    background: white;

    padding: 22px;

    border-radius: 15px;

    border: 1px solid var(--border);

}


.recommendations h5 {

    font-weight: 800;

}


.recommendations li {

    margin-bottom: 9px;

    color: #475569;

}


/* =====================================
   FOOTER
===================================== */

footer {

    margin-top: 50px;

    padding: 28px 15px;

    text-align: center;

    background: var(--navy);

    color: #CBD5E1;

}


footer strong {

    color: white;

    font-size: 18px;

}


/* =====================================
   MOBILE
===================================== */

@media (max-width: 768px) {

    .navbar .container {

        flex-direction: column;

        gap: 15px;

    }


    .navbar-brand {

        font-size: 22px;

    }


    .nav-buttons {

        width: 100%;

        display: grid;

        grid-template-columns: 1fr 1fr;

    }


    .nav-buttons .btn {

        width: 100%;

    }


    .page-container {

        padding-top: 25px;

    }


    .page-hero {

        padding: 30px 20px;

        text-align: center;

    }


    .page-hero h1 {

        font-size: 29px;

    }


    .hero-icon {

        font-size: 50px;

        margin-top: 20px;

    }


    .form-card {

        padding: 20px;

    }


    .result-card {

        padding: 25px 18px;

    }


    .result-title {

        font-size: 25px;

    }

}


@media (max-width: 500px) {

    .nav-buttons {

        grid-template-columns: 1fr;

    }

}

</style>

</head>


<body>


<!-- =====================================
     NAVBAR
===================================== -->

<nav class="navbar">

<div class="container">


<a
href="dashboard.php"
class="navbar-brand">

<i
class="fa-solid fa-building-columns me-2">
</i>

FinIntel AI

</a>


<div class="nav-buttons">


<a
href="dashboard.php"
class="btn btn-outline-light">

<i
class="fa-solid fa-house me-1">
</i>

Dashboard

</a>


<a
href="analytics.php"
class="btn btn-outline-light">

<i
class="fa-solid fa-chart-line me-1">
</i>

Analytics

</a>


<a
href="history.php"
class="btn btn-outline-light">

<i
class="fa-solid fa-clock-rotate-left me-1">
</i>

History

</a>


<a
href="logout.php"
class="btn btn-danger">

<i
class="fa-solid fa-right-from-bracket me-1">
</i>

Logout

</a>


</div>

</div>

</nav>


<!-- =====================================
     MAIN
===================================== -->

<main class="page-container">


<!-- =====================================
     HERO
===================================== -->

<div class="page-hero">

<div class="row align-items-center">


<div class="col-md-9">

<h1>

Apply for a Loan

</h1>

<p class="mb-0">

Submit your financial details and receive an
AI-powered loan eligibility assessment.

</p>

</div>


<div class="col-md-3 text-center">

<i
class="fa-solid fa-file-circle-check hero-icon">
</i>

</div>


</div>

</div>


<!-- =====================================
     MESSAGE
===================================== -->

<?php if ($message !== ""): ?>

<div
class="alert
<?php

if ($prediction === "Eligible") {

    echo "alert-success";

}

elseif ($prediction === "Not Eligible") {

    echo "alert-danger";

}

else {

    echo "alert-warning";

}

?>">

<i
class="fa-solid
<?php

if ($prediction === "Eligible") {

    echo "fa-circle-check";

}

elseif ($prediction === "Not Eligible") {

    echo "fa-circle-xmark";

}

else {

    echo "fa-triangle-exclamation";

}

?> me-2">
</i>

<?php

echo htmlspecialchars($message);

?>

</div>

<?php endif; ?>


<!-- =====================================
     FORM CARD
===================================== -->

<div class="form-card">


<h3>

<i
class="fa-solid fa-file-lines text-primary me-2">
</i>

Loan Application

</h3>


<p class="text-muted">

Enter your information accurately for a reliable AI assessment.

</p>


<form
method="POST"
id="loanForm">


<!-- =====================================
     LOAN INFORMATION
===================================== -->

<div class="section-title">

<i
class="fa-solid fa-building-columns text-primary me-2">
</i>

Loan Information

</div>


<div class="row">


<!-- Loan Type -->

<div class="col-md-6 mb-3">

<label class="form-label">

Loan Type

</label>


<select
name="loan_type"
class="form-select"
required>

<option value="Personal">

Personal Loan

</option>

<option value="Home">

Home Loan

</option>

<option value="Education">

Education Loan

</option>

<option value="Business">

Business Loan

</option>

</select>

</div>


<!-- Loan Amount -->

<div class="col-md-6 mb-3">

<label class="form-label">

Loan Amount

</label>


<input
type="number"
name="loan_amount"
class="form-control"
placeholder="Example: 500000"
min="1"
required>

</div>


<!-- Tenure -->

<div class="col-md-6 mb-3">

<label class="form-label">

Loan Tenure (Months)

</label>


<input
type="number"
name="tenure"
class="form-control"
placeholder="Example: 24"
min="1"
required>

</div>


</div>


<!-- =====================================
     FINANCIAL PROFILE
===================================== -->

<div class="section-title">

<i
class="fa-solid fa-user text-primary me-2">
</i>

Financial Profile

</div>


<div class="row">


<!-- Income -->

<div class="col-md-6 mb-3">

<label class="form-label">

Monthly Income

</label>


<input
type="number"
name="income"
class="form-control"
placeholder="Example: 50000"
min="1"
required>

</div>


<!-- Credit Score -->

<div class="col-md-6 mb-3">

<label class="form-label">

Credit Score

</label>


<input
type="number"
name="credit_score"
class="form-control"
placeholder="300 - 900"
min="300"
max="900"
required>

</div>


<!-- Employment -->

<div class="col-md-6 mb-3">

<label class="form-label">

Employment Type

</label>


<select
name="employment"
class="form-select"
required>

<option value="Salaried">

Salaried

</option>

<option value="Self Employed">

Self Employed

</option>

</select>

</div>


<!-- Age -->

<div class="col-md-6 mb-3">

<label class="form-label">

Age

</label>


<input
type="number"
name="age"
class="form-control"
placeholder="18 - 100"
min="18"
max="100"
required>

</div>


</div>


<!-- =====================================
     SUBMIT
===================================== -->

<div class="mt-4">

<button
type="submit"
name="submit"
class="apply-btn"
id="applyButton">

<i
class="fa-solid fa-paper-plane me-2">
</i>

Apply & Analyze Loan

</button>

</div>


</form>

</div>


<!-- =====================================
     RESULT
===================================== -->

<?php if ($prediction !== ""): ?>


<div
class="result-card
<?php

echo
$prediction === "Eligible"
    ? "eligible"
    : "not-eligible";

?>">


<div class="text-center">


<div class="result-icon">

<?php

if ($prediction === "Eligible") {

    echo "✅";

}

else {

    echo "❌";

}

?>

</div>


<div class="result-title">

<?php

if ($prediction === "Eligible") {

    echo "Loan Eligible";

}

else {

    echo "Loan Not Eligible";

}

?>

</div>


<p class="result-subtitle mb-0">

AI financial assessment completed successfully.

</p>


</div>


<?php if (!empty($recommendations)): ?>


<div class="recommendations">


<h5>

<i
class="fa-solid fa-lightbulb text-warning me-2">
</i>

Smart Recommendations

</h5>


<ul class="mb-0 mt-3">


<?php foreach ($recommendations as $recommendation): ?>

<li>

<?php

echo htmlspecialchars(
    $recommendation
);

?>

</li>

<?php endforeach; ?>


</ul>


</div>


<?php endif; ?>


</div>


<?php endif; ?>


</main>


<!-- =====================================
     FOOTER
===================================== -->

<footer>

<strong>

FinIntel AI

</strong>

<br>

AI-Powered Financial Intelligence Platform

<br><br>

© 2026 Developed by Krishna Kumar

</footer>


<!-- =====================================
     JAVASCRIPT
===================================== -->

<script>

document
.getElementById("loanForm")
.addEventListener(
    "submit",
    function () {

        const button =
            document.getElementById(
                "applyButton"
            );


        button.disabled = true;


        button.innerHTML =
            '<i class="fa-solid fa-spinner fa-spin me-2"></i>Analyzing...';

    }
);

</script>


</body>

</html>