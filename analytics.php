<?php

session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['id'];


/* =========================
   BASIC STATISTICS
========================= */

$totalQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM loan_details
     WHERE user_id='$user_id'"
);

$total = mysqli_fetch_assoc($totalQuery)['total'] ?? 0;


$eligibleQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM loan_details
     WHERE user_id='$user_id'
     AND prediction='Eligible'"
);

$eligible = mysqli_fetch_assoc($eligibleQuery)['total'] ?? 0;


$notEligibleQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM loan_details
     WHERE user_id='$user_id'
     AND prediction='Not Eligible'"
);

$notEligible = mysqli_fetch_assoc($notEligibleQuery)['total'] ?? 0;


/* =========================
   AVERAGE FINANCIAL DATA
========================= */

$averageQuery = mysqli_query(
    $conn,
    "SELECT
        AVG(income) AS avg_income,
        AVG(credit_score) AS avg_credit,
        AVG(loan_amount) AS avg_loan
     FROM loan_details
     WHERE user_id='$user_id'"
);

$averageData = mysqli_fetch_assoc($averageQuery);

$avgIncome = round($averageData['avg_income'] ?? 0);
$avgCredit = round($averageData['avg_credit'] ?? 0);
$avgLoan = round($averageData['avg_loan'] ?? 0);


/* =========================
   LOAN TYPE DATA
========================= */

$loanTypeQuery = mysqli_query(
    $conn,
    "SELECT
        loan_type,
        COUNT(*) AS total
     FROM loan_details
     WHERE user_id='$user_id'
     GROUP BY loan_type"
);

$loanLabels = [];
$loanValues = [];

while ($row = mysqli_fetch_assoc($loanTypeQuery)) {

    $loanLabels[] = $row['loan_type'];
    $loanValues[] = (int)$row['total'];
}


/* =========================
   ELIGIBILITY DATA
========================= */

$eligibilityLabels = ["Eligible", "Not Eligible"];

$eligibilityValues = [
    (int)$eligible,
    (int)$notEligible
];


/* =========================
   RECENT DATA
========================= */

$recentQuery = mysqli_query(
    $conn,
    "SELECT
        id,
        income,
        credit_score,
        loan_amount,
        prediction
     FROM loan_details
     WHERE user_id='$user_id'
     ORDER BY id ASC"
);

$incomeLabels = [];
$incomeData = [];
$loanData = [];

while ($row = mysqli_fetch_assoc($recentQuery)) {

    $incomeLabels[] = "Application " . $row['id'];
    $incomeData[] = (int)$row['income'];
    $loanData[] = (int)$row['loan_amount'];
}


/* =========================
   ELIGIBILITY RATE
========================= */

$eligibilityRate = 0;

if ($total > 0) {
    $eligibilityRate = round(($eligible / $total) * 100);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>FinIntel AI | Analytics</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>

:root {
    --navy: #0F172A;
    --navy-light: #1E293B;
    --blue: #2563EB;
    --blue-dark: #1D4ED8;
    --background: #F8FAFC;
    --card: #FFFFFF;
    --text: #0F172A;
    --muted: #64748B;
    --border: #E2E8F0;
}

body {
    margin: 0;
    background: var(--background);
    font-family: Arial, sans-serif;
    color: var(--text);
}

/* NAVBAR */

.navbar {
    background: var(--navy);
    padding: 14px 0;
    box-shadow: 0 2px 15px rgba(15,23,42,.12);
}

.navbar-brand {
    color: white !important;
    font-size: 24px;
    font-weight: 800;
}

.nav-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.nav-buttons .btn {
    border-radius: 9px;
    font-weight: 600;
}

/* MAIN */

.analytics-container {
    max-width: 1400px;
    margin: auto;
    padding: 35px 15px;
}

/* HERO */

.analytics-hero {
    background:
        linear-gradient(
            135deg,
            var(--navy),
            var(--navy-light),
            var(--blue)
        );

    border-radius: 22px;
    padding: 35px;
    color: white;
    margin-bottom: 30px;
}

.analytics-hero h2 {
    font-weight: 800;
}

.analytics-hero p {
    color: #CBD5E1;
    margin-bottom: 0;
}

.hero-icon {
    font-size: 70px;
}

/* STAT CARDS */

.stat-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 22px;
    height: 100%;
    box-shadow: 0 5px 18px rgba(15,23,42,.04);
    transition: .25s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(15,23,42,.10);
}

.stat-icon {
    font-size: 22px;
    margin-bottom: 15px;
}

