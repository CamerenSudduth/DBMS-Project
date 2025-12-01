<?php
// Initialize variables
$pledges = [];
$message = "";
$searchPledgedID = "";
$searchDonorID = "";
$searchCircleName = "";

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
        $newDateOfPledge = trim($_POST['newDateOfPledge'] ?? '');
        $newNumPayments = trim($_POST['newNumPayments'] ?? '');
        $newPaymentMethod = trim($_POST['newPaymentMethod'] ?? '');
        $newCreditCard = trim($_POST['newCreditCard'] ?? '');
        $newDonorID = trim($_POST['newDonorID'] ?? '');
        $newEmployerID = trim($_POST['newEmployerID'] ?? '');
        $newCircleName = trim($_POST['newCircleName'] ?? '');

        // Validate numeric fields
        if (!is_numeric($newAmount) || $newAmount < 0) {
            $message = "Amount Pledged must be a non-negative number.";
        } elseif (!ctype_digit($newNumPayments)) {
            $message = "Number of Payments must be an integer.";
        } elseif (!ctype_digit($newDonorID)) {
            $message = "Donor ID must be an integer.";
        } elseif ($newEmployerID && !ctype_digit($newEmployerID)) {
            $message = "Employer ID must be an integer if provided.";
        } elseif (!$newDateOfPledge || !$newPaymentMethod) {
            $message = "Date of Pledge and Payment Method are required.";
        } else {
            // Foreign key checks
            $fkError = "";
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Donor WHERE DonorID = :donorID");
            $stmt->bindParam(':donorID', $newDonorID);
            $stmt->execute();
            if (!$stmt->fetchColumn()) $fkError = "Donor ID {$newDonorID} does not exist.";

            if ($newEmployerID) {
                $stmt = $conn->prepare("SELECT COUNT(*) FROM Employer WHERE EmployerID = :empID");
                $stmt->bindParam(':empID', $newEmployerID);
                $stmt->execute();
                if (!$stmt->fetchColumn()) $fkError = "Employer ID {$newEmployerID} does not exist.";
            }

            if ($newCircleName) {
                $stmt = $conn->prepare("SELECT COUNT(*) FROM DonorCircle WHERE circleName = :circle");
                $stmt->bindParam(':circle', $newCircleName);
                $stmt->execute();
                if (!$stmt->fetchColumn()) $fkError = "Circle Name '{$newCircleName}' does not exist.";
            }

            if ($fkError) {
                $message = "Cannot insert: $fkError";
            } else {
                // Insert pledge
                $stmt = $conn->prepare("INSERT INTO Pledge (amountPledged, dateOfPledge, numOfPayments, paymentMethod, creditCardNumber, DonorID, EmployerID, circleName) VALUES (:amount, :dateOfPledge, :numPayments, :paymentMethod, :creditCard, :donorID, :empID, :circle)");
                $stmt->bindParam(':amount', $newAmount);
                $stmt->bindParam(':dateOfPledge', $newDateOfPledge);
                $stmt->bindParam(':numPayments', $newNumPayments);
                $stmt->bindParam(':paymentMethod', $newPaymentMethod);
                $stmt->bindParam(':creditCard', $newCreditCard);
                $stmt->bindParam(':donorID', $newDonorID);
                $stmt->bindParam(':empID', $newEmployerID);
                $stmt->bindParam(':circle', $newCircleName);
                $stmt->execute();
                $message = "Pledge inserted successfully.";
            }
        }
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $deletePledgedID = trim($_POST['deletePledgedID'] ?? '');
        if (!ctype_digit($deletePledgedID)) {
            $message = "Pledged ID must be an integer.";
        } else {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Pledge WHERE PledgedID = :pid");
            $stmt->bindParam(':pid', $deletePledgedID);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                $message = "Pledged ID {$deletePledgedID} does not exist.";
            } else {
                $stmt = $conn->prepare("DELETE FROM Pledge WHERE PledgedID = :pid");
                $stmt->bindParam(':pid', $deletePledgedID);
                $stmt->execute();
                $message = "Pledge deleted successfully.";
            }
        }
    }

    // Handle search
    if (isset($_POST['searchPledgedID'])) $searchPledgedID = trim($_POST['searchPledgedID']);
    if (isset($_POST['searchDonorID'])) $searchDonorID = trim($_POST['searchDonorID']);
    if (isset($_POST['searchCircleName'])) $searchCircleName = trim($_POST['searchCircleName']);

    // Fetch all pledges
    $stmt = $conn->prepare("SELECT * FROM Pledge");
    $stmt->execute();
    $pledges = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Pledges</title>
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

