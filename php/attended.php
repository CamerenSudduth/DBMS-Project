<?php
// Initialize variables
$attendees = [];
$message = "";
$searchDonorID = "";
$searchEventName = "";

$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar";
$password = "0121";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle insert
    if (isset($_POST['insert'])) {
        $newDonorID = trim($_POST['newDonorID'] ?? '');
        $newEventName = trim($_POST['newEventName'] ?? '');

        // Validate DonorID is integer
        if (!ctype_digit($newDonorID)) {
            $message = "DonorID must be an integer.";
        } elseif ($newEventName === '') {
            $message = "Event Name cannot be empty.";
        } else {
            // Check if event exists in DonorEvent
            $stmt = $conn->prepare("SELECT COUNT(*) FROM DonorEvent WHERE eventName = :eventName");
            $stmt->bindParam(':eventName', $newEventName);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                $message = "Cannot insert: Event Name '{$newEventName}' does not exist in DonorEvent table.";
            } else {
                // Check if combination already exists
                $stmt = $conn->prepare("SELECT COUNT(*) FROM Attended WHERE DonorID = :donorID AND eventName = :eventName");
                $stmt->bindParam(':donorID', $newDonorID);
                $stmt->bindParam(':eventName', $newEventName);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    $message = "This attendee with DonorID {$newDonorID} and Event Name '{$newEventName}' already exists.";
                } else {
                    // Insert new row
                    $stmt = $conn->prepare("INSERT INTO Attended (DonorID, eventName) VALUES (:donorID, :eventName)");
                    $stmt->bindParam(':donorID', $newDonorID);
                    $stmt->bindParam(':eventName', $newEventName);
                    $stmt->execute();
                    $message = "Attendee inserted successfully.";
                }
            }
        }
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $deleteDonorID = trim($_POST['deleteDonorID'] ?? '');
        $deleteEventName = trim($_POST['deleteEventName'] ?? '');

        if (!ctype_digit($deleteDonorID) || $deleteEventName === '') {
            $message = "Please provide a valid DonorID (integer) and Event Name to delete.";
        } else {
            $stmt = $conn->prepare("DELETE FROM Attended WHERE DonorID = :donorID AND eventName = :eventName");
            $stmt->bindParam(':donorID', $deleteDonorID);
            $stmt->bindParam(':eventName', $deleteEventName);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $message = "Attendee deleted successfully.";
            } else {
                $message = "Cannot delete: This attendee does not exist.";
            }
        }
    }

    // Handle search
    if (isset($_POST['searchDonorID']) || isset($_POST['searchEventName'])) {
        $searchDonorID = trim($_POST['searchDonorID'] ?? '');
        $searchEventName = trim($_POST['searchEventName'] ?? '');
    }

    // Fetch all attendees
    $stmt = $conn->prepare("SELECT * FROM Attended");
    $stmt->execute();
    $attendees = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Attendees</title>
<style>
    body { font-family: Arial, sans-serif; margin: 40px; }
    table { width: 60%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; font-weight: bold; }
    input[type=text] { padding: 6px; font-size: 14px; margin: 2px; }
    input[type=submit], button { padding: 6px 12px; font-size: 14px; margin: 2px; }
    .message { color: green; font-weight: bold; margin-top: 10px; }
</style>
</head>
<body>

<h1>Attended Table</h1>
<p>The list of attendees for each event.</p>

<?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<!-- Search Donor ID Form -->
<h3>Search Donor ID</h3>
<form method="post">
    <input type="text" name="searchDonorID" id="searchDonorID" placeholder="Enter DonorID" value="<?php echo htmlspecialchars($searchDonorID); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchDonorID" value="">Show All</button>
</form>

<!-- Search Event Name Form -->
<h3>Search by Event Name</h3>
<form method="post">
    <input type="text" name="searchEventName" id="searchEventName" placeholder="Enter Event Name" value="<?php echo htmlspecialchars($searchEventName); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchEventName" value="">Show All</button>
</form>

<!-- Insert Form -->
<h3>Insert Attendee</h3>
<form method="post">
    <input type="text" name="newDonorID" placeholder="DonorID">
    <input type="text" name="newEventName" placeholder="Event Name">
    <input type="submit" name="insert" value="Insert">
</form>

<!-- Delete Form -->
<h3>Delete Attendee</h3>
<form method="post">
    <input type="text" name="deleteDonorID" placeholder="DonorID">
    <input type="text" name="deleteEventName" placeholder="Event Name">
    <input type="submit" name="delete" value="Delete">
</form>

<!-- Attendees Table -->
<table>
    <tr>
        <th>DonorID</th>
        <th>Event Name</th>
    </tr>
    <?php foreach ($attendees as $row): 
        // Apply search filters if set
        if ($searchDonorID !== "" && $row['DonorID'] != $searchDonorID) continue;
        if ($searchEventName !== "" && stripos($row['eventName'], $searchEventName) === false) continue;
    ?>
    <tr>
        <td><?php echo $row['DonorID'] ?? 'NULL'; ?></td>
        <td><?php echo $row['eventName'] ?? 'NULL'; ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<button onclick="window.location.href='index4.php'">Back to Main Menu</button>

</body>
</html>
