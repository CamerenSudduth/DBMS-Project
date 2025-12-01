<?php
// Initialize variables
$classifications = [];

// Database connection
$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar"; // Replace with your MySQL username
$password = "0121"; // Replace with your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM ClassifiedBy");
    $stmt->execute();
    $classifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Donor Classifications</title>

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
    <h1>ClassifiedBy Table</h1>
    <p>The list of donors and their classifications.</p>

    <table>
        <tr>
            <th>DonorID</th>
            <th>Year</th>
            <th>Role</th>
        </tr>

        <?php foreach ($classifications as $row): ?>
            <tr>
                <td><?php echo $row['DonorID'] ?? 'NULL'; ?></td>
                <td><?php echo $row['year'] ?? 'NULL'; ?></td>
                <td><?php echo $row['role'] ?? 'NULL'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <button onclick="window.location.href='index4.php'">Back to Main Menu</button>
</body>
</html>
