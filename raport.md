# KlinoffRoad Project Report

## Front Page

**Topic:** KlinoffRoad Web Application  
**Authors:**  
- Miska Mero (Student Number: AF5692)  
- Jooa Akonpelto (Student Number: AF5691)  
**Course Code:** LV2425

## 1. Idea and Solutions Behind the Designed Database Model

### Database Model Overview

The KlinoffRoad project utilizes a relational database model to manage users, products, carts, and orders. The database schema includes the following tables:

1. **Users**
   - **UserID**: INT, Primary Key, Auto Increment
   - **Username**: VARCHAR(50), Unique, Not Null
   - **PasswordHash**: VARCHAR(255), Not Null

2. **Products**
   - **ProductID**: INT, Primary Key, Auto Increment
   - **ProductName**: VARCHAR(100), Not Null
   - **Price**: DECIMAL(10, 2), Not Null
   - **Stock**: INT, Default 0
   - **Description**: TEXT

3. **Carts**
   - **CartID**: INT, Primary Key, Auto Increment
   - **UserID**: INT, Unique, Not Null, Foreign Key (References Users)
   - **Items**: JSON
   - **LastUpdated**: TIMESTAMP, Default CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

4. **Orders**
   - **OrderID**: INT, Primary Key, Auto Increment
   - **UserID**: INT, Not Null, Foreign Key (References Users)
   - **CartID**: INT, Not Null, Foreign Key (References Carts)
   - **Items**: JSON
   - **Total**: DECIMAL(10, 2), Not Null
   - **OrderDate**: TIMESTAMP, Default CURRENT_TIMESTAMP

### Connections and Relationships

- **Users** table is connected to **Carts** and **Orders** tables through the **UserID** field.
- **Carts** table is connected to **Orders** table through the **CartID** field.
- **Products** table is referenced in the **Items** field of **Carts** and **Orders** tables, which store product details in JSON format.

### Solutions

- **Normalization**: The database is normalized to reduce redundancy and ensure data integrity.
- **Security**: Passwords are stored as hashed values to enhance security.
- **Scalability**: The use of JSON for storing cart items allows flexibility in managing varying quantities of products.

## 2. Summary of the Progress

### Progress Overview

The project progressed through several stages, including planning, database design, implementation, and testing. The following summarizes the key milestones and challenges faced:

### Challenges and Solutions

1. **Database Design**:
   - **Challenge**: Designing a flexible and scalable database schema.
   - **Solution**: Implemented a normalized schema with JSON fields for dynamic data storage.

2. **User Authentication**:
   - **Challenge**: Ensuring secure user authentication and data encryption.
   - **Solution**: Used PHP `password_hash` and `password_verify` functions for secure password handling.

3. **Product Management**:
   - **Challenge**: Efficiently managing product inventory and updates.
   - **Solution**: Implemented CRUD operations with prepared statements to prevent SQL injection.

4. **Cart and Order Processing**:
   - **Challenge**: Handling dynamic cart contents and order processing.
   - **Solution**: Used JSON to store cart items and implemented robust order processing logic.

### Grade Proposal

**Proposed Grade**: 5/5

**Arguments**:
- **Comprehensive Implementation**: The project meets all specified requirements and includes additional features such as product search and user reviews.
- **Security**: Implemented secure authentication and data encryption mechanisms.
- **Usability**: Developed a user-friendly interface with responsive design.
- **Documentation**: Provided detailed documentation, including a specification of requirements and database model.

## 3. Appendices

### Appendix A: Specification of Requirements Document

[Specification of Requirements](specification_of_requirements.md)

### Appendix B: Database Model Document

```sql
-- Users Table
CREATE TABLE users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL
);

-- Products Table
CREATE TABLE products (
    ProductID INT AUTO_INCREMENT PRIMARY KEY,
    ProductName VARCHAR(100) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    Stock INT DEFAULT 0,
    Description TEXT
);

-- Carts Table
CREATE TABLE carts (
    CartID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL UNIQUE,
    Items JSON,
    LastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE CASCADE
);

-- Orders Table
CREATE TABLE orders (
    OrderID INT AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,
    CartID INT NOT NULL,
    Items JSON,
    Total DECIMAL(10, 2) NOT NULL,
    OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE CASCADE,
    FOREIGN KEY (CartID) REFERENCES carts(CartID) ON DELETE CASCADE
);