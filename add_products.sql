

show databases;
use crud_products_db;
describe products;

INSERT INTO products (name, description, price, is_in_stock) 
VALUES
(
		"beer",
        "liquid that makes you drunk",
        600,
        true
);

select * from products;