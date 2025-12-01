<?php
// Initialize variables
$attendees = [];

// Database connection
$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar"; // Replace with your MySQL username
$password = "0121"; // Replace with your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM Attended");
    $stmt->execute();
    $attendees = $stmt->fetchAll();

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Attendees</title>

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
    <h1>Attended Table</h1>
    <p1> The list of attendees for each event.</p1>
    <table>
        <tr>
            <th>DonorID</th>
            <th>Event Name</th>
        </tr>

        <?php foreach ($attendees as $row): ?>
            <tr>
                <td><?php echo $row['DonorID'] ?? 'NULL'; ?></td>
                <td><?php echo $row['eventName'] ?? 'NULL'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
	
	<!-- Add a Back button -->
    <button onclick="window.location.href='index4.php'">Back to Main Menu</button>
</body>
</html>
