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
VALUES(1, 'bleh', 'CD', 'rock','Gods Hand', 2014, 99.99, 50);
INSERT INTO Item
VALUES(2, 'ha', 'CD', 'rap','Gods Hand', 2014, 20.0, 50);
INSERT INTO Item
VALUES(3, 'das', 'CD', 'classical','Gods Hand', 2014, 52.60, 50);
INSERT INTO Item
VALUES(4, 'da', 'CD', 'classical','Gods Hand', 2014, 11.11, 50);
INSERT INTO Item
VALUES(5, 'ka', 'CD', 'pop','Gods Hand', 2014, 52.68, 50);
INSERT INTO Item
VALUES(6, 'lol', 'CD', 'pop','Gods Hand', 2014, 42.50, 50);
INSERT INTO Item
VALUES(7, 'he', 'CD', 'classical','Gods Hand', 2014, 96.0, 50);
INSERT INTO Item
VALUES(8, 'ka', 'CD', 'rap','Gods Hand', 2014, 55.00, 50);
INSERT INTO Item
VALUES(9, 'something', 'CD', 'pop','Gods Hand', 2014, 58.20, 50);
INSERT INTO Item
VALUES(10, 'nothing', 'CD', 'rap','Gods Hand', 2014, 15.45, 50);
INSERT INTO Item
VALUES(11, 'i dunno', 'CD', 'classical','Gods Hand', 2014, 85.58, 50);
INSERT INTO Item
VALUES(12, 'this', 'CD', 'classical','Gods Hand', 2014, 248.25, 50);
INSERT INTO Item
VALUES(13, 'two', 'CD', 'classical','Gods Hand', 2014, 15.50, 50);

INSERT INTO Customer
VALUES(1,'name', '12', 'Justin Bieber', 'somewhere', 5017);
INSERT INTO Customer
VALUES(2,'Bieber', 'password', 'Kenneth', 'home', 56132);
INSERT INTO Customer
VALUES(3,'something', '134', 'Olivia', 'Mars', 10823);
INSERT INTO Customer
VALUES(4, 'thatthing', '4321', 'April', 'Sun', 135155);
INSERT INTO Customer
VALUES(5, 'aname', 'something', 'Daniel', 'Canada', 16555);
INSERT INTO Customer
VALUES(6, 'thatname', 'thatthing', 'Tom', 'nowhere', 549412);
INSERT INTO Customer
VALUES(7, 'toolazy', 'dunno', 'Bob', 'there', 515161);
INSERT INTO Customer
VALUES(8, 'somdsa', 'whaatever', 'George', 'here', 575415);

INSERT INTO Purchase
VALUES(1, CURRENT_DATE(),	1, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(2, CURRENT_DATE() -10,	1, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(3, CURRENT_DATE() - 4,	2, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(4, CURRENT_DATE() - 2,	2, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(5, CURRENT_DATE() - 100,	3, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(6, CURRENT_DATE() - 20,	3, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(7, CURRENT_DATE() -365,	3, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(8, CURRENT_DATE(),	4, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(9, CURRENT_DATE(),	4, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(10, CURRENT_DATE(),	4, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(11, CURRENT_DATE(),	5, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(12, CURRENT_DATE(),	5, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(13, CURRENT_DATE(),	5, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(14, CURRENT_DATE(),	6, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(15, CURRENT_DATE(),	6, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(16, CURRENT_DATE(),	7, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(17, CURRENT_DATE(),	7, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(18, CURRENT_DATE(),	7, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(19, CURRENT_DATE(),	7, 16516161, CURRENT_DATE()+10, CURRENT_DATE()+5, CURRENT_DATE()+2);

INSERT INTO PurchaseItem
VALUES(1, 1, 20);
INSERT INTO PurchaseItem
VALUES(8, 1, 40);
INSERT INTO PurchaseItem
VALUES(9, 2, 2);
INSERT INTO PurchaseItem
VALUES(6, 3, 50);
INSERT INTO PurchaseItem
VALUES(10, 3, 50);
INSERT INTO PurchaseItem
VALUES(11, 4, 32);
INSERT INTO PurchaseItem
VALUES(12, 5, 20);
INSERT INTO PurchaseItem
VALUES(13, 6, 12);
INSERT INTO PurchaseItem
VALUES(16, 7, 5);
INSERT INTO PurchaseItem
VALUES(14, 8, 23);
INSERT INTO PurchaseItem
VALUES(15, 9, 8);
INSERT INTO PurchaseItem
VALUES(17, 10, 8);
INSERT INTO PurchaseItem
VALUES(18, 11, 12);
INSERT INTO PurchaseItem
VALUES(19, 8, 30);
INSERT INTO PurchaseItem
VALUES(2, 10, 21);
INSERT INTO PurchaseItem
VALUES(3, 11, 33);

CREATE VIEW topsell AS SELECT i.upc, i.category, i.price, p.date, sum(quantity) AS total
FROM Purchase p, Item i, PurchaseItem pi 
WHERE pi.upc = i.upc AND pi.receiptId = p.receiptId
GROUP BY i.upc ORDER BY sum(quantity) DESC;

CREATE VIEW OutstandingOrders AS
SELECT *
FROM Purchase
WHERE expectedDate IS NOT NULL
AND deliveredDate IS NULL;
