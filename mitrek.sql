create database meditrack;
use meditrack;
create table users (
    id int auto_increment primary key,
    username varchar(50) not null,
    email varchar(100) not null,
    store_name varchar(100) not null,
    password varchar(255) not null
);

CREATE TABLE medicines (
  id INT AUTO_INCREMENT PRIMARY KEY,
  store_name varchar(100),
  medicine_name VARCHAR(100),
  sl_no INT,
  stock_entry_date DATE,
  mfg_date DATE,
  exp_date DATE,
  cost DECIMAL(10,2),
  batch_no VARCHAR(50),
  distributor_name VARCHAR(100),
  sold INT DEFAULT 0,
  quantity INT,
  medid INT
);
CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  phone VARCHAR(15),
  address VARCHAR(255)
);
ALTER TABLE customers ADD COLUMN store_name VARCHAR(100);

CREATE TABLE sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT,
  medicine_id INT,
  quantity INT,
  sold_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (medicine_id) REFERENCES medicines(id)
);
ALTER TABLE sales ADD bill_id INT;
ALTER TABLE sales ADD FOREIGN KEY (bill_id) REFERENCES bills(bill_id);

select * from users;
select * from medicines;
select * from customers;
select * from sales;

CREATE TABLE bills (
  bill_id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT NOT NULL,
  total_amount DECIMAL(10, 2) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id)
);

CREATE TABLE contact(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    submitted_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
select * from contact;



