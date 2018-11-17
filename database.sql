DROP TABLE Customer;
CREATE TABLE Customer (cid INT PRIMARY KEY, first_name CHAR(20), last_name CHAR(20), email CHAR(50), password CHAR(20), address CHAR(100));

INSERT INTO Customer VALUES(1,'admin','admin','admin','admin','admin');
INSERT INTO Customer VALUES(2,'customer','customer','customer','customer','customer');

DROP TABLE Receipt;
CREATE TABLE Receipt (rid INT PRIMARY KEY, receipt_price DECIMAL(11,2), receipt_quantity INT, cid INT
CONSTRAINT r_cid_const FOREIGN KEY (cid) REFERENCES Customer(cid));

DROP TABLE Payment;
CREATE TABLE Payment (payment_id INT PRIMARY KEY, credit_card_num CHAR(20), exp_date DATE, cvv CHAR(5), name_on_card CHAR(100), amount DECIMAL(11,2), rid INT
CONSTRAINT payment_rid_const FOREIGN KEY (rid) REFERENCES Receipt(rid));

DROP TABLE Department;
CREATE TABLE Department (did INT PRIMARY KEY, dep_name CHAR(100));

DROP TABLE Product;
CREATE TABLE Product (pid INT PRIMARY KEY, product_name CHAR(100), price DECIMAL(11,2), quantity INT, did INT
CONSTRAINT p_did_const FOREIGN KEY (did) REFERENCES Department(did));

DROP TABLE OrderedProduct;
CREATE TABLE OrderedProduct (opid INT PRIMARY KEY, quantity INT, pid INT, rid INT
CONSTRAINT op_pid_const FOREIGN KEY (pid) REFERENCES Product(pid)
CONSTRAINT op_rid_const FOREIGN KEY (rid) REFERENCES Receipt(rid));

DROP TABLE Supplier;
CREATE TABLE Supplier (sid INT PRIMARY KEY, s_name CHAR(100), s_address CHAR(100), pid INT
CONSTRAINT s_pid_const FOREIGN KEY (pid) REFERENCES Product(pid));
