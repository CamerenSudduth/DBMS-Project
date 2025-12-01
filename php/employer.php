<?php
// Initialize variables
$employers = [];

// Database connection
$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar"; // Replace with your MySQL username
$password = "0121"; // Replace with your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM Employer");
    $stmt->execute();
    $employers = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Employer</title>

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
    <h1>Employers Table</h1>
    <p>The list of employers and their information.</p>

    <table>
        <tr>
            <th>Employer ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Contact Person</th>
        </tr>

        <?php foreach ($employers as $row): ?>
            <tr>
                <td><?php echo $row['EmployerID'] ?? 'NULL'; ?></td>
                <td><?php echo $row['name'] ?? 'NULL'; ?></td>
                <td><?php echo $row['address'] ?? 'NULL'; ?></td>
                <td><?php echo $row['contactPerson'] ?? 'NULL'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <button onclick="window.location.href='index4.php'">Back to Main Menu</button>
</body>
</html>
