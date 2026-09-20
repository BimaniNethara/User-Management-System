# User-Management-System

## Description

This is a web-based User Management System developed using HTML,css, Bootstrap, JavaScript, PHP, and MySQL.

The system allows users to:
- Add new user records
- View all users
- Filter users by country and birthday
- Edit existing user records
- Validate user input

## Technologies Used

- HTML5
- Bootstrap 5
- JavaScript
- PHP
- MySQL
- XAMPP

  ## Database Configuration

1. Start Apache and MySQL using XAMPP.
2. Open phpMyAdmin.
3. Import the `database.sql` file.
4. The database `user` and the `users` table will be created.

Database configuration is stored in: config/database.php

## How to Run

1. Copy the `user-management` folder into the XAMPP `htdocs` folder.

2. Start Apache and MySQL in XAMPP.

3. Import `database.sql` using phpMyAdmin.

4. Open the application in a web browser:

http://localhost:8080/user-management/index.html

## Application Pages

- Registration Page – Add a new user
- User List Page – View and filter users
- Edit Page – Update an existing user
