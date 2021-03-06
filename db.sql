CREATE TABLE address
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    street VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL,
    zip INT NOT NULL,
    phone VARCHAR(255) NOT NULL,
    cell VARCHAR(255) NOT NULL,
    fax VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL
);
CREATE UNIQUE INDEX unique_id ON address (id);