.stat-title {
    color: var(--muted);
    font-size: 14px;
    font-weight: 600;
}

.stat-number {
    font-size: 28px;
    font-weight: 800;
}

/* CHART CARD */

.chart-card {
    background: white;
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 25px;
    height: 100%;
    box-shadow: 0 8px 25px rgba(15,23,42,.05);
}

.chart-card h5 {
    font-weight: 750;
}

.chart-card p {
    color: var(--muted);
    font-size: 14px;
}

.chart-container {
    position: relative;
    height: 320px;
}

/* INSIGHT */

.insight-card {
    background: white;
    border-radius: 18px;
    border: 1px solid var(--border);
    padding: 22px;
    height: 100%;
}

.insight-number {
    font-size: 26px;
    font-weight: 800;
    color: var(--blue);
}

/* FOOTER */

footer {
    margin-top: 50px;
    background: var(--navy);
    color: #CBD5E1;
    text-align: center;
    padding: 25px;
}

footer strong {
    color: white;
}

/* RESPONSIVE */

@media (max-width: 768px) {

    .navbar .container {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
    }

    .nav-buttons {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }

    .nav-buttons .btn {
        width: 100%;
    }

    .analytics-hero {
        text-align: center;
    }

}

@media (max-width: 480px) {

    .nav-buttons {
        grid-template-columns: 1fr;
    }

    .chart-container {
        height: 260px;
    }

}

</style>

</head>

<body>


<!-- NAVBAR -->

<nav class="navbar">

<div class="container">

<a href="dashboard.php" class="navbar-brand">

<i class="fa-solid fa-building-columns me-2"></i>

FinIntel AI

</a>

<div class="nav-buttons">

<a href="dashboard.php" class="btn btn-outline-light">
<i class="fa-solid fa-house"></i> Dashboard
</a>

<a href="loan_form.php" class="btn btn-primary">
<i class="fa-solid fa-robot"></i> AI Analysis
</a>

<a href="history.php" class="btn btn-outline-light">
<i class="fa-solid fa-clock-rotate-left"></i> History
</a>

<a href="logout.php" class="btn btn-danger">
<i class="fa-solid fa-right-from-bracket"></i> Logout
</a>

</div>

</div>

</nav>


<main class="analytics-container">


<!-- HERO -->

<div class="analytics-hero">

<div class="row align-items-center">

<div class="col-md-9">

<div class="small text-uppercase mb-2"
style="letter-spacing:1px;color:#93C5FD;">

Data Intelligence

</div>

<h2>Financial Analytics Dashboard</h2>

<p>
Explore loan application patterns, financial trends and
AI prediction insights from your stored data.
</p>

</div>

<div class="col-md-3 text-md-end text-center mt-3 mt-md-0">

<i class="fa-solid fa-chart-pie hero-icon"></i>

</div>

</div>

</div>


<!-- STATISTICS -->

<div class="row g-4 mb-4">

<div class="col-md-6 col-xl-3">

<div class="stat-card">

<div class="stat-icon text-primary">
<i class="fa-solid fa-file-lines"></i>
</div>

<div class="stat-title">
Total Applications
</div>

<div class="stat-number">
<?php echo $total; ?>
</div>

</div>

</div>


<div class="col-md-6 col-xl-3">

<div class="stat-card">

<div class="stat-icon text-success">
<i class="fa-solid fa-circle-check"></i>
</div>

<div class="stat-title">
Eligible Applications
</div>

<div class="stat-number text-success">
<?php echo $eligible; ?>
</div>

</div>

</div>


<div class="col-md-6 col-xl-3">

<div class="stat-card">

<div class="stat-icon text-danger">
<i class="fa-solid fa-circle-xmark"></i>
</div>

<div class="stat-title">
Not Eligible
</div>

<div class="stat-number text-danger">
<?php echo $notEligible; ?>
</div>

</div>

</div>


<div class="col-md-6 col-xl-3">

<div class="stat-card">

<div class="stat-icon text-warning">
<i class="fa-solid fa-percent"></i>
</div>

<div class="stat-title">
Eligibility Rate
</div>

<div class="stat-number text-warning">
<?php echo $eligibilityRate; ?>%
</div>

</div>

</div>

</div>


<!-- CHARTS ROW 1 -->

<div class="row g-4 mb-4">


<div class="col-lg-6">

<div class="chart-card">

<h5>
<i class="fa-solid fa-chart-pie text-primary me-2"></i>
Eligibility Distribution
</h5>

<p>
Comparison between eligible and not eligible applications.
</p>

<div class="chart-container">

<canvas id="eligibilityChart"></canvas>

