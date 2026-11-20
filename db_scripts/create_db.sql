CREATE DATABASE crud_products_db;
USE crud_products_db;
CREATE TABLE products(
	id INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
	`description` VARCHAR(255) DEFAULT NULL,
    price  INT NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    availability_date DATE DEFAULT NULL,
    is_in_stock BOOLEAN DEFAULT FALSE
);
DESCRIBE products;