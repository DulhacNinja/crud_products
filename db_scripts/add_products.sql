show databases;
use crud_products_db;
describe products;

INSERT INTO products (name, description, price, is_in_stock)
VALUES
    ('wine', 'fermented grape juice that makes you fancy and drunk', 1500, true),
    ('water', 'liquid that keeps you alive', 100, true),
    ('coffee', 'liquid that makes you wake up', 350, true),
    ('whiskey', 'liquid that makes you drunk, but slower and more expensive', 4500, false),
    ('orange juice', 'liquid that makes you feel healthy', 450, true),
    ('energy drink', 'liquid that makes you regret your heart rate', 400, false);

select * from products;