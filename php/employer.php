<?php
// Initialize variables
$employers = [];
$message = "";
$searchEmployerID = "";
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
        $newEmployerID = trim($_POST['newEmployerID'] ?? '');
        $newName = trim($_POST['newName'] ?? '');
        $newAddress = trim($_POST['newAddress'] ?? '');
        $newContactPerson = trim($_POST['newContactPerson'] ?? '');

        // Validate EmployerID is integer
        if (!ctype_digit($newEmployerID)) {
            $message = "EmployerID must be an integer.";
        } elseif ($newName === '') {
            $message = "Name cannot be empty.";
        } else {
            // Check if EmployerID already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Employer WHERE EmployerID = :employerID");
            $stmt->bindParam(':employerID', $newEmployerID);
            $stmt->execute();
            if ($stmt->fetchColumn()) {
                $message = "Employer with ID {$newEmployerID} already exists.";
            } else {
                // Insert new employer
                $stmt = $conn->prepare("INSERT INTO Employer (EmployerID, name, address, contactPerson) VALUES (:employerID, :name, :address, :contactPerson)");
                $stmt->bindParam(':employerID', $newEmployerID);
                $stmt->bindParam(':name', $newName);
                $stmt->bindParam(':address', $newAddress);
                $stmt->bindParam(':contactPerson', $newContactPerson);
                $stmt->execute();
                $message = "Employer inserted successfully.";
            }
        }
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $deleteEmployerID = trim($_POST['deleteEmployerID'] ?? '');
        if (!ctype_digit($deleteEmployerID)) {
            $message = "Please provide a valid EmployerID (integer) to delete.";
        } else {
            $stmt = $conn->prepare("DELETE FROM Employer WHERE EmployerID = :employerID");
            $stmt->bindParam(':employerID', $deleteEmployerID);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $message = "Employer deleted successfully.";
            } else {
                $message = "Employer with ID {$deleteEmployerID} does not exist.";
            }
        }
    }

    // Handle search
    if (isset($_POST['searchEmployerID']) || isset($_POST['searchName'])) {
        $searchEmployerID = trim($_POST['searchEmployerID'] ?? '');
        $searchName = trim($_POST['searchName'] ?? '');
    }

    // Fetch all employers
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
        input[type=text] { padding: 6px; font-size: 14px; margin: 2px; }
        input[type=submit], button { padding: 6px 12px; font-size: 14px; margin: 2px; }
        .message { color: green; font-weight: bold; margin-top: 10px; }
    </style>
</head>

<body>
    <h1>Employers Table</h1>
    <p>The list of employers and their information.</p>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Search Form -->
    <h3>Search Employer</h3>
    <form method="post">
        <input type="text" name="searchEmployerID" placeholder="Enter EmployerID" value="<?php echo htmlspecialchars($searchEmployerID); ?>">
        <input type="text" name="searchName" placeholder="Enter Name" value="<?php echo htmlspecialchars($searchName); ?>">
        <input type="submit" value="Search">
        <button type="submit" name="searchEmployerID" value="">Show All</button>
    </form>

    <!-- Insert Form -->
    <h3>Insert Employer</h3>
    <form method="post">
        <input type="text" name="newEmployerID" placeholder="EmployerID">
        <input type="text" name="newName" placeholder="Name">
        <input type="text" name="newAddress" placeholder="Address">
        <input type="text" name="newContactPerson" placeholder="Contact Person">
        <input type="submit" name="insert" value="Insert">
    </form>

    <!-- Delete Form -->
    <h3>Delete Employer</h3>
    <form method="post">
        <input type="text" name="deleteEmployerID" placeholder="EmployerID">
        <input type="submit" name="delete" value="Delete">
    </form>

    <!-- Employer Table -->
    <table>
        <tr>
            <th>Employer ID</th>
            <th>Name</th>
            <th>Address</th>
            <th>Contact Person</th>
        </tr>

        <?php foreach ($employers as $row):
            if ($searchEmployerID !== "" && $row['EmployerID'] != $searchEmployerID) continue;
            if ($searchName !== "" && stripos($row['name'], $searchName) === false) continue;
        ?>
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
