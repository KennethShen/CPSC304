DROP DATABASE IF EXISTS CPSC304;
CREATE DATABASE CPSC304;
USE CPSC304;

-- Item(upc, title, type, category, company, year, price, stock)

CREATE TABLE IF NOT EXISTS Item(
	upc INTEGER NOT NULL,
	title CHAR(30) NOT NULL,
    type VARCHAR(3) NOT NULL,
	category VARCHAR(30) NOT NULL,
	company VARCHAR(30) NOT NULL,
	year INTEGER NOT NULL,
    price DOUBLE NOT NULL,
	stock INTEGER NOT NULL,
    PRIMARY KEY (upc)
);   

-- LeadSinger(upc, name)

CREATE TABLE IF NOT EXISTS LeadSinger (
    upc INTEGER NOT NULL,
    name VARCHAR(30) NOT NULL,
    PRIMARY KEY (upc, name),
    FOREIGN KEY (upc) REFERENCES Item(upc)
    	ON DELETE CASCADE
    );
    
-- HasSong(upc, title)

CREATE TABLE IF NOT EXISTS HasSong (
    upc INTEGER NOT NULL,
    title VARCHAR(24) NOT NULL,
    PRIMARY KEY (upc, title),
    FOREIGN KEY (upc) REFERENCES Item(upc)
    	ON DELETE CASCADE
    );
        
