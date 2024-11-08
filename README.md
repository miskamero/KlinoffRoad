# <img src="assets/favicon.png" width="35px"> KlinoffRoad

KlinoffRoad is a web application for off-road enthusiasts to shop for products, manage their carts, and complete purchases. This project uses PHP and MySQL and is designed to run on a local XAMPP server.

## Table of Contents

- [Installation](#installation)
- [Database Setup](#database-setup)
- [Configuration](#configuration)
- [Usage](#usage)
- [Important Note](#important-note)

## Installation

1. **Download and Install XAMPP:**
   - Download XAMPP from [Apache Friends](https://www.apachefriends.org/index.html) and install it on your local machine.

2. **Clone the Repository:**
   - Clone this repository to your local machine:

    ```sh
     git clone https://github.com/miskamero/course-exercise-JAMK.git
     ```

3. **Move the Project to XAMPP's `htdocs` Directory:**
   - Move the cloned project folder to the `htdocs` directory of your XAMPP installation. For example:

     ```sh
     mv course-exercise-JAMK C:\xampp\htdocs\klinoffroad
     ```

## Database Setup

1. **Start Apache and MySQL:**
   - Open the XAMPP Control Panel and start the Apache and MySQL services.

2. **Create the Database:**
   - Open phpMyAdmin by navigating to `http://localhost/klinoffroad` in your web browser.
   - Create a new database named `klinoffroad`.

3. **Import the Database Schema:**
   - In phpMyAdmin, select the `klinoffroad` database.
   - Click on the "Import" tab and import the `klinoffroad.sql` file located in the project root directory.

4. **Insert Example Data:**
   - Optionally, you can insert example data by running the SQL commands in the `exampleDataInsertions.txt` file.

## Configuration

##### Encryption Key:

- Ensure the `key.klinoff` file is present in the project root directory. This file contains the encryption key used for encrypting and decrypting sensitive data.

## Usage

1. **Access the Application:**
   - Open your web browser and navigate to `http://localhost/klinoffroad`.

2. **User Registration and Login:**
   - Register a new user or log in with existing credentials.

3. **Shopping:**
   - Browse products, add items to your cart, and proceed to checkout.

4. **Admin Panel:**
   - Log in as the admin user to manage products and users.

## Important Note

- **Admin Credentials:**
  - you need to create an admin user in the database manually.
