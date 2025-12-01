<?php
// Initialize variables
$payments = [];

// Database connection
$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar"; // Replace with your MySQL username
$password = "0121"; // Replace with your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM Payment");
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Payments</title>

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
    <h1>Payment Table</h1>
    <p>The list of payments and their information.</p>

    <table>
        <tr>
            <th>Payment ID</th>
            <th>Amount</th>
            <th>Date Received</th>
            <th>Due Date</th>
            <th>Pledge ID</th>
        </tr>

        <?php foreach ($payments as $row): ?>
            <tr>
                <td><?php echo $row['PaymentID'] ?? 'NULL'; ?></td>
                <td><?php echo $row['amount'] ?? 'NULL'; ?></td>
                <td><?php echo $row['dateReceived'] ?? 'NULL'; ?></td>
                <td><?php echo $row['dueDate'] ?? 'NULL'; ?></td>
                <td><?php echo $row['pledge'] ?? 'NULL'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <button onclick="window.location.href='index4.php'">Back to Main Menu</button>
</body>
</html>
