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

-- Dummpy entities--
INSERT INTO Item
VALUES(111, 'bleh', 'CD', 'classical','Gods Hand', 2014, 99.99, 50);
INSERT INTO Item
VALUES(112, 'ha', 'CD', 'classical','Gods Hand', 2014, 20.0, 50);
INSERT INTO Item
VALUES(113, 'das', 'CD', 'classical','Gods Hand', 2014, 52.60, 50);
INSERT INTO Item
VALUES(114, 'da', 'CD', 'classical','Gods Hand', 2014, 11.11, 50);
INSERT INTO Item
VALUES(115, 'ka', 'CD', 'classical','Gods Hand', 2014, 52.68, 50);
INSERT INTO Item
VALUES(116, 'lol', 'CD', 'classical','Gods Hand', 2014, 42.50, 50);
INSERT INTO Item
VALUES(117, 'he', 'CD', 'classical','Gods Hand', 2014, 96.0, 50);
INSERT INTO Item
VALUES(118, 'ka', 'CD', 'classical','Gods Hand', 2014, 55.00, 50);
INSERT INTO Item
VALUES(119, 'something', 'CD', 'classical','Gods Hand', 2014, 58.20, 50);
INSERT INTO Item
VALUES(120, 'nothing', 'CD', 'classical','Gods Hand', 2014, 15.45, 50);
INSERT INTO Item
VALUES(121, 'i dunno', 'CD', 'classical','Gods Hand', 2014, 85.58, 50);
INSERT INTO Item
VALUES(122, 'this', 'CD', 'classical','Gods Hand', 2014, 248.25, 50);
INSERT INTO Item
VALUES(123, 'two', 'CD', 'classical','Gods Hand', 2014, 15.50, 50);

INSERT INTO Customer
VALUES(333, '233', 'Justin Bieber', 'somewhere', 50238);
INSERT INTO Customer
VALUES(334, 'password', 'Kenneth', 'home', 56132);
INSERT INTO Customer
VALUES(335, '1234', 'Olivia', 'Mars', 231823);
INSERT INTO Customer
VALUES(336, '4321', 'April', 'Sun', 135155);
INSERT INTO Customer
VALUES(337, 'something', 'Daniel', 'Canada', 16555);
INSERT INTO Customer
VALUES(338, 'thatthing', 'Tom', 'nowhere', 549412);
INSERT INTO Customer
VALUES(339, 'dunno', 'Bob', 'there', 515161);
INSERT INTO Customer
VALUES(340, 'whaatever', 'George', 'here', 575415);

INSERT INTO Purchase
VALUES(222, CURRENT_DATE(),	333, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(223, CURRENT_DATE() -10,	333, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(224, CURRENT_DATE() - 4,	334, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(225, CURRENT_DATE() - 2,	334, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(226, CURRENT_DATE() - 100,	335, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(227, CURRENT_DATE() - 20,	335, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(228, CURRENT_DATE() -365,	335, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(229, CURRENT_DATE(),	336, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(230, CURRENT_DATE(),	336, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(231, CURRENT_DATE(),	336, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(232, CURRENT_DATE(),	337, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(233, CURRENT_DATE(),	337, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(234, CURRENT_DATE(),	337, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(235, CURRENT_DATE(),	338, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(236, CURRENT_DATE(),	338, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(237, CURRENT_DATE(),	339, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(238, CURRENT_DATE(),	339, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(239, CURRENT_DATE(),	339, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(240, CURRENT_DATE(),	339, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);

INSERT INTO PurchaseItem
VALUES(222, 111, 20);
INSERT INTO PurchaseItem
VALUES(229, 115, 40);
INSERT INTO PurchaseItem
VALUES(230, 113, 2);
INSERT INTO PurchaseItem
VALUES(231, 118, 50);
INSERT INTO PurchaseItem
VALUES(232, 111, 32);
INSERT INTO PurchaseItem
VALUES(233, 113, 20);
INSERT INTO PurchaseItem
VALUES(234, 116, 12);
INSERT INTO PurchaseItem
VALUES(237, 113, 5);
INSERT INTO PurchaseItem
VALUES(235, 123, 23);
INSERT INTO PurchaseItem
VALUES(236, 112, 8);
INSERT INTO PurchaseItem
VALUES(238, 117, 8);
INSERT INTO PurchaseItem
VALUES(239, 113, 12);
INSERT INTO PurchaseItem
VALUES(240, 118, 30);
INSERT INTO PurchaseItem
VALUES(223, 120, 21);
INSERT INTO PurchaseItem
VALUES(224, 121, 33);