</div>

</div>

</div>


<div class="col-lg-6">

<div class="chart-card">

<h5>
<i class="fa-solid fa-chart-column text-primary me-2"></i>
Loan Type Distribution
</h5>

<p>
Number of applications by loan category.
</p>

<div class="chart-container">

<canvas id="loanTypeChart"></canvas>

</div>

</div>

</div>


</div>


<!-- CHART ROW 2 -->

<div class="row g-4">


<div class="col-lg-8">

<div class="chart-card">

<h5>
<i class="fa-solid fa-chart-line text-primary me-2"></i>
Income vs Loan Amount
</h5>

<p>
Financial comparison across loan applications.
</p>

<div class="chart-container">

<canvas id="incomeLoanChart"></canvas>

</div>

</div>

</div>


<div class="col-lg-4">

<div class="chart-card">

<h5>
<i class="fa-solid fa-chart-simple text-primary me-2"></i>
Financial Averages
</h5>

<p>
Average values calculated from your application data.
</p>

<div class="chart-container">

<canvas id="averageChart"></canvas>

</div>

</div>

</div>


</div>


<!-- DATA INSIGHTS -->

<div class="mt-5">

<h4 class="fw-bold mb-3">

<i class="fa-solid fa-lightbulb text-warning me-2"></i>

Key Data Insights

</h4>


<div class="row g-4">


<div class="col-md-4">

<div class="insight-card">

<div class="text-muted">
Average Monthly Income
</div>

<div class="insight-number">

₹<?php echo number_format($avgIncome); ?>

</div>

</div>

</div>


<div class="col-md-4">

<div class="insight-card">

<div class="text-muted">
Average Credit Score
</div>

<div class="insight-number">

<?php echo $avgCredit; ?>

</div>

</div>

</div>


<div class="col-md-4">

<div class="insight-card">

<div class="text-muted">
Average Loan Amount
</div>

<div class="insight-number">

₹<?php echo number_format($avgLoan); ?>

</div>

</div>

</div>


</div>

</div>


</main>


<footer>

<strong>FinIntel AI</strong>

<br>

AI-Powered Financial Intelligence & Data Analytics Platform

<br><br>

© 2026 Developed by Krishna Kumar

</footer>


<!-- CHART JAVASCRIPT -->

<script>

const eligibilityLabels =
<?php echo json_encode($eligibilityLabels); ?>;

const eligibilityValues =
<?php echo json_encode($eligibilityValues); ?>;


new Chart(
    document.getElementById('eligibilityChart'),
    {
        type: 'doughnut',

        data: {
            labels: eligibilityLabels,

            datasets: [{
                data: eligibilityValues,
                backgroundColor: [
                    '#16A34A',
                    '#DC2626'
                ]
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    }
);



const loanLabels =
<?php echo json_encode($loanLabels); ?>;

const loanValues =
<?php echo json_encode($loanValues); ?>;


new Chart(
    document.getElementById('loanTypeChart'),
    {
        type: 'bar',

        data: {
            labels: loanLabels,

            datasets: [{
                label: 'Applications',
                data: loanValues,
                backgroundColor: '#2563EB',
                borderRadius: 8
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    }
);



const incomeLabels =
<?php echo json_encode($incomeLabels); ?>;

const incomeData =
<?php echo json_encode($incomeData); ?>;

const loanData =
<?php echo json_encode($loanData); ?>;


new Chart(
    document.getElementById('incomeLoanChart'),
    {
        type: 'line',

        data: {
            labels: incomeLabels,

            datasets: [

                {
                    label: 'Monthly Income',
                    data: incomeData,
                    borderColor: '#2563EB',
                    backgroundColor:
                    'rgba(37,99,235,.1)',
                    tension: .4
                },

                {
                    label: 'Loan Amount',
                    data: loanData,
                    borderColor: '#7C3AED',
                    backgroundColor:
                    'rgba(124,58,237,.1)',
                    tension: .4
                }

            ]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    }
);



new Chart(
    document.getElementById('averageChart'),
    {
        type: 'bar',

        data: {

            labels: [
                'Avg Income',
                'Avg Credit',
                'Avg Loan'
            ],

            datasets: [{

                data: [

                    <?php echo $avgIncome; ?>,

                    <?php echo $avgCredit; ?>,

                    <?php echo $avgLoan; ?>

                ],

                backgroundColor: [

                    '#2563EB',

                    '#F59E0B',

                    '#4F46E5'

                ],

                borderRadius: 8

            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    display: false
                }

            }

        }

    }

);

</script>


<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>