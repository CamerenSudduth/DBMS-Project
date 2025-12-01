<?php
// Initialize variables
$class = [];

// Database connection
$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar"; // Replace with your MySQL username
$password = "0121"; // Replace with your MySQL password

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("SELECT * FROM ClassYear");
    $stmt->execute();
    $class = $stmt->fetchAll();

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Class Years</title>

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
    <h1>ClassYear Table</h1>
    <p1> The list of all class years.</p1>
    <table>
        <tr>
            <th>Class Year</th>
        </tr>

        <?php foreach ($class as $row): ?>
            <tr>
                <td><?php echo $row['year'] ?? 'NULL'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
	
	<!-- Add a Back button -->
    <button onclick="window.location.href='index4.php'">Back to Main Menu</button>
</body>
</html>
