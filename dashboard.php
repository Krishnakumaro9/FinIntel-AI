<?php

session_start();
include 'db.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['id'];


/* =========================
   DASHBOARD STATISTICS
========================= */

$totalQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM loan_details
     WHERE user_id='$user_id'"
);

$total = mysqli_fetch_assoc($totalQuery)['total'];


$eligibleQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM loan_details
     WHERE user_id='$user_id'
     AND prediction='Eligible'"
);

$eligible = mysqli_fetch_assoc($eligibleQuery)['total'];


$notEligibleQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM loan_details
     WHERE user_id='$user_id'
     AND prediction='Not Eligible'"
);

$notEligible = mysqli_fetch_assoc($notEligibleQuery)['total'];


/* =========================
   ELIGIBILITY RATE
========================= */

$eligibilityRate = 0;

if ($total > 0) {
    $eligibilityRate = round(($eligible / $total) * 100);
}


/* =========================
   RECENT APPLICATIONS
========================= */

$recentApplications = mysqli_query(
    $conn,
    "SELECT *
     FROM loan_details
     WHERE user_id='$user_id'
     ORDER BY id DESC
     LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>FinIntel AI | Dashboard</title>


<!-- Bootstrap -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<!-- Font Awesome -->

<link
rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


<style>

/* =========================
   PROFESSIONAL COLOR SYSTEM
========================= */

:root {
    --navy: #0F172A;
    --navy-light: #1E293B;
    --blue: #2563EB;
    --blue-dark: #1D4ED8;
    --indigo: #4F46E5;
    --background: #F8FAFC;
    --card: #FFFFFF;
    --text: #0F172A;
    --muted: #64748B;
    --border: #E2E8F0;
    --success: #16A34A;
    --danger: #DC2626;
    --warning: #F59E0B;
}


/* =========================
   GLOBAL
========================= */

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: var(--background);
    color: var(--text);
    font-family: Arial, sans-serif;
    overflow-x: hidden;
}


/* =========================
   NAVBAR
========================= */

.navbar {
    background: var(--navy);
    padding: 14px 0;
    box-shadow: 0 2px 15px rgba(15,23,42,.12);
}

.navbar-brand {
    color: white !important;
    font-size: 24px;
    font-weight: 800;
    letter-spacing: .3px;
}

.brand-icon {
    color: #60A5FA;
    margin-right: 8px;
}

.nav-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.nav-buttons .btn {
    border-radius: 9px;
    padding: 8px 15px;
    font-weight: 600;
    white-space: nowrap;
}


/* =========================
   PAGE
========================= */

.dashboard-container {
    max-width: 1350px;
    margin: auto;
    padding: 30px 15px;
}


/* =========================
   WELCOME HERO
========================= */

.welcome-card {
    background:
        linear-gradient(
            135deg,
            var(--navy),
            var(--navy-light) 55%,
            var(--blue)
        );

    border-radius: 22px;
    padding: 35px;
    color: white;
    position: relative;
    overflow: hidden;
    box-shadow: 0 18px 45px rgba(15,23,42,.18);
}

.welcome-card::before {
    content: "";
    position: absolute;
    width: 280px;
    height: 280px;
    border-radius: 50%;
    background: rgba(255,255,255,.06);
    right: -80px;
    top: -100px;
}

.welcome-card h2 {
    font-weight: 800;
    margin-bottom: 10px;
}

.welcome-card p {
    color: #CBD5E1;
    max-width: 650px;
    margin-bottom: 0;
}

.hero-icon {
    font-size: 75px;
    opacity: .9;
    position: relative;
}


/* =========================
   SECTION HEADER
========================= */

.section-header {
    margin: 35px 0 18px;
}

.section-header h4 {
    font-weight: 750;
    margin-bottom: 4px;
}

.section-header p {
    color: var(--muted);
    margin: 0;
}


/* =========================
   STAT CARDS
========================= */

.stat-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 23px;
    height: 100%;
    transition: all .25s ease;
    box-shadow: 0 5px 18px rgba(15,23,42,.04);
}

.stat-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 35px rgba(15,23,42,.10);
}

.stat-icon {
    width: 54px;
    height: 54px;
    border-radius: 15px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 22px;
    margin-bottom: 18px;
}

.icon-blue {
    background: #DBEAFE;
    color: #2563EB;
}

.icon-green {
    background: #DCFCE7;
    color: #16A34A;
}

