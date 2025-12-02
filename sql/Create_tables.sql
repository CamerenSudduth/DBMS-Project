CREATE TABLE ClassYear (year INT(4) NOT NULL,PRIMARY KEY (year)
);

CREATE TABLE DonorCircle (
circleName VARCHAR(50) NOT NULL,
amountThreshold DECIMAL(10, 2) NOT NULL,
PRIMARY KEY (circleName)
);

CREATE TABLE DonorEvent (
eventName VARCHAR(100) NOT NULL,
eventDate DATE NOT NULL,
PRIMARY KEY (eventName), UNIQUE (eventDate)
);

CREATE TABLE Employer (
EmployerID INT(11) NOT NULL AUTO_INCREMENT,
name VARCHAR(100) NOT NULL,
address VARCHAR(255) NULL,
contactPerson VARCHAR(100) NULL,
PRIMARY KEY (EmployerID)
);

CREATE TABLE Donor (
DonorID INT(11) NOT NULL AUTO_INCREMENT,
name VARCHAR(100) NOT NULL,
address VARCHAR(255) NULL,
phone VARCHAR(20) NULL,
email VARCHAR(100) NULL,
category VARCHAR(50) NOT NULL,
job VARCHAR (100) NULL,
lastYearDonation DECIMAL (10, 2) NOT NULL DEFAULT 0.00,
currentYearDonation DECIMAL (10, 2) NOT NULL DEFAULT 0.00,
coordinatorYear INT(4) NULL,
PRIMARY KEY (DonorID),
CONSTRAINT fk_donor_coordinator FOREIGN KEY (coordinatorYear) REFERENCES ClassYear (year)
);

CREATE TABLE Pledge (
PledgedID INT(11) NOT NULL AUTO_INCREMENT,
amountPledged DECIMAL(10, 2) NOT NULL,
dateOfPledge DATE NOT NULL,
numOfPayments INT(3) NOT NULL DEFAULT 1,
paymentMethod VARCHAR(50) NOT NULL,
creditCardNumber VARCHAR(20) NULL,
DonorID INT(11) NOT NULL,
EmployerID INT(11) NULL,
circleName VARCHAR(50) NULL,
PRIMARY KEY (PledgedID),
CONSTRAINT fk_pledge_donor FOREIGN KEY (DonorID) REFERENCES Donor (DonorID),
CONSTRAINT fk_pledge_employer FOREIGN KEY (EmployerID) REFERENCES Employer (EmployerID),
CONSTRAINT fk_pledge_circle FOREIGN KEY (circleName) REFERENCES DonorCircle (circleName)
);

CREATE TABLE Payment (
PaymentID INT(11) NOT NULL AUTO_INCREMENT,
amount DECIMAL(10, 2) NOT NULL,
dateReceived DATE NOT NULL,
dueDate DATE NULL,
PledgeID INT(11) NOT NULL,
PRIMARY KEY (PaymentID),
CONSTRAINT fk_payment_pledge FOREIGN KEY (PledgeID) REFERENCES Pledge (PledgedID)
);

CREATE TABLE Attended (
DonorID INT(11) NOT NULL,
eventName VARCHAR(100) NOT NULL,
PRIMARY KEY (DonorID, eventName),
CONSTRAINT fk_attended_donor FOREIGN KEY (DonorID) REFERENCES Donor (DonorID),
CONSTRAINT fk_attended_event FOREIGN KEY (eventName) REFERENCES DonorEvent (eventName)
);

CREATE TABLE ClassifiedBy (
DonorID INT(11) NOT NULL,
year INT(4) NOT NULL,
role VARCHAR(50) NOT NULL,
PRIMARY KEY (DonorID, year),
CONSTRAINT fk_classified_donor FOREIGN KEY (DonorID) REFERENCES Donor (DonorID),
CONSTRAINT fk_classified_year FOREIGN KEY (year) REFERENCES ClassYear (year)
);

