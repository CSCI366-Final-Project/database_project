DROP TABLE Customer;
CREATE TABLE Customer (cid INT PRIMARY KEY, first_name CHAR(20), last_name CHAR(20), email CHAR(50), password CHAR(20), address CHAR(100));

INSERT INTO Customer VALUES(1,'admin','admin@admin.com','admin','admin','admin');
INSERT INTO Customer VALUES(2,'customer','customer','customer','customer','customer');

DROP TABLE Receipt;
CREATE TABLE Receipt (rid INT PRIMARY KEY, receipt_price DECIMAL(11,2), receipt_quantity INT, FOREIGN KEY(cid) REFERENCES Customer(cid));

DROP TABLE Payment;
CREATE TABLE Payment (payment_id INT PRIMARY KEY, credit_card_num CHAR(20), exp_date DATE, cvv CHAR(5), name_on_card CHAR(100), amount DECIMAL(11,2), FOREIGN KEY(rid) REFERENCES Receipt(rid));

DROP TABLE Department;
CREATE TABLE Department (did INT PRIMARY KEY, dep_name CHAR(100));

INSERT INTO Department VALUES(11, 'Accessories');

DROP TABLE Product;
CREATE TABLE Product (pid INT PRIMARY KEY, product_name CHAR(100), price DECIMAL(11,2), quantity INT, FOREIGN KEY(did) REFERENCES Department(did));

INSERT INTO Product VALUES(111, 'Hat', 15.50, 5, 11);

DROP TABLE OrderedProduct;
CREATE TABLE OrderedProduct (opid INT PRIMARY KEY, quantity INT, pid INT, rid INT, FOREIGN KEY(pid) REFERENCES Product(pid), FOREIGN KEY(rid) REFERENCES Receipt(rid));

DROP TABLE Supplier;
CREATE TABLE Supplier (sid INT PRIMARY KEY, s_name CHAR(100), s_address CHAR(100), FOREIGN KEY(pid) REFERENCES Product(pid));

INSERT INTO Supplier VALUES(1111, 'Accessory Store','123 1st Ave', 111);
