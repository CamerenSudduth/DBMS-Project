<?php
// Initialize variables
$pledges = [];

// Database connection
$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar"; 
$password = "0121"; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM Pledge");
    $stmt->execute();
    $pledges = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Pledges</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h1>Pledge Table</h1>
<p>The list of all pledges and their information.</p>

<table>
    <tr>
        <th>Pledged ID</th>
        <th>Amount Pledged</th>
        <th>Date of Pledge</th>
        <th>Number of Payments</th>
        <th>Payment Method</th>
        <th>Credit Card Number</th>
        <th>Donor ID</th>
        <th>Employer ID</th>
        <th>Circle Name</th>
    </tr>

    <?php foreach ($pledges as $row): ?>
        <tr>
            <td><?php echo $row['PledgedID'] ?? 'NULL'; ?></td>
            <td><?php echo $row['amountPledged'] ?? 'NULL'; ?></td>
            <td><?php echo $row['dateOfPledge'] ?? 'NULL'; ?></td>
            <td><?php echo $row['numOfPayments'] ?? 'NULL'; ?></td>
            <td><?php echo $row['paymentMethod'] ?? 'NULL'; ?></td>
            <td><?php echo $row['creditCardNumber'] ?? 'NULL'; ?></td>
            <td><?php echo $row['DonorID'] ?? 'NULL'; ?></td>
            <td><?php echo $row['EmployerID'] ?? 'NULL'; ?></td>
            <td><?php echo $row['circleName'] ?? 'NULL'; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<button onclick="window.location.href='index4.php'">Back to Main Menu</button>

</body>
</html>
