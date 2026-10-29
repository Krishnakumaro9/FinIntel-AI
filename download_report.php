<?php

session_start();
include 'db.php';

if(!isset($_SESSION['id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['id'];


/* =========================
   CSV DOWNLOAD SETTINGS
========================= */

header('Content-Type: text/csv; charset=utf-8');

header(
    'Content-Disposition: attachment; filename=FinIntel_AI_Loan_Report.csv'
);


/* =========================
   CREATE CSV OUTPUT
========================= */

$output = fopen('php://output', 'w');


/* =========================
   CSV HEADINGS
========================= */

fputcsv($output, [

    'ID',
    'Loan Type',
    'Monthly Income',
    'Credit Score',
    'Employment Type',
    'Loan Amount',
    'Loan Tenure',
    'Age',
    'Prediction'

]);


/* =========================
   GET USER DATA
========================= */

$query = mysqli_query(
    $conn,
    "SELECT * FROM loan_details
     WHERE user_id='$user_id'
     ORDER BY id DESC"
);


/* =========================
   ADD DATA TO CSV
========================= */

while($row = mysqli_fetch_assoc($query)){

    fputcsv($output, [

        $row['id'],
        $row['loan_type'],
        $row['income'],
        $row['credit_score'],
        $row['employment_type'],
        $row['loan_amount'],
        $row['tenure'],
        $row['age'],
        $row['prediction']

    ]);

}


/* =========================
   CLOSE FILE
========================= */

fclose($output);

exit();

?>