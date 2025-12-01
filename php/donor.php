<?php
// Initialize variables
$donors = [];

// Database connection
$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar"; 
$password = "0121"; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM Donor");
    $stmt->execute();
    $donors = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>All Donors</title>
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

<h1>Donor Table</h1>
<p>The list of all donors and their information.</p>

<table>
    <tr>
        <th>Donor ID</th>
        <th>Name</th>
        <th>Address</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Category</th>
        <th>Job</th>
        <th>Last Year Donation</th>
        <th>Current Year Donation</th>
        <th>Coordinator Year</th>
    </tr>

    <?php foreach ($donors as $row): ?>
        <tr>
            <td><?php echo $row['DonorID'] ?? 'NULL'; ?></td>
            <td><?php echo $row['name'] ?? 'NULL'; ?></td>
            <td><?php echo $row['address'] ?? 'NULL'; ?></td>
            <td><?php echo $row['phone'] ?? 'NULL'; ?></td>
            <td><?php echo $row['email'] ?? 'NULL'; ?></td>
            <td><?php echo $row['category'] ?? 'NULL'; ?></td>
            <td><?php echo $row['job'] ?? 'NULL'; ?></td>
            <td><?php echo $row['lastYearDonation'] ?? 'NULL'; ?></td>
            <td><?php echo $row['currentYearDonation'] ?? 'NULL'; ?></td>
            <td><?php echo $row['coordinatorYear'] ?? 'NULL'; ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<br>
<button onclick="window.location.href='index4.php'">Back to Main Menu</button>

</body>
</html>
