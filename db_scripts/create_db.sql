CREATE DATABASE crud_products_db;
USE crud_products_db;
CREATE TABLE products(
	id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
	description VARCHAR(255) DEFAULT NULL,
    price  INT NOT NULL,
    imagePath VARCHAR(255) DEFAULT NULL,
    availabilityDate DATE DEFAULT NULL,
    isInStock BOOLEAN DEFAULT NULL
);
DESCRIBE products;