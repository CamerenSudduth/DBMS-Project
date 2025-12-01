<!DOCTYPE html>
<html>
<head>
    <title>Main Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 40px;
        }
        button {
            display: block;
            width: 240px;
            margin: 20px auto;
            padding: 10px 20px;
            font-size: 18px;
        }
        .content {
            display: inline-block;
            text-align: center;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <div class="content">
        <h1>Alpha University System</h1>

        <!-- View Attended Table -->
        <button onclick="window.location.href='attended.php'">View Attended Table</button>
        <!-- View ClassifiedBy Table -->
        <button onclick="window.location.href='classified_by.php'">View ClassifiedBy Table</button>
        <!-- View ClassYear Table -->
        <button onclick="window.location.href='class_year.php'">View ClassYear Table</button>
        <!-- View Donor Table -->
        <button onclick="window.location.href='donor.php'">View Donor Table</button>
        <!-- View DonorCircle Table -->
        <button onclick="window.location.href='donor_circle.php'">View DonorCircle Table</button>
        <!-- View DonorEvent Table -->
        <button onclick="window.location.href='donor_event.php'">View DonorEvent Table</button>
        <!-- View Employer Table -->
        <button onclick="window.location.href='employer.php'">View Employer Table</button>
        <!-- View Payment Table -->
        <button onclick="window.location.href='payment.php'">View Payment Table</button>
        <!-- View Payment Table -->
        <button onclick="window.location.href='pledge.php'">View Pledge Table</button>
        
    </div>

</body>
</html>