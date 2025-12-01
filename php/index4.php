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
        .content {
            display: inline-block;
            text-align: center;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }
        .button-row {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            margin: 10px 0;
        }
        .button-row button {
            margin: 10px;
            padding: 10px 20px;
            font-size: 16px;
            width: 220px;
        }
    </style>
</head>
<body>

    <div class="content">
        <img src="Alpha University Transparent Logo.png" alt="Alpha University Transparent Logo" width="300">

        <!-- Top row: 3 buttons -->
        <div class="button-row">
            <button onclick="window.location.href='attended.php'">View Attended Table</button>
            <button onclick="window.location.href='classified_by.php'">View ClassifiedBy Table</button>
            <button onclick="window.location.href='class_year.php'">View ClassYear Table</button>
        </div>
        
        <!-- Mid row: 2 buttons -->
        <div class="button-row">
            <button onclick="window.location.href='donor.php'">View Donor Table</button>
            <button onclick="window.location.href='donor_circle.php'">View DonorCircle Table</button>
            <button onclick="window.location.href='donor_event.php'">View DonorEvent Table</button>
        </div>

        <!-- Bottom row: 4 buttons -->
        <div class="button-row">
            <button onclick="window.location.href='employer.php'">View Employer Table</button>
            <button onclick="window.location.href='payment.php'">View Payment Table</button>
            <button onclick="window.location.href='pledge.php'">View Pledge Table</button>
        </div>

    </div>

</body>
</html>
