<?php
// Initialize variables
$classifications = [];
$message = "";
$searchDonorID = "";
$searchYear = "";
$searchRole = "";

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
        $newYear = trim($_POST['newYear'] ?? '');
        $newRole = trim($_POST['newRole'] ?? '');

        // Validate DonorID and Year are integers, Role non-empty
        if (!ctype_digit($newDonorID)) {
            $message = "DonorID must be an integer.";
        } elseif (!ctype_digit($newYear)) {
            $message = "Year must be an integer.";
        } elseif ($newRole === '') {
            $message = "Role cannot be empty.";
        } else {
            // Check if DonorID exists in Donor table
            $stmt = $conn->prepare("SELECT COUNT(*) FROM Donor WHERE DonorID = :donorID");
            $stmt->bindParam(':donorID', $newDonorID);
            $stmt->execute();
            if (!$stmt->fetchColumn()) {
                $message = "Cannot insert: DonorID '{$newDonorID}' does not exist in Donor table.";
            } else {
                // Check if combination already exists
                $stmt = $conn->prepare("SELECT COUNT(*) FROM ClassifiedBy WHERE DonorID = :donorID AND year = :year AND role = :role");
                $stmt->bindParam(':donorID', $newDonorID);
                $stmt->bindParam(':year', $newYear);
                $stmt->bindParam(':role', $newRole);
                $stmt->execute();
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    $message = "This classification with DonorID {$newDonorID}, Year '{$newYear}' and Role '{$newRole}' already exists.";
                } else {
                    // Insert new row
                    $stmt = $conn->prepare("INSERT INTO ClassifiedBy (DonorID, year, role) VALUES (:donorID, :year, :role)");
                    $stmt->bindParam(':donorID', $newDonorID);
                    $stmt->bindParam(':year', $newYear);
                    $stmt->bindParam(':role', $newRole);
                    $stmt->execute();
                    $message = "Classification inserted successfully.";
                }
            }
        }
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $deleteDonorID = trim($_POST['deleteDonorID'] ?? '');
        $deleteYear = trim($_POST['deleteYear'] ?? '');
        $deleteRole = trim($_POST['deleteRole'] ?? '');

        if (!ctype_digit($deleteDonorID) || !ctype_digit($deleteYear) || $deleteRole === '') {
            $message = "Please provide valid DonorID, Year (integers) and Role to delete.";
        } else {
            $stmt = $conn->prepare("DELETE FROM ClassifiedBy WHERE DonorID = :donorID AND year = :year AND role = :role");
            $stmt->bindParam(':donorID', $deleteDonorID);
            $stmt->bindParam(':year', $deleteYear);
            $stmt->bindParam(':role', $deleteRole);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $message = "Classification deleted successfully.";
            } else {
                $message = "Cannot delete: This classification does not exist.";
            }
        }
    }

    // Handle search
    if (isset($_POST['searchDonorID']) || isset($_POST['searchYear']) || isset($_POST['searchRole'])) {
        $searchDonorID = trim($_POST['searchDonorID'] ?? '');
        $searchYear = trim($_POST['searchYear'] ?? '');
        $searchRole = trim($_POST['searchRole'] ?? '');
    }

    // Fetch all classifications
    $stmt = $conn->prepare("SELECT * FROM ClassifiedBy");
    $stmt->execute();
    $classifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All Classifications</title>
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

<h1>ClassifiedBy Table</h1>
<p>The list of donors and their classifications.</p>

<?php if ($message): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<!-- Search Donor ID Form -->
<h3>Search Donor ID</h3>
<form method="post">
    <input type="text" name="searchDonorID" placeholder="Enter DonorID" value="<?php echo htmlspecialchars($searchDonorID); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchDonorID" value="">Show All</button>
</form>

<!-- Search Year Form -->
<h3>Search Year</h3>
<form method="post">
    <input type="text" name="searchYear" placeholder="Enter Year" value="<?php echo htmlspecialchars($searchYear); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchYear" value="">Show All</button>
</form>

<!-- Search Role Form -->
<h3>Search by Role</h3>
<form method="post">
    <input type="text" name="searchRole" placeholder="Enter Role" value="<?php echo htmlspecialchars($searchRole); ?>">
    <input type="submit" value="Search">
    <button type="submit" name="searchRole" value="">Show All</button>
</form>

<!-- Insert Form -->
<h3>Insert Classification</h3>
<form method="post">
    <input type="text" name="newDonorID" placeholder="DonorID">
    <input type="text" name="newYear" placeholder="Year">
    <input type="text" name="newRole" placeholder="Role">
    <input type="submit" name="insert" value="Insert">
</form>

<!-- Delete Form -->
<h3>Delete Classification</h3>
<form method="post">
    <input type="text" name="deleteDonorID" placeholder="DonorID">
    <input type="text" name="deleteYear" placeholder="Year">
    <input type="text" name="deleteRole" placeholder="Role">
    <input type="submit" name="delete" value="Delete">
</form>

<!-- Classifications Table -->
<table>
    <tr>
        <th>DonorID</th>
        <th>Year</th>
        <th>Role</th>
    </tr>
    <?php foreach ($classifications as $row): 
        // Apply search filters if set
        if ($searchDonorID !== "" && $row['DonorID'] != $searchDonorID) continue;
        if ($searchYear !== "" && $row['year'] != $searchYear) continue;
        if ($searchRole !== "" && stripos($row['role'], $searchRole) === false) continue;
    ?>
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
