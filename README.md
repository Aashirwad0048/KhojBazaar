
# KhojBazaar

KhojBazaar is a business website project designed to facilitate buying, selling, and connecting users with various services. Built using PHP, it provides essential features for user registration, authentication, and secure data management.

## Features
- User registration and login
- Password reset functionality
- Dashboard for users
- Contact and privacy pages
- Media uploads (images, videos)
- Database integration
- Secure session management

## Project Structure
- `index.php` — Homepage
- `register.php` — User registration
- `login.php` — User login
- `forgot_password.php` — Password reset
- `dashboard.php` — User dashboard
- `contact.php` — Contact form
- `privacy.php` — Privacy policy
- `sell.php` — Sell products/services
- `uploads/` — Uploaded media files
- `db.php` — Database connection
- `create_db.php` — Database setup

## Requirements
- PHP 7.x or higher
- MySQL database
- Web server (e.g., XAMPP, Apache)

## Setup Instructions
## Installation & Setup Guide

Follow these steps to install and run KhojBazaar on your local machine:

### 1. Prerequisites
- Install [XAMPP](https://www.apachefriends.org/index.html) or any web server with PHP and MySQL support.
- Make sure PHP 7.x or higher is available.

### 2. Download the Project
- Clone the repository using:
	```pwsh
	git clone https://github.com/Aashirwad0048/KhojBazaar.git
	```
- Or download the ZIP file from the GitHub page.
- Extract the contents to your web server directory (e.g., `C:/xampp/htdocs/buisnesswebsite`).

### 3. Configure the Database
- Start Apache and MySQL from the XAMPP control panel.
- Open your browser and go to `http://localhost/phpmyadmin`.
- Create a new database (e.g., `buisnesswebsite`).
- Import the database tables by running `create_db.php` or manually importing an SQL file if provided.

### 4. Update Database Credentials
- Open `db.php` in a text editor.
- Set your database name, username, and password as per your local setup.

### 5. Run the Website
- Visit `http://localhost/buisnesswebsite` in your browser.
- Register a new account and start using the platform.

### 6. Troubleshooting
- If you encounter errors, check your PHP and MySQL versions.
- Ensure all required files are present in the project directory.
- Review the configuration in `db.php` for correct credentials.

## Usage
- Register a new account or log in with existing credentials.
- Use the dashboard to manage your profile and listings.
- Upload images or videos as needed.
- Contact support via the contact page.

## License
This project is for educational and demonstration purposes. Feel free to modify and use as needed.
