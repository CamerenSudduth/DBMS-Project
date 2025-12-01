<?php
// Initialize variables
$events = [];
$message = "";
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
        $newEventName = trim($_POST['newEventName'] ?? '');
        $newEventDate = trim($_POST['newEventDate'] ?? '');

        // Validate inputs
        if ($newEventName === '') {
            $message = "Event Name cannot be empty.";
        } elseif ($newEventDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $newEventDate)) {
            $message = "Event Date must be provided in YYYY-MM-DD format.";
        } else {
            // Check if event already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM DonorEvent WHERE eventName = :eventName");
            $stmt->bindParam(':eventName', $newEventName);
            $stmt->execute();
            if ($stmt->fetchColumn()) {
                $message = "Event '{$newEventName}' already exists.";
            } else {
                // Insert new event
                $stmt = $conn->prepare("INSERT INTO DonorEvent (eventName, eventDate) VALUES (:eventName, :eventDate)");
                $stmt->bindParam(':eventName', $newEventName);
                $stmt->bindParam(':eventDate', $newEventDate);
                $stmt->execute();
                $message = "Donor Event inserted successfully.";
            }
        }
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $deleteEventName = trim($_POST['deleteEventName'] ?? '');
        if ($deleteEventName === '') {
            $message = "Please provide an Event Name to delete.";
        } else {
            $stmt = $conn->prepare("DELETE FROM DonorEvent WHERE eventName = :eventName");
            $stmt->bindParam(':eventName', $deleteEventName);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $message = "Donor Event deleted successfully.";
            } else {
                $message = "Event '{$deleteEventName}' does not exist.";
            }
        }
    }

    // Handle search
    if (isset($_POST['searchEventName'])) {
        $searchEventName = trim($_POST['searchEventName']);
    }

    // Fetch all events
    $stmt = $conn->prepare("SELECT * FROM DonorEvent");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Donor Events</title>

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
        input[type=text] { padding: 6px; font-size: 14px; margin: 2px; }
        input[type=submit], button { padding: 6px 12px; font-size: 14px; margin: 2px; }
        .message { color: green; font-weight: bold; margin-top: 10px; }
    </style>
</head>

<body>
    <h1>DonorEvent Table</h1>
    <p>The list of donor events and their dates.</p>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Search Form -->
    <h3>Search by Event Name</h3>
    <form method="post">
        <input type="text" name="searchEventName" placeholder="Enter Event Name" value="<?php echo htmlspecialchars($searchEventName); ?>">
        <input type="submit" value="Search">
        <button type="submit" name="searchEventName" value="">Show All</button>
    </form>

    <!-- Insert Form -->
    <h3>Insert Donor Event</h3>
    <form method="post">
        <input type="text" name="newEventName" placeholder="Event Name">
        <input type="text" name="newEventDate" placeholder="YYYY-MM-DD">
        <input type="submit" name="insert" value="Insert">
    </form>

    <!-- Delete Form -->
    <h3>Delete Donor Event</h3>
    <form method="post">
        <input type="text" name="deleteEventName" placeholder="Event Name">
        <input type="submit" name="delete" value="Delete">
    </form>

    <!-- Donor Events Table -->
    <table>
        <tr>
            <th>Event Name</th>
            <th>Event Date</th>
        </tr>

        <?php foreach ($events as $row):
            if ($searchEventName !== "" && stripos($row['eventName'], $searchEventName) === false) continue;
        ?>
            <tr>
                <td><?php echo $row['eventName'] ?? 'NULL'; ?></td>
                <td><?php echo $row['eventDate'] ?? 'NULL'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <button onclick="window.location.href='index4.php'">Back to Main Menu</button>
</body>
</html>
