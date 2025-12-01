<?php
// Initialize variables
$class = [];
$message = "";
$searchYear = "";

$host = "localhost";
$dbname = "Alpha_University";
$username = "dcesar";
$password = "0121";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle insert
    if (isset($_POST['insert'])) {
        $newYear = trim($_POST['newYear'] ?? '');

        // Validate year is integer
        if (!ctype_digit($newYear)) {
            $message = "Class Year must be an integer.";
        } else {
            // Check if year already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM ClassYear WHERE year = :year");
            $stmt->bindParam(':year', $newYear);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                $message = "Class Year {$newYear} already exists.";
            } else {
                // Insert new year
                $stmt = $conn->prepare("INSERT INTO ClassYear (year) VALUES (:year)");
                $stmt->bindParam(':year', $newYear);
                $stmt->execute();
                $message = "Class Year inserted successfully.";
            }
        }
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $deleteYear = trim($_POST['deleteYear'] ?? '');

        if (!ctype_digit($deleteYear)) {
            $message = "Please provide a valid integer year to delete.";
        } else {
            // Check foreign key constraint: is there any donor linked to this year?
            $stmtFK = $conn->prepare("SELECT COUNT(*) FROM Donor WHERE classYear = :year");
            $stmtFK->bindParam(':year', $deleteYear);
            $stmtFK->execute();
            $fkCount = $stmtFK->fetchColumn();

            if ($fkCount > 0) {
                $message = "Cannot delete Class Year {$deleteYear}: it is linked to existing donors.";
            } else {
                $stmt = $conn->prepare("DELETE FROM ClassYear WHERE year = :year");
                $stmt->bindParam(':year', $deleteYear);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $message = "Class Year deleted successfully.";
                } else {
                    $message = "Cannot delete: Class Year {$deleteYear} does not exist.";
                }
            }
        }
    }

    // Handle search
    if (isset($_POST['searchYear'])) {
        $searchYear = trim($_POST['searchYear']);
    }

    // Fetch all class years
    $stmt = $conn->prepare("SELECT * FROM ClassYear");
    $stmt->execute();
    $class = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Class Years</title>

    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { width: 40%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        input[type=text] { padding: 6px; font-size: 14px; margin: 2px; }
        input[type=submit], button { padding: 6px 12px; font-size: 14px; margin: 2px; }
        .message { color: green; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <h1>ClassYear Table</h1>
    <p1> The list of all class years.</p1>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Search Form -->
    <h3>Search Class Year</h3>
    <form method="post">
        <input type="text" name="searchYear" placeholder="Enter Class Year" value="<?php echo htmlspecialchars($searchYear); ?>">
        <input type="submit" value="Search">
        <button type="submit" name="searchYear" value="">Show All</button>
    </form>

    <!-- Insert Form -->
    <h3>Insert Class Year</h3>
    <form method="post">
        <input type="text" name="newYear" placeholder="Class Year">
        <input type="submit" name="insert" value="Insert">
    </form>

    <!-- Delete Form -->
    <h3>Delete Class Year</h3>
    <form method="post">
        <input type="text" name="deleteYear" placeholder="Class Year">
        <input type="submit" name="delete" value="Delete">
    </form>

    <!-- Class Years Table -->
    <table>
        <tr>
            <th>Class Year</th>
        </tr>
        <?php foreach ($class as $row):
            if ($searchYear !== "" && $row['year'] != $searchYear) continue;
        ?>
        <tr>
            <td><?php echo $row['year'] ?? 'NULL'; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Back Button -->
    <button onclick="window.location.href='index4.php'">Back to Main Menu</button>
</body>
</html>
