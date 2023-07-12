CREATE DATABASE transactions;
USE transactions;

CREATE TABLE transactions (
	transactionID INT NOT NULL AUTO_INCREMENT,
    amount DECIMAL(20, 2),
    company VARCHAR(128),
    purchaseDate DATETIME,
    frequency VARCHAR(128),
    transactionType VARCHAR(50),
    PRIMARY KEY (transactionID)
);