.icon-red {
    background: #FEE2E2;
    color: #DC2626;
}

.icon-orange {
    background: #FEF3C7;
    color: #D97706;
}

.stat-title {
    color: var(--muted);
    font-size: 14px;
    font-weight: 600;
}

.stat-number {
    font-size: 32px;
    font-weight: 800;
    margin-top: 5px;
}


/* =========================
   QUICK ACTIONS
========================= */

.quick-action {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 25px;
    height: 100%;
    text-decoration: none;
    color: var(--text);
    display: block;
    transition: all .25s ease;
}

.quick-action:hover {
    transform: translateY(-5px);
    color: var(--text);
    box-shadow: 0 15px 30px rgba(15,23,42,.10);
}

.action-icon {
    width: 50px;
    height: 50px;
    background: #EFF6FF;
    color: var(--blue);
    border-radius: 14px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 20px;
    margin-bottom: 16px;
}

.quick-action h5 {
    font-weight: 750;
}

.quick-action p {
    color: var(--muted);
    margin-bottom: 0;
    font-size: 14px;
}


/* =========================
   CONTENT CARD
========================= */

.content-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 8px 25px rgba(15,23,42,.05);
}


/* =========================
   TABLE
========================= */

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #F1F5F9;
    color: #475569;
    border-bottom: 1px solid var(--border);
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .3px;
}

.table td,
.table th {
    padding: 15px;
    vertical-align: middle;
}

.table tbody tr {
    transition: background .2s;
}

.table tbody tr:hover {
    background: #F8FAFC;
}


/* =========================
   BADGES
========================= */

.badge {
    padding: 8px 11px;
    border-radius: 20px;
    font-weight: 600;
}


/* =========================
   FOOTER
========================= */

footer {
    margin-top: 50px;
    background: var(--navy);
    color: #CBD5E1;
    text-align: center;
    padding: 25px 15px;
    font-size: 14px;
}

footer strong {
    color: white;
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 992px) {

    .navbar .container {
        flex-direction: column;
        align-items: flex-start;
        gap: 14px;
    }

    .nav-buttons {
        width: 100%;
        justify-content: flex-start;
    }

    .hero-icon {
        margin-top: 25px;
    }

}


@media (max-width: 768px) {

    .dashboard-container {
        padding: 20px 12px;
    }

    .welcome-card {
        padding: 25px;
        text-align: center;
    }

    .welcome-card h2 {
        font-size: 25px;
    }

    .nav-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
    }

    .nav-buttons .btn {
        width: 100%;
    }

    .stat-number {
        font-size: 28px;
    }

}


@media (max-width: 480px) {

    .nav-buttons {
        grid-template-columns: 1fr;
    }

}

</style>

</head>


<body>


<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar">

<div class="container">

<a href="dashboard.php" class="navbar-brand">

<i class="fa-solid fa-building-columns brand-icon"></i>

FinIntel AI

</a>


<div class="nav-buttons">

<a href="loan_form.php" class="btn btn-primary">

<i class="fa-solid fa-plus"></i>

New Application

</a>


<a href="history.php" class="btn btn-outline-light">

<i class="fa-solid fa-clock-rotate-left"></i>

History

</a>


<a href="analytics.php" class="btn btn-outline-light">

<i class="fa-solid fa-chart-line"></i>

Analytics

</a>


<a href="logout.php" class="btn btn-danger">

<i class="fa-solid fa-right-from-bracket"></i>

Logout

</a>

</div>

</div>

</nav>



<!-- =========================
     DASHBOARD
========================= -->

<main class="dashboard-container">


<!-- WELCOME -->

<div class="welcome-card">

<div class="row align-items-center">

<div class="col-lg-8">

<div class="mb-2 text-uppercase small"
style="letter-spacing:1px;color:#93C5FD;">

Financial Intelligence Platform

</div>

<h2>

Welcome back,
<?php echo htmlspecialchars($_SESSION['user']); ?> 👋

</h2>

<p>

Monitor your loan applications, AI predictions,
and financial analytics from one centralized dashboard.

</p>

<div class="mt-4">

<a href="loan_form.php"
class="btn btn-light px-4">

<i class="fa-solid fa-wand-magic-sparkles"></i>

Start New Analysis

</a>

</div>

</div>


<div class="col-lg-4 text-lg-end text-center">

<i class="fa-solid fa-chart-line hero-icon"></i>

</div>

