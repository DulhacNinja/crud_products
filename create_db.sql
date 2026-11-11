CREATE DATABASE crud_products_db;
USE crud_products_db;
CREATE TABLE products(
	id INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
	`description` VARCHAR(100) DEFAULT NULL,
    price  INT NOT NULL,
    image BLOB DEFAULT NULL,
    availability_date DATE DEFAULT NULL,
    is_in_stock BOOLEAN DEFAULT FALSE
);
DESCRIBE products;