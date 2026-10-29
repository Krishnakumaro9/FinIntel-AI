<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/db.php';

if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (int) $_SESSION['id'];

$sql = "SELECT id, loan_type, income, credit_score, employment_type,
               loan_amount, tenure, age, prediction, created_at
        FROM loan_details
        WHERE user_id = ?
        ORDER BY id DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Query preparation failed: " . htmlspecialchars(mysqli_error($conn)));
}

mysqli_stmt_bind_param($stmt, "i", $user_id);

if (!mysqli_stmt_execute($stmt)) {
    die("Query execution failed: " . htmlspecialchars(mysqli_stmt_error($stmt)));
}

$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan History | FinIntel AI</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 16px;
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1e293b;
        }

        .container {
            max-width: 1200px;
            margin: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 25px;
        }

        h1 {
            margin: 0 0 8px;
            color: #172554;
        }

        .subtitle {
            margin: 0;
            color: #64748b;
        }

        .back-button {
            display: inline-block;
            padding: 11px 18px;
            background: #1d4ed8;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .card {
            background: white;
            padding: 22px;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, 0.07);
            overflow-x: auto;
        }

        .count {
            margin-bottom: 18px;
            color: #475569;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1000px;
        }

        th, td {
            padding: 14px 12px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #eff6ff;
            color: #1e3a8a;
            font-size: 13px;
        }

        td {
            font-size: 14px;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .eligible {
            background: #dcfce7;
            color: #166534;
        }

        .not-eligible {
            background: #fee2e2;
            color: #991b1b;
        }

        .empty {
            padding: 30px;
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 600px) {
            body {
                padding: 18px 10px;
            }

            .card {
                padding: 12px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header">
        <div>
            <h1>Loan Application History</h1>
            <p class="subtitle">View your previously submitted loan applications.</p>
        </div>

        <a class="back-button" href="dashboard.php">Back to Dashboard</a>
    </div>

    <div class="card">

        <p class="count">
            Total applications:
            <strong><?php echo mysqli_num_rows($result); ?></strong>
        </p>

        <?php if (mysqli_num_rows($result) > 0): ?>

            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Loan Type</th>
                    <th>Monthly/Declared Income</th>
                    <th>Credit Score</th>
                    <th>Employment</th>
                    <th>Loan Amount</th>
                    <th>Tenure (Months)</th>
                    <th>Age</th>
                    <th>Prediction</th>
                    <th>Applied On</th>
                </tr>
                </thead>

                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo (int) $row['id']; ?></td>

                        <td>
                            <?php echo htmlspecialchars($row['loan_type'] ?? ''); ?>
                        </td>

                        <td>
                            ₹<?php echo number_format((float) $row['income'], 2); ?>
                        </td>

                        <td>
                            <?php echo (int) $row['credit_score']; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['employment_type'] ?? ''); ?>
                        </td>

                        <td>
                            ₹<?php echo number_format((float) $row['loan_amount'], 2); ?>
                        </td>

                        <td><?php echo (int) $row['tenure']; ?></td>

                        <td><?php echo (int) $row['age']; ?></td>

                        <td>
                            <?php
                            $prediction = $row['prediction'] ?? '';
                            $isEligible = strcasecmp(trim($prediction), 'Eligible') === 0;
                            ?>
                            <span class="badge <?php echo $isEligible ? 'eligible' : 'not-eligible'; ?>">
                                <?php echo htmlspecialchars($prediction); ?>
                            </span>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($row['created_at'] ?? ''); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>

            <div class="empty">
                No loan applications were found for your account.
            </div>

        <?php endif; ?>

    </div>
</div>
</body>
</html>

<?php
mysqli_stmt_close($stmt);
?>