<?php
session_start();
include 'db.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];

$result = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($result);

$total = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM loan_details WHERE user_id='$user_id'"))['total'];
?>

<!DOCTYPE html>
<html>

<head>

<title>My Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f4f6f9;
}

.card{
    margin-top:60px;
    border:none;
    border-radius:15px;
    box-shadow:0 0 15px rgba(0,0,0,.1);
}

</style>

</head>

<body>

<div class="container">

<div class="row justify-content-center">

<div class="col-md-6">

<div class="card p-4">

<div class="text-center mb-4">

<img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
width="120"
height="120">

</div>

<hr>

<div class="list-group">

<div class="list-group-item">

<b>👤 Name</b>

<br>

<?php echo $user['name']; ?>

</div>

<div class="list-group-item">

<b>📧 Email</b>

<br>

<?php echo $user['email']; ?>

</div>

<div class="list-group-item">

<b>📊 Total Predictions</b>

<br>

<?php echo $total; ?>

</div>

</div>
<div class="d-grid mt-3">

<a href="dashboard.php" class="btn btn-primary">
Back to Dashboard
</a>
<br>

<a href="loan_form.php" class="btn btn-success w-100">

🏦 Apply New Loan

</a>

<br><br>

<a href="history.php" class="btn btn-info w-100">

📜 View Prediction History

</a>

</div>

</div>

</div>

</div>

</div>

</body>

</html>