<h1>Pledge Table</h1>
<p>The list of all pledges and their information.</p>

<?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<!-- Search Forms -->
<h3>Search Pledged ID</h3>
<form method="post">
    <input type="text" name="searchPledgedID" placeholder="Enter Pledged ID" value="<?php echo htmlspecialchars($searchPledgedID); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchPledgedID" value="">Show All</button>
</form>

<h3>Search by Donor ID</h3>
<form method="post">
    <input type="text" name="searchDonorID" placeholder="Enter Donor ID" value="<?php echo htmlspecialchars($searchDonorID); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchDonorID" value="">Show All</button>
</form>

<h3>Search by Circle Name</h3>
<form method="post">
    <input type="text" name="searchCircleName" placeholder="Enter Circle Name" value="<?php echo htmlspecialchars($searchCircleName); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchCircleName" value="">Show All</button>
</form>

<!-- Insert Form -->
<h3>Insert Pledge</h3>
<form method="post">
    <input type="number" name="newAmount" placeholder="Amount Pledged">
    <input type="date" name="newDateOfPledge" placeholder="Date of Pledge">
    <input type="number" name="newNumPayments" placeholder="Number of Payments">
    <input type="text" name="newPaymentMethod" placeholder="Payment Method">
    <input type="text" name="newCreditCard" placeholder="Credit Card Number">
    <input type="text" name="newDonorID" placeholder="Donor ID">
    <input type="text" name="newEmployerID" placeholder="Employer ID">
    <input type="text" name="newCircleName" placeholder="Circle Name">
    <input type="submit" name="insert" value="Insert">
</form>

<!-- Delete Form -->
<h3>Delete Pledge</h3>
<form method="post">
    <input type="text" name="deletePledgedID" placeholder="Pledged ID">
    <input type="submit" name="delete" value="Delete">
</form>

<!-- Pledges Table -->
<table>
    <tr>
        <th>Pledged ID</th>
        <th>Amount Pledged</th>
        <th>Date of Pledge</th>
        <th>Number of Payments</th>
        <th>Payment Method</th>
        <th>Credit Card Number</th>
        <th>Donor ID</th>
        <th>Employer ID</th>
        <th>Circle Name</th>
    </tr>
    <?php foreach ($pledges as $row):
        if ($searchPledgedID !== "" && strval($row['PledgedID']) !== strval($searchPledgedID)) continue;
        if ($searchDonorID !== "" && strval($row['DonorID']) !== strval($searchDonorID)) continue;
        if ($searchCircleName !== "" && stripos($row['circleName'], $searchCircleName) === false) continue;
    ?>
    <tr>
        <td><?php echo $row['PledgedID'] ?? 'NULL'; ?></td>
        <td><?php echo $row['amountPledged'] ?? 'NULL'; ?></td>
        <td><?php echo $row['dateOfPledge'] ?? 'NULL'; ?></td>
        <td><?php echo $row['numOfPayments'] ?? 'NULL'; ?></td>
        <td><?php echo $row['paymentMethod'] ?? 'NULL'; ?></td>
        <td><?php echo $row['creditCardNumber'] ?? 'NULL'; ?></td>
        <td><?php echo $row['DonorID'] ?? 'NULL'; ?></td>
        <td><?php echo $row['EmployerID'] ?? 'NULL'; ?></td>
        <td><?php echo $row['circleName'] ?? 'NULL'; ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<button onclick="window.location.href='index4.php'">Back to Main Menu</button>

</body>
</html>
