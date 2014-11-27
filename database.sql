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
VALUES(1,'name', '$2a$10$btcjSYCmGdZomibJBQAG5eDZrnMHunu8He/5ozjDdf8Lpzx5m.2l.', 'Justin Bieber', 'somewhere', 5017);
INSERT INTO Customer
VALUES(2,'Bieber', '$2a$10$eSkPPfOyx0i9Gl.4G8gs4e0v/n84tQB4KCORosn/e6.c3yh2Wmyv.', 'Kenneth', 'home', 56132);
INSERT INTO Customer
VALUES(3,'something', '$2a$10$jDIwMuNQKj4hik4vP1wX.eIfCflJZuNNsBi9ieyFqQuDOLo0TqDR2', 'Olivia', 'Mars', 10823);
INSERT INTO Customer
VALUES(4, 'thatthing', '$2a$10$wXifjBmTeUTW8VIdLADwB.ng1RTCcvPOBa8pXZzTlxEoS7SOR6Uoe', 'April', 'Sun', 135155);
INSERT INTO Customer
VALUES(5, 'aname', '$2a$10$BTyr70BWObZPWCsyg50LCOhBEHwa8VUceHtjeJkZJVqEIGOkGO3gm', 'Daniel', 'Canada', 16555);
INSERT INTO Customer
VALUES(6, 'thatname', '$2a$10$QTmXTjIUYNg/OpvuT8IcYeSesktc5v02TAGbf/D9cMQxIQRVA.UQe', 'Tom', 'nowhere', 549412);
INSERT INTO Customer
VALUES(7, 'toolazy', '$2a$10$/4l5kpdRBc4bbkb6MzCCweykrSA9v96kk/Vn10G3rz.N9JRvijUAC', 'Bob', 'there', 515161);
INSERT INTO Customer
VALUES(8, 'somdsa', '$2a$10$5098/UUE5BSnNaUZmvyQLe/vwyGfnOHUhmf8gBLOddi8j04VqEBsu', 'George', 'here', 575415);

INSERT INTO Purchase
VALUES(1, CURRENT_DATE(),	1, 16516161, CURRENT_DATE()+3, CURRENT_DATE()+4, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(2, CURRENT_DATE() -10,	2, 16516161, CURRENT_DATE()+3, CURRENT_DATE()+4, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(3, CURRENT_DATE() - 4,	3, 16516161, CURRENT_DATE()+3, CURRENT_DATE()+4, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(4, CURRENT_DATE() - 2,	4, 16516161, CURRENT_DATE()+3, CURRENT_DATE()+4, CURRENT_DATE()+2);
INSERT INTO Purchase
VALUES(5, CURRENT_DATE() - 2,	2, 16516161, CURRENT_DATE()+3, CURRENT_DATE()+4, CURRENT_DATE()+2);

INSERT INTO PurchaseItem
VALUES(1, 1, 20);
INSERT INTO PurchaseItem
VALUES(1, 2, 20);
INSERT INTO PurchaseItem
VALUES(1, 3, 40);
INSERT INTO PurchaseItem
VALUES(1, 4, 40);
INSERT INTO PurchaseItem
VALUES(2, 5, 2);
INSERT INTO PurchaseItem
VALUES(2, 6, 50);
INSERT INTO PurchaseItem
VALUES(3, 7, 50);
INSERT INTO PurchaseItem
VALUES(3, 8, 32);
INSERT INTO PurchaseItem
VALUES(3, 9, 20);
INSERT INTO PurchaseItem
VALUES(3, 10, 12);
INSERT INTO PurchaseItem
VALUES(4, 11, 5);
INSERT INTO PurchaseItem
VALUES(4, 12, 23);
INSERT INTO PurchaseItem
VALUES(4, 13, 8);
INSERT INTO PurchaseItem
VALUES(4, 1, 8);
INSERT INTO PurchaseItem
VALUES(4, 2, 12);
INSERT INTO PurchaseItem
VALUES(4, 3, 30);
INSERT INTO PurchaseItem
VALUES(4, 5, 21);
INSERT INTO PurchaseItem
VALUES(4, 6, 33);
INSERT INTO PurchaseItem
VALUES(5, 11, 14);
INSERT INTO PurchaseItem
VALUES(5, 12, 27);
INSERT INTO PurchaseItem
VALUES(5, 13, 1);
INSERT INTO PurchaseItem
VALUES(5, 1, 9);
INSERT INTO PurchaseItem
VALUES(5, 2, 22);
INSERT INTO PurchaseItem
VALUES(5, 3, 80);
INSERT INTO PurchaseItem
VALUES(5, 5, 21);
INSERT INTO PurchaseItem
VALUES(5, 6, 53);

CREATE VIEW topsell AS SELECT i.upc, i.category, i.price, p.date, sum(quantity) AS total
FROM Purchase p, Item i, PurchaseItem pi 
WHERE pi.upc = i.upc AND pi.receiptId = p.receiptId
GROUP BY i.upc, p.date ORDER BY sum(quantity) DESC;

CREATE VIEW OutstandingOrders AS
SELECT *
FROM Purchase
WHERE expectedDate IS NOT NULL
AND deliveredDate IS NULL;
