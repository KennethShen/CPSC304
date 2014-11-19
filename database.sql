DROP DATABASE IF EXISTS CPSC304;
CREATE DATABASE CPSC304;
USE CPSC304;

-- Item(upc, title, type, category, company, year, price, stock)

CREATE TABLE IF NOT EXISTS Item(
	upc INTEGER,
	title CHAR(30),
    type VARCHAR(3),
	category VARCHAR(30),
	company VARCHAR(30),
	year INTEGER,
    price DOUBLE,
	stock INTEGER,
    PRIMARY KEY (upc)
);   

-- LeadSinger(upc, name)

CREATE TABLE IF NOT EXISTS LeadSinger (
    upc INTEGER,
    name VARCHAR(30),
    PRIMARY KEY (upc, name),
    FOREIGN KEY (upc) REFERENCES Item(upc)
    	ON DELETE CASCADE
    );
    
-- HasSong(upc, title)

CREATE TABLE IF NOT EXISTS HasSong (
    upc INTEGER,
    title VARCHAR(24),
    PRIMARY KEY (upc, title),
    FOREIGN KEY (upc) REFERENCES Item(upc)
    	ON DELETE CASCADE
    );
        
-- Customer(cid, username, password, name, address, phone)
CREATE TABLE IF NOT EXISTS Customer (
cid INTEGER,
	username VARCHAR(24) UNIQUE,
  	password VARCHAR(24),
	name CHAR(30),
	address VARCHAR(30),
	phone INTEGER(10),
    PRIMARY KEY (cid)
    );

 -- Purchase (receiptId, date, cid, card#, expiryDate,expectedDate, deliveredDate)

CREATE TABLE IF NOT EXISTS Purchase (
	receiptId INTEGER,
	date DATE,
	cid INTEGER,
	cardNo INTEGER,
	expiryDate DATE,
	expectedDate DATE,
	deliveredDate DATE,
	PRIMARY KEY( receiptId ),
	FOREIGN KEY (cid) REFERENCES Customer(cid)
		ON DELETE CASCADE
);

-- PurchaseItem (receiptId, upc, quantity)

CREATE TABLE IF NOT EXISTS PurchaseItem (
	receiptId INTEGER,
	upc INTEGER,
	quantity INTEGER,
	PRIMARY KEY(receiptId, upc),
	FOREIGN KEY(receiptId) REFERENCES Purchase(receiptId),
	FOREIGN KEY(upc) REFERENCES Item(upc)
);


-- Returned (retid, date, receptId)
CREATE TABLE IF NOT EXISTS Returned(
	retid INTEGER,
	date DATE,
	receiptId INTEGER,
	PRIMARY KEY(retid),
	FOREIGN KEY(receiptId) REFERENCES Purchase(receiptId)
);

-- ReturnItem( retid, upc, quantity)
CREATE TABLE IF NOT EXISTS ReturnItem (
	retid INTEGER,
	upc INTEGER,
	quantitiy INTEGER,
	PRIMARY KEY(retid, upc),
	FOREIGN KEY (retid) REFERENCES Returned(retid),
	FOREIGN KEY (upc) REFERENCES Item(upc)
);
