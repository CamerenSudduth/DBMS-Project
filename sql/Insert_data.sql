INSERT INTO ClassYear (year) VALUES (1995);
INSERT INTO ClassYear (year) VALUES (2000);
INSERT INTO ClassYear (year) VALUES (2010);
INSERT INTO ClassYear (year) VALUES (2020);
INSERT INTO ClassYear (year) VALUES (2025);
INSERT INTO DonorCircle (circleName, amountThreshold) VALUES ('Bronze', 100.00);
INSERT INTO DonorCircle (circleName, amountThreshold) VALUES ('Silver', 500.00);
INSERT INTO DonorCircle (circleName, amountThreshold) VALUES ('Gold', 1000.00);
INSERT INTO DonorCircle (circleName, amountThreshold) VALUES ('Platinum', 5000.00);
INSERT INTO DonorCircle (circleName, amountThreshold) VALUES ('Founders', 10000.00);
INSERT INTO DonorEvent (eventName, eventDate) VALUES ('Annual Gala 2024', '2024-11-15');
INSERT INTO DonorEvent (eventName, eventDate) VALUES ('Phonathon Kickoff', '2025-01-20');
INSERT INTO DonorEvent (eventName, eventDate) VALUES ('Spring Mixer', '2025-04-10');
INSERT INTO DonorEvent (eventName, eventDate) VALUES ('Alumni Weekend Lunch', '2025-06-05');
INSERT INTO DonorEvent (eventName, eventDate) VALUES ('Faculty Reception', '2025-09-01');
INSERT INTO Employer (EmployerID, name, address, contactPerson) VALUES (1, 'TechCorp Solutions', '100 Main St', 'Alice Smith');
INSERT INTO Employer (EmployerID, name, address, contactPerson) VALUES (2, 'Global Finance Group', '20 Wall St', 'Bob Johnson');
INSERT INTO Employer (EmployerID, name, address, contactPerson) VALUES (3, 'City Hospital Network', '300 Oak Ave', 'Dr. Carol Lee');
INSERT INTO Employer (EmployerID, name, address, contactPerson) VALUES (4, 'Alpha University', '400 College Dr', 'Dean Davis');
INSERT INTO Employer (EmployerID, name, address, contactPerson) VALUES (5, 'Local Bakery', '50 Market St', 'Eve Adams');
INSERT INTO Donor (DonorID, name, address, phone, email, category, job, lastYearDonation, currentYearDonation, coordinatorYear) VALUES
(101, 'John Q. Alumnus', '123 Pine Ln', '832-555-1001', 'john.a@mail.com', 'alumni', 'Engineer', 1000.00, 1500.00, 1995);
INSERT INTO Donor (DonorID, name, address, phone, email, category, job, lastYearDonation, currentYearDonation, coordinatorYear) VALUES
(102, 'Mary T. Parent', '456 Cedar Dr', '281-555-1002', 'mary.p@mail.com', 'parents', 'Manager', 500.00, 750.00, NULL);
INSERT INTO Donor (DonorID, name, address, phone, email, category, job, lastYearDonation, currentYearDonation, coordinatorYear) VALUES
(103, 'Global Finance Group', '20 Wall St', '246-555-1003', 'corp@global.com', 'corporation', 'N/A', 5000.00, 0.00, NULL);
INSERT INTO Donor (DonorID, name, address, phone, email, category, job, lastYearDonation, currentYearDonation, coordinatorYear) VALUES
(104, 'Prof. Alan Turing', '789 Birch Ave', '281-555-1004', 'alan.t@alpha.edu', 'faculty', 'Professor', 200.00, 200.00, NULL);
INSERT INTO Donor (DonorID, name, address, phone, email, category, job, lastYearDonation, currentYearDonation, coordinatorYear) VALUES
(105, 'John Smith', '10 Elm St', '246-555-1005', 'j.smith@mail.com', 'alumni', 'CEO', 15000.00, 15000.00, 2000);
INSERT INTO Pledge (PledgedID, amountPledged, dateOfPledge, numOfPayments, paymentMethod, creditCardNumber, DonorID, EmployerID,
circleName) VALUES (1, 1000.00, '2025-07-01', 1, 'check', NULL, 101, 1, 'Gold');
INSERT INTO Pledge (PledgedID, amountPledged, dateOfPledge, numOfPayments, paymentMethod, creditCardNumber, DonorID, EmployerID,
circleName) VALUES (2, 750.00, '2025-07-15', 4, 'bankTransfer', NULL, 102, NULL, 'Silver');
INSERT INTO Pledge (PledgedID, amountPledged, dateOfPledge, numOfPayments, paymentMethod, creditCardNumber, DonorID, EmployerID,
circleName) VALUES (3, 50.00, '2025-08-01', 1, 'check', NULL, 103, NULL, NULL);
INSERT INTO Pledge (PledgedID, amountPledged, dateOfPledge, numOfPayments, paymentMethod, creditCardNumber, DonorID, EmployerID,
circleName) VALUES (4, 5500.00, '2025-08-10', 1, 'credit', '1111222233334444', 104, NULL, 'Platinum');
INSERT INTO Pledge (PledgedID, amountPledged, dateOfPledge, numOfPayments, paymentMethod, creditCardNumber, DonorID, EmployerID,
circleName) VALUES (5, 10000.00, '2025-09-01', 10, 'credit', '5555666677778888', 105, NULL, 'Founders');
INSERT INTO Payment (PaymentID, amount, dateReceived, dueDate, PledgeID) VALUES (1, 1000.00, '2025-07-05', NULL, 1);
INSERT INTO Payment (PaymentID, amount, dateReceived, dueDate, PledgeID) VALUES (2, 187.50, '2025-07-15', '2025-07-15', 2);
INSERT INTO Payment (PaymentID, amount, dateReceived, dueDate, PledgeID) VALUES (3, 187.50, '2025-08-15', '2025-08-15', 2);
INSERT INTO Payment (PaymentID, amount, dateReceived, dueDate, PledgeID) VALUES (4, 5500.00, '2025-08-10', NULL, 4);
INSERT INTO Payment (PaymentID, amount, dateReceived, dueDate, PledgeID) VALUES (5, 1000.00, '2025-09-01', '2025-09-01', 5);
INSERT INTO Attended (DonorID, eventName) VALUES (101, 'Annual Gala 2024');
INSERT INTO Attended (DonorID, eventName) VALUES (101, 'Spring Mixer');
INSERT INTO Attended (DonorID, eventName) VALUES (102, 'Annual Gala 2024');
INSERT INTO Attended (DonorID, eventName) VALUES (104, 'Faculty Reception');
INSERT INTO Attended (DonorID, eventName) VALUES (105, 'Alumni Weekend Lunch');
INSERT INTO ClassifiedBy (DonorID, year, role) VALUES (101, 1995, 'Alumnus');
INSERT INTO ClassifiedBy (DonorID, year, role) VALUES (102, 2020, 'Parent of Graduate');
INSERT INTO ClassifiedBy (DonorID, year, role) VALUES (102, 2025, 'Parent of Current Student');
INSERT INTO ClassifiedBy (DonorID, year, role) VALUES (104, 2010, 'Faculty');
INSERT INTO ClassifiedBy (DonorID, year, role) VALUES (105, 2000, 'Alumnus');

