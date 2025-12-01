<?php
// Initialize variables
$payments = [];
$message = "";
$searchPaymentID = "";
$searchPledgeID = "";

$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar";
$password = "0121";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle insert
    if (isset($_POST['insert'])) {
        $newAmount = trim($_POST['newAmount'] ?? '');
        $newDateReceived = trim($_POST['newDateReceived'] ?? '');
        $newDueDate = trim($_POST['newDueDate'] ?? '');
        $newPledgeID = trim($_POST['newPledgeID'] ?? '');

        // Validate numeric amount and pledge ID
        if (!is_numeric($newAmount) || $newAmount < 0) {
            $message = "Amount must be a non-negative number.";
        } elseif (!ctype_digit($newPledgeID)) {
            $message = "Pledge ID must be an integer.";
        } elseif (!$newDateReceived || !$newDueDate) {
            $message = "Both Date Received and Due Date must be provided.";
        } else {
            // Check if pledge exists (foreign key)
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Pledge WHERE PledgeID = :pledgeID");
            $stmt->bindParam(':pledgeID', $newPledgeID);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                $message = "Cannot insert: Pledge ID {$newPledgeID} does not exist.";
            } else {
                // Insert new payment
                $stmt = $conn->prepare("INSERT INTO Payment (amount, dateReceived, dueDate, pledgeID) VALUES (:amount, :dateReceived, :dueDate, :pledgeID)");
                $stmt->bindParam(':amount', $newAmount);
                $stmt->bindParam(':dateReceived', $newDateReceived);
                $stmt->bindParam(':dueDate', $newDueDate);
                $stmt->bindParam(':pledgeID', $newPledgeID);
                $stmt->execute();
                $message = "Payment inserted successfully.";
            }
        }
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $deletePaymentID = trim($_POST['deletePaymentID'] ?? '');

        if (!ctype_digit($deletePaymentID)) {
            $message = "Payment ID must be an integer.";
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Payment WHERE PaymentID = :paymentID");
            $stmt->bindParam(':paymentID', $deletePaymentID);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                $message = "Payment ID {$deletePaymentID} does not exist.";
            } else {
                $stmt = $conn->prepare("DELETE FROM Payment WHERE PaymentID = :paymentID");
                $stmt->bindParam(':paymentID', $deletePaymentID);
                $stmt->execute();
                $message = "Payment deleted successfully.";
            }
        }
    }

    // Handle search
    if (isset($_POST['searchPaymentID'])) {
        $searchPaymentID = trim($_POST['searchPaymentID']);
    }
    if (isset($_POST['searchPledgeID'])) {
        $searchPledgeID = trim($_POST['searchPledgeID']);
    }

    // Fetch all payments
    $stmt = $conn->prepare("SELECT PaymentID, amount, dateReceived, dueDate, pledgeID FROM Payment");
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Payments</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #ccc; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        input[type=text], input[type=number], input[type=date] { padding: 6px; margin: 2px; }
        input[type=submit], button { padding: 6px 12px; margin: 2px; }
        .message { color: green; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>

<h1>Payment Table</h1>
<p>The list of payments and their information.</p>

<?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<!-- Search Payment ID Form -->
<h3>Search Payment ID</h3>
<form method="post">
    <input type="text" name="searchPaymentID" placeholder="Enter Payment ID" value="<?php echo htmlspecialchars($searchPaymentID); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchPaymentID" value="">Show All</button>
</form>

<!-- Search Pledge ID Form -->
<h3>Search by Pledge ID</h3>
<form method="post">
    <input type="text" name="searchPledgeID" placeholder="Enter Pledge ID" value="<?php echo htmlspecialchars($searchPledgeID); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchPledgeID" value="">Show All</button>
</form>

<!-- Insert Form -->
<h3>Insert Payment</h3>
<form method="post">
    <input type="number" name="newAmount" placeholder="Amount">
    <input type="date" name="newDateReceived" placeholder="Date Received">
    <input type="date" name="newDueDate" placeholder="Due Date">
    <input type="text" name="newPledgeID" placeholder="Pledge ID">
    <input type="submit" name="insert" value="Insert">
</form>

<!-- Delete Form -->
<h3>Delete Payment</h3>
<form method="post">
    <input type="text" name="deletePaymentID" placeholder="Payment ID">
    <input type="submit" name="delete" value="Delete">
</form>

<!-- Payments Table -->
<table>
    <tr>
        <th>Payment ID</th>
        <th>Amount</th>
        <th>Date Received</th>
        <th>Due Date</th>
        <th>Pledge ID</th>
    </tr>
    <?php foreach ($payments as $row): 
        if ($searchPaymentID !== "" && strval($row['PaymentID']) !== strval($searchPaymentID)) continue;
        if ($searchPledgeID !== "" && strval($row['pledgeID']) !== strval($searchPledgeID)) continue;
    ?>
    <tr>
        <td><?php echo $row['PaymentID'] ?? 'NULL'; ?></td>
        <td><?php echo $row['amount'] ?? 'NULL'; ?></td>
        <td><?php echo $row['dateReceived'] ?? 'NULL'; ?></td>
        <td><?php echo $row['dueDate'] ?? 'NULL'; ?></td>
        <td><?php echo $row['pledgeID'] ?? 'NULL'; ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<button onclick="window.location.href='index4.php'">Back to Main Menu</button>

</body>
</html>
