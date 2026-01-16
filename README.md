# Vendex – Full Stack eCommerce Website

Vendex is a full-stack eCommerce web application built using PHP and MySQL. It provides a complete product selling platform with separate user and admin panels, secure authentication, order management, and a responsive user interface.

This project demonstrates core full-stack development concepts including server-side logic, database design, authentication, and role-based access control.

---

## Features

### User Side
- User registration and login
- Product browsing and search
- Add to cart and checkout
- Order placement and order history
- Digital product downloads
- User profile management
- Contact form and FAQ section

### Admin Side
- Secure admin authentication
- Admin dashboard
- Product management (Create, Read, Update, Delete)
- Order management
- User management
- Coupon and discount management
- Support ticket handling

---

## Tech Stack
- Frontend: HTML, CSS, Bootstrap 5, JavaScript
- Backend: PHP
- Database: MySQL
- Styling: Custom CSS with responsive design

---

## Installation & Setup

### 1. Clone or Download the Repository
git clone https://github.com/your-username/Vendex.git

Or download the ZIP and extract it.

---

### 2. Database Setup
- Create a MySQL database named:
vendex
- Import the database.sql file into the database

---

### 3. Configure Database Connection
Edit the file:
app/config/config.php

Update database credentials if required:
define('DB_HOST', 'localhost');
define('DB_NAME', 'vendex');
define('DB_USER', 'root');
define('DB_PASS', '');

---

### 4. Server Configuration
- Move the project folder to your web server root
  - XAMPP: htdocs/
  - WAMP: www/
- Ensure the following directories are writable:
public/storage/
storage/

---

### 5. Run the Application
Open your browser and visit:
http://localhost/vendex/public/

---

## Default Login Credentials

### Admin
Email: admin@vendex.com  
Password: admin  

### User
Create a new account using the signup page

---

## Project Structure

vendex/
├── app/
│   ├── config/
│   │   ├── config.php
│   │   └── core/
│   │       ├── Database.php
│   │       ├── controllers/
│   │       └── models/
│   └── views/
├── public/
│   ├── index.php
│   ├── assets/
│   │   ├── css/
│   │   ├── js/
│   │   └── images/
│   └── storage/
├── storage/
├── database.sql
└── README.md

---

## Key Highlights
- Fully functional user and admin roles
- Secure authentication using PHP sessions
- Password hashing using password_hash()
- Prepared statements to prevent SQL injection
- Responsive design for mobile and desktop
- MVC-style project structure
- Secure file upload handling for digital products

---

## Security Notes
- Passwords are securely hashed
- Admin routes are protected
- Input validation and prepared SQL queries
- Restricted file upload handling

---

## Browser Compatibility
- Google Chrome
- Mozilla Firefox
- Microsoft Edge
- Safari

---

## License
This project is developed for educational purposes.
You are free to modify and use it for learning or portfolio projects.
