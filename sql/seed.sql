
-- Add customers
INSERT INTO customers (name, email, password, phone, address)
VALUES ('Alice Johnson', 'alice@mail.com', '123456', '0123456789', '123 Main St');

-- Add products
INSERT INTO products (name, description, price, stock_quantity, category)
VALUES ('Laptop', '14-inch gaming laptop', 1200.00, 10, 'Electronics');

-- Add order
INSERT INTO orders (customer_id, status, total_amount)
VALUES (1, 'Pending', 1200.00);

-- Add order item
INSERT INTO order_items (order_id, product_id, quantity, price)
VALUES (1, 1, 1, 1200.00);

-- Add payment
INSERT INTO payments (order_id, amount, status, transaction_ref)
VALUES (1, 1200.00, 'Completed', 'TXN12345');
