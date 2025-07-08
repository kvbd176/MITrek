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
  distributor_name VARCHAR(100),
  sl_no INT,
  name VARCHAR(100),
  stock_entry_date DATE,
  mfg_date DATE,
  exp_date DATE,
  cost DECIMAL(10,2),
  batch_no VARCHAR(50),
  company_name VARCHAR(100),
  sold TINYINT DEFAULT 0
);

CREATE TABLE customers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  phone VARCHAR(15),
  address VARCHAR(255)
);

CREATE TABLE sales (
  id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT,
  medicine_id INT,
  quantity INT,
  sold_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES customers(id),
  FOREIGN KEY (medicine_id) REFERENCES medicines(id)
);

select * from users;
select * from medicines;
select * from customers;
select * from sales;


