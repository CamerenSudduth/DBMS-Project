<?php
// Initialize variables
$donors = [];
$message = "";
$searchDonorID = "";
$searchName = "";

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
        $newName = trim($_POST['newName'] ?? '');
        $newAddress = trim($_POST['newAddress'] ?? '');
        $newPhone = trim($_POST['newPhone'] ?? '');
        $newEmail = trim($_POST['newEmail'] ?? '');
        $newCategory = trim($_POST['newCategory'] ?? '');
        $newJob = trim($_POST['newJob'] ?? '');
        $newLastYearDonation = trim($_POST['newLastYearDonation'] ?? '');
        $newCurrentYearDonation = trim($_POST['newCurrentYearDonation'] ?? '');
        $newCoordinatorYear = trim($_POST['newCoordinatorYear'] ?? '');

        // Validate DonorID is integer
        if (!ctype_digit($newDonorID)) {
            $message = "DonorID must be an integer.";
        } elseif ($newName === '') {
            $message = "Name cannot be empty.";
        } else {
            // Foreign key check: coordinatorYear exists in ClassYear
            $stmt = $conn->prepare("SELECT COUNT(*) FROM ClassYear WHERE year = :year");
            $stmt->bindParam(':year', $newCoordinatorYear);
            $stmt->execute();
            if ($newCoordinatorYear !== '' && !$stmt->fetchColumn()) {
                $message = "Cannot insert: Coordinator Year '{$newCoordinatorYear}' does not exist in ClassYear table.";
            } else {
                // Check if DonorID already exists
                $stmt = $conn->prepare("SELECT COUNT(*) FROM Donor WHERE DonorID = :donorID");
                $stmt->bindParam(':donorID', $newDonorID);
                $stmt->execute();
                if ($stmt->fetchColumn()) {
                    $message = "DonorID {$newDonorID} already exists.";
                } else {
                    // Insert new donor
                    $stmt = $conn->prepare("INSERT INTO Donor (DonorID, name, address, phone, email, category, job, lastYearDonation, currentYearDonation, coordinatorYear) VALUES (:donorID, :name, :address, :phone, :email, :category, :job, :lastYearDonation, :currentYearDonation, :coordinatorYear)");
                    $stmt->bindParam(':donorID', $newDonorID);
                    $stmt->bindParam(':name', $newName);
                    $stmt->bindParam(':address', $newAddress);
                    $stmt->bindParam(':phone', $newPhone);
                    $stmt->bindParam(':email', $newEmail);
                    $stmt->bindParam(':category', $newCategory);
                    $stmt->bindParam(':job', $newJob);
                    $stmt->bindParam(':lastYearDonation', $newLastYearDonation);
                    $stmt->bindParam(':currentYearDonation', $newCurrentYearDonation);
                    $stmt->bindParam(':coordinatorYear', $newCoordinatorYear);
                    $stmt->execute();
                    $message = "Donor inserted successfully.";
                }
            }
        }
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $deleteDonorID = trim($_POST['deleteDonorID'] ?? '');
        if (!ctype_digit($deleteDonorID)) {
            $message = "Please provide a valid DonorID (integer) to delete.";
        } else {
            $stmt = $conn->prepare("DELETE FROM Donor WHERE DonorID = :donorID");
            $stmt->bindParam(':donorID', $deleteDonorID);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $message = "Donor deleted successfully.";
            } else {
                $message = "DonorID {$deleteDonorID} does not exist.";
            }
        }
    }

    // Handle search
    if (isset($_POST['searchDonorID']) || isset($_POST['searchName'])) {
        $searchDonorID = trim($_POST['searchDonorID']);
        $searchName = trim($_POST['searchName'] ?? '');
    }

    // Fetch all donors
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
        input[type=text] { padding: 6px; font-size: 14px; margin: 2px; }
        input[type=submit], button { padding: 6px 12px; font-size: 14px; margin: 2px; }
        .message { color: green; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>

<h1>Donor Table</h1>
<p>The list of all donors and their information.</p>

<?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<!-- Search Form -->
<h3>Search Donor ID</h3>
<form method="post">
    <input type="text" name="searchDonorID" placeholder="Enter DonorID" value="<?php echo htmlspecialchars($searchDonorID); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchDonorID" value="">Show All</button>
</form>

<h3>Search by Name</h3>
<form method="post">
    <input type="text" name="searchName" placeholder="Enter Name" value="<?php echo htmlspecialchars($searchName); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchName" value="">Show All</button>
</form>

<!-- Insert Form -->
<h3>Insert Donor</h3>
<form method="post">
    <input type="text" name="newDonorID" placeholder="DonorID">
    <input type="text" name="newName" placeholder="Name">
    <input type="text" name="newAddress" placeholder="Address">
    <input type="text" name="newPhone" placeholder="Phone">
    <input type="text" name="newEmail" placeholder="Email">
    <input type="text" name="newCategory" placeholder="Category">
    <input type="text" name="newJob" placeholder="Job">
    <input type="text" name="newLastYearDonation" placeholder="Last Year Donation">
    <input type="text" name="newCurrentYearDonation" placeholder="Current Year Donation">
    <input type="text" name="newCoordinatorYear" placeholder="Coordinator Year">
    <input type="submit" name="insert" value="Insert">
</form>

<!-- Delete Form -->
<h3>Delete Donor</h3>
<form method="post">
    <input type="text" name="deleteDonorID" placeholder="DonorID">
    <input type="submit" name="delete" value="Delete">
</form>

<!-- Donors Table -->
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
    <?php foreach ($donors as $row):
        if ($searchDonorID !== "" && $row['DonorID'] != $searchDonorID) continue;
        if ($searchName !== "" && stripos($row['name'], $searchName) === false) continue;
    ?>
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

<button onclick="window.location.href='index4.php'">Back to Main Menu</button>

</body>
</html>
