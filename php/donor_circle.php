<?php
// Initialize variables
$circles = [];
$message = "";
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
        $newCircleName = trim($_POST['newCircleName'] ?? '');
        $newAmountThreshold = trim($_POST['newAmountThreshold'] ?? '');

        // Validate inputs
        if ($newCircleName === '') {
            $message = "Circle Name cannot be empty.";
        } elseif (!is_numeric($newAmountThreshold) || $newAmountThreshold < 0) {
            $message = "Amount Threshold must be a non-negative number.";
        } else {
            // Check if circle already exists
            $stmt = $conn->prepare("SELECT COUNT(*) FROM DonorCircle WHERE circleName = :circleName");
            $stmt->bindParam(':circleName', $newCircleName);
            $stmt->execute();
            if ($stmt->fetchColumn()) {
                $message = "Circle '{$newCircleName}' already exists.";
            } else {
                // Insert new circle
                $stmt = $conn->prepare("INSERT INTO DonorCircle (circleName, amountThreshold) VALUES (:circleName, :amountThreshold)");
                $stmt->bindParam(':circleName', $newCircleName);
                $stmt->bindParam(':amountThreshold', $newAmountThreshold);
                $stmt->execute();
                $message = "Donor Circle inserted successfully.";
            }
        }
    }

    // Handle delete
    if (isset($_POST['delete'])) {
        $deleteCircleName = trim($_POST['deleteCircleName'] ?? '');
        if ($deleteCircleName === '') {
            $message = "Please provide a Circle Name to delete.";
        } else {
            $stmt = $conn->prepare("DELETE FROM DonorCircle WHERE circleName = :circleName");
            $stmt->bindParam(':circleName', $deleteCircleName);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $message = "Donor Circle deleted successfully.";
            } else {
                $message = "Circle '{$deleteCircleName}' does not exist.";
            }
        }
    }

    // Handle search
    if (isset($_POST['searchCircleName'])) {
        $searchCircleName = trim($_POST['searchCircleName']);
    }

    // Fetch all circles
    $stmt = $conn->prepare("SELECT * FROM DonorCircle");
    $stmt->execute();
    $circles = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Donor Circles</title>

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
    <h1>DonorCircle Table</h1>
    <p>The list of donor circles and their amount threshold.</p>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Search Form -->
    <h3>Search by Circle Name</h3>
    <form method="post">
        <input type="text" name="searchCircleName" placeholder="Enter Circle Name" value="<?php echo htmlspecialchars($searchCircleName); ?>">
        <input type="submit" value="Search">
        <button type="submit" name="searchCircleName" value="">Show All</button>
    </form>

    <!-- Insert Form -->
    <h3>Insert Donor Circle</h3>
    <form method="post">
        <input type="text" name="newCircleName" placeholder="Circle Name">
        <input type="text" name="newAmountThreshold" placeholder="Amount Threshold">
        <input type="submit" name="insert" value="Insert">
    </form>

    <!-- Delete Form -->
    <h3>Delete Donor Circle</h3>
    <form method="post">
        <input type="text" name="deleteCircleName" placeholder="Circle Name">
        <input type="submit" name="delete" value="Delete">
    </form>

    <!-- Donor Circles Table -->
    <table>
        <tr>
            <th>Circle Name</th>
            <th>Amount Threshold</th>
        </tr>

        <?php foreach ($circles as $row):
            if ($searchCircleName !== "" && stripos($row['circleName'], $searchCircleName) === false) continue;
        ?>
            <tr>
                <td><?php echo $row['circleName'] ?? 'NULL'; ?></td>
                <td><?php echo $row['amountThreshold'] ?? 'NULL'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

    <button onclick="window.location.href='index4.php'">Back to Main Menu</button>
</body>
</html>
