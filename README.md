Alpha_University Database and PHP Pages Setup Instructions

Step 0: Prerequisites
1. Make sure MySQL and XAMPP (or another PHP-enabled server) are installed.
2. Ensure PHP version is compatible with PDO (PHP 7+ recommended).
3. Have a text editor (like VS Code or Notepad++) ready for editing files if needed.

Step 1: Creating the Database
1. Open MySQL Workbench (or another MySQL client).
2. Open the create_tables.sql file.
3. Run the SQL script to create the Alpha_University database and its tables.
4. Verify that all tables have been created successfully.

Step 2: Inserting Sample Data
1. Open the insert_data.sql file.
2. Run the script to populate the database with sample data.
3. Verify that all tables contain the expected records.

Step 3: Running the PHP Pages
1. Make sure XAMPP is running to serve PHP pages and connect to MySQL.
2. Place all PHP files in the server’s htdocs directory (or your web root folder).
3. Open create_user.sql and run it. This will create a user and grant privileges to access the database via the PHP pages.
4. Open a web browser and navigate to:
http://localhost/<folder-name>/<page>.php
5. Replace <folder-name> with the folder where you placed your PHP files.
Example: http://localhost/AlphaUniversity/index.php

Step 4: Using and Modifying the Database
1. Use the provided forms on each PHP page to view, search, insert, and delete records.
2. Search forms allow filtering by primary or foreign keys. Use the Show All button to clear filters.
3. Insert forms validate inputs:
-Numeric values (like Amount, DonorID, PledgeID) must be valid.
-Dates must be properly formatted.
-Foreign key values must exist in the referenced table.
-Delete forms will notify you if the record to delete does not exist.

Troubleshooting Tips:
1. Foreign key errors on insert: Double-check that the referenced value exists in the corresponding table (e.g., DonorID must exist in the Donor table).
2. Database connection issues: Ensure the credentials in the PHP files match the MySQL user and password.
3. Date or numeric validation errors: Ensure all required fields are filled in the correct format.