-- Customer(cid, username, password, name, address, phone)
CREATE TABLE IF NOT EXISTS Customer (
cid INTEGER NOT NULL AUTO_INCREMENT,
	username VARCHAR(24) UNIQUE,
  	password VARCHAR(60) NOT NULL,
	name CHAR(30) NOT NULL,
	address VARCHAR(30) NOT NULL,
	phone INTEGER(10) NOT NULL,
    PRIMARY KEY (cid)
    );

 -- Purchase (receiptId, date, cid, card#, expiryDate,expectedDate, deliveredDate)

CREATE TABLE IF NOT EXISTS Purchase (
	receiptId INTEGER NOT NULL AUTO_INCREMENT,
	date DATE NOT NULL,
	cid INTEGER NOT NULL,
	cardNo INTEGER NOT NULL,
	expiryDate DATE NOT NULL,
	expectedDate DATE,
	deliveredDate DATE,
	PRIMARY KEY( receiptId ),
	FOREIGN KEY (cid) REFERENCES Customer(cid)
		ON DELETE CASCADE
);

-- PurchaseItem (receiptId, upc, quantity)

CREATE TABLE IF NOT EXISTS PurchaseItem (
	receiptId INTEGER NOT NULL,
	upc INTEGER NOT NULL,
	quantity INTEGER NOT NULL,
	PRIMARY KEY(receiptId, upc),
	FOREIGN KEY(receiptId) REFERENCES Purchase(receiptId),
	FOREIGN KEY(upc) REFERENCES Item(upc)
);


-- Returned (retid, date, receptId)
CREATE TABLE IF NOT EXISTS Returned(
	retid INTEGER NOT NULL AUTO_INCREMENT,
	date DATE NOT NULL,
	receiptId INTEGER NOT NULL,
	PRIMARY KEY(retid),
	FOREIGN KEY(receiptId) REFERENCES Purchase(receiptId)
);

-- ReturnItem( retid, upc, quantity)
CREATE TABLE IF NOT EXISTS ReturnItem (
	retid INTEGER NOT NULL,
	upc INTEGER NOT NULL,
	quantity INTEGER NOT NULL,
	PRIMARY KEY(retid, upc),
	FOREIGN KEY (retid) REFERENCES Returned(retid),
	FOREIGN KEY (upc) REFERENCES Item(upc)
);

-- Dummpy entities--
INSERT INTO Item
VALUES(111, 'bleh', 'CD', 'rock','Gods Hand', 2014, 99.99, 50);
INSERT INTO Item
VALUES(112, 'ha', 'CD', 'rap','Gods Hand', 2014, 20.0, 50);
INSERT INTO Item
VALUES(113, 'das', 'CD', 'classical','Gods Hand', 2014, 52.60, 50);
INSERT INTO Item
VALUES(114, 'da', 'CD', 'classical','Gods Hand', 2014, 11.11, 50);
INSERT INTO Item
VALUES(115, 'ka', 'CD', 'pop','Gods Hand', 2014, 52.68, 50);
INSERT INTO Item
VALUES(116, 'lol', 'CD', 'pop','Gods Hand', 2014, 42.50, 50);
INSERT INTO Item
VALUES(117, 'he', 'CD', 'classical','Gods Hand', 2014, 96.0, 50);
INSERT INTO Item
VALUES(118, 'ka', 'CD', 'rap','Gods Hand', 2014, 55.00, 50);
INSERT INTO Item
VALUES(119, 'something', 'CD', 'pop','Gods Hand', 2014, 58.20, 50);
INSERT INTO Item
VALUES(120, 'nothing', 'CD', 'rap','Gods Hand', 2014, 15.45, 50);
INSERT INTO Item
VALUES(121, 'i dunno', 'CD', 'classical','Gods Hand', 2014, 85.58, 50);
INSERT INTO Item
VALUES(122, 'this', 'CD', 'classical','Gods Hand', 2014, 248.25, 50);
INSERT INTO Item
VALUES(123, 'two', 'CD', 'classical','Gods Hand', 2014, 15.50, 50);

INSERT INTO Customer
VALUES(333,'name', '233', 'Justin Bieber', 'somewhere', 50238);
INSERT INTO Customer
VALUES(334,'Bieber', 'password', 'Kenneth', 'home', 56132);
INSERT INTO Customer
VALUES(335,'something', '1234', 'Olivia', 'Mars', 231823);
INSERT INTO Customer
VALUES(336, 'thatthing', '4321', 'April', 'Sun', 135155);
INSERT INTO Customer
VALUES(337, 'aname', 'something', 'Daniel', 'Canada', 16555);
INSERT INTO Customer
VALUES(338, 'thatname', 'thatthing', 'Tom', 'nowhere', 549412);
INSERT INTO Customer
VALUES(339, 'toolazy', 'dunno', 'Bob', 'there', 515161);
INSERT INTO Customer
VALUES(340, 'somdsa', 'whaatever', 'George', 'here', 575415);

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
VALUES(229, 111, 40);
INSERT INTO PurchaseItem
VALUES(230, 112, 2);
INSERT INTO PurchaseItem
VALUES(227, 113, 50);
INSERT INTO PurchaseItem
VALUES(231, 113, 50);
INSERT INTO PurchaseItem
VALUES(232, 114, 32);
INSERT INTO PurchaseItem
VALUES(233, 115, 20);
INSERT INTO PurchaseItem
VALUES(234, 116, 12);
INSERT INTO PurchaseItem
VALUES(237, 117, 5);
INSERT INTO PurchaseItem
VALUES(235, 118, 23);
INSERT INTO PurchaseItem
VALUES(236, 119, 8);
INSERT INTO PurchaseItem
VALUES(238, 120, 8);
INSERT INTO PurchaseItem
VALUES(239, 121, 12);
INSERT INTO PurchaseItem
VALUES(240, 118, 30);
INSERT INTO PurchaseItem
VALUES(223, 120, 21);
INSERT INTO PurchaseItem
VALUES(224, 121, 33);

CREATE VIEW topsell AS SELECT i.upc, i.category, i.price, p.date, sum(quantity) AS total
FROM Purchase p, Item i, PurchaseItem pi 
WHERE pi.upc = i.upc AND pi.receiptId = p.receiptId
GROUP BY i.upc ORDER BY sum(quantity) DESC;

CREATE VIEW OutstandingOrders AS
SELECT *
FROM Purchase
WHERE expectedDate IS NOT NULL
AND deliveredDate IS NULL;
