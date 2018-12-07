DROP TABLE Supplier;
DROP TABLE OrderedProduct;
DROP TABLE Product;
DROP TABLE Department;
DROP TABLE Payment;
DROP TABLE Receipt;
DROP TABLE Customer;

DROP SEQUENCE cidSeq;
DROP SEQUENCE ridSeq;
DROP SEQUENCE payment_idSeq;
DROP SEQUENCE didSeq;
DROP SEQUENCE pidSeq;
DROP SEQUENCE opidSeq;
DROP SEQUENCE sidSeq;


CREATE TABLE Customer (cid INT PRIMARY KEY, first_name CHAR(20), last_name CHAR(20), email CHAR(50), password CHAR(20), address CHAR(100));
CREATE SEQUENCE cidSeq START WITH 1;

CREATE TABLE Receipt (rid INT PRIMARY KEY, receipt_price DECIMAL(11,2), cid INT, FOREIGN KEY(cid) REFERENCES Customer(cid));
CREATE SEQUENCE ridSeq START WITH 1;

CREATE TABLE Payment (payment_id INT PRIMARY KEY, credit_card_num CHAR(20), exp_date DATE, cvv CHAR(5), name_on_card CHAR(100), amount DECIMAL(11,2), rid INT, FOREIGN KEY(rid) REFERENCES Receipt(rid));
CREATE SEQUENCE payment_idSeq START WITH 1;

CREATE TABLE Department (did INT PRIMARY KEY, dep_name CHAR(100));
CREATE SEQUENCE didSeq START WITH 1;

CREATE TABLE Product (pid INT PRIMARY KEY, product_name CHAR(100), price DECIMAL(11,2), quantity INT, did INT, FOREIGN KEY(did) REFERENCES Department(did));
CREATE SEQUENCE pidSeq START WITH 1;

CREATE TABLE OrderedProduct (opid INT PRIMARY KEY, quantity INT, pid INT, rid INT, FOREIGN KEY(pid) REFERENCES Product(pid), FOREIGN KEY(rid) REFERENCES Receipt(rid));
CREATE SEQUENCE opidSeq START WITH 1;

CREATE TABLE Supplier (sid INT PRIMARY KEY, s_name CHAR(100), s_address CHAR(100), pid INT, FOREIGN KEY(pid) REFERENCES Product(pid));
CREATE SEQUENCE sidSeq START WITH 1;


INSERT INTO Customer VALUES(cidSeq.nextval,'admin','admin@admin.com','admin','admin','admin');
INSERT INTO Customer VALUES(cidSeq.nextval,'customer2','customer2','customer2','customer2','customer2');

INSERT INTO Receipt VALUES (ridSeq.nextval, 46.5, 2);

INSERT INTO Payment VALUES (payment_idSeq.nextval, '1234', TO_DATE('2005/06/03 00:00:00', 'yyyy/mm/dd hh24:mi:ss'), 123, 'Jordan Holleman', 46.5, 1);

INSERT INTO Department VALUES(didSeq.nextval, 'Accessories');

INSERT INTO Product VALUES(pidSeq.nextval, 'Hat', 15.50, 5, 1);

INSERT INTO OrderedProduct Values (opidSeq.nextval, 3, 1, 1);

INSERT INTO Supplier VALUES(sidSeq.nextval, 'Accessory Store','123 1st Ave', 1);
