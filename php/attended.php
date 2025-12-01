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
</head>
<body>
    <h1>Attended Table</h1>
    <p1> The list of attendees for each event.</p1>
    <ul>
	
        <?php foreach($attendees as $row): ?>
            <li><?php echo 'DonorID: ' . $row['DonorID'] . ' | Event: ' . $row['eventName']; ?></li>
        <?php endforeach; ?>
    </ul>
	
	<!-- Add a Back button -->
    <button onclick="window.location.href='index4.php'">Back to Main Menu</button>
</body>
</html>