</div>

</div>



<!-- APPLICATION OVERVIEW -->

<div class="section-header">

<h4>

<i class="fa-solid fa-chart-pie text-primary"></i>

Application Overview

</h4>

<p>
A quick summary of your loan prediction activity.
</p>

</div>


<div class="row g-4">


<!-- TOTAL -->

<div class="col-md-6 col-xl-3">

<div class="stat-card">

<div class="stat-icon icon-blue">

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



<!-- ELIGIBLE -->

<div class="col-md-6 col-xl-3">

<div class="stat-card">

<div class="stat-icon icon-green">

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



<!-- NOT ELIGIBLE -->

<div class="col-md-6 col-xl-3">

<div class="stat-card">

<div class="stat-icon icon-red">

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



<!-- RATE -->

<div class="col-md-6 col-xl-3">

<div class="stat-card">

<div class="stat-icon icon-orange">

<i class="fa-solid fa-percent"></i>

</div>

<div class="stat-title">

Eligibility Rate

</div>

<div class="stat-number"
style="color:#D97706;">

<?php echo $eligibilityRate; ?>%

</div>

</div>

</div>


</div>



<!-- QUICK ACTIONS -->

<div class="section-header">

<h4>

<i class="fa-solid fa-bolt text-primary"></i>

Quick Actions

</h4>

<p>
Access the most important features quickly.
</p>

</div>


<div class="row g-4">


<div class="col-md-4">

<a href="loan_form.php"
class="quick-action">

<div class="action-icon">

<i class="fa-solid fa-plus"></i>

</div>

<h5>
New Loan Analysis
</h5>

<p>
Submit financial details and get an AI-based eligibility prediction.
</p>

</a>

</div>



<div class="col-md-4">

<a href="history.php"
class="quick-action">

<div class="action-icon">

<i class="fa-solid fa-clock-rotate-left"></i>

</div>

<h5>
Prediction History
</h5>

<p>
Review your previous loan applications and prediction results.
</p>

</a>

</div>



<div class="col-md-4">

<a href="analytics.php"
class="quick-action">

<div class="action-icon">

<i class="fa-solid fa-chart-column"></i>

</div>

<h5>
Financial Analytics
</h5>

<p>
Explore application patterns and insights from your stored data.
</p>

</a>

</div>


</div>



<!-- RECENT APPLICATIONS -->

<div class="content-card mt-5">


<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h4 class="mb-1">

<i class="fa-solid fa-clock text-primary"></i>

Recent Applications

</h4>

<p class="text-muted mb-0">
Your latest loan prediction records.
</p>

</div>


<a href="history.php"
class="btn btn-primary">

View All

</a>

</div>



<div class="table-responsive">

<table class="table align-middle">


<thead>

<tr>

<th>Loan Type</th>

<th>Income</th>

<th>Loan Amount</th>

<th>Credit Score</th>

<th>Prediction</th>

</tr>

</thead>


<tbody>

<?php

if (mysqli_num_rows($recentApplications) > 0) {

    while (
        $row =
        mysqli_fetch_assoc($recentApplications)
    ) {

?>

<tr>


<td>

<strong>

<?php
echo htmlspecialchars(
    $row['loan_type']
);
?>

</strong>

</td>



<td>

₹<?php
echo number_format(
    $row['income']
);
?>

</td>



<td>

₹<?php
echo number_format(
    $row['loan_amount']
);
?>

</td>



<td>

<?php
echo htmlspecialchars(
    $row['credit_score']
);
?>

</td>



<td>

<?php

if (
    $row['prediction']
    == "Eligible"
) {

?>

<span class="badge bg-success">

<i class="fa-solid fa-check"></i>

Eligible

</span>

<?php

} else {

?>

<span class="badge bg-danger">

<i class="fa-solid fa-xmark"></i>

Not Eligible

</span>

<?php

}

?>

</td>


</tr>


<?php

    }

} else {

?>

<tr>

<td colspan="5"
class="text-center text-muted py-4">

<i class="fa-solid fa-folder-open fs-4 d-block mb-2"></i>

No applications available yet.

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>


</div>


</main>



<!-- FOOTER -->

<footer>

<strong>FinIntel AI</strong>

<br>

AI-Powered Financial Intelligence & Data Analytics Platform

<br><br>

© 2026 Developed by Krishna Kumar

</footer>



<!-- Bootstrap JS -->

<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>


</body>

</html>