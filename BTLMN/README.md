# College Dormitory Management System

A complete web application for managing college dormitories built with PHP and MySQL.

## Features

- **Student Management**: Add, edit, delete, and view student information
- **Room Management**: Manage dormitory rooms with details like type, capacity, price, and status
- **Registration Management**: Handle student room registrations with status tracking
- **Payment Management**: Track payments for room registrations
- **User Authentication**: Secure login system with session management
- **Dashboard**: Overview statistics of students, rooms, and registrations

## Technology Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL
- **Frontend**: HTML5, CSS3, Bootstrap 5.3.2
- **Architecture**: MVC-like pattern with separation of concerns

## Project Structure

```
BTLMN/
├── functions/              # Core functions
│   ├── connect.php        # Database connection
│   └── helpers.php        # Helper functions
├── handle/                # Process handlers (Controllers)
│   ├── login_process.php
│   ├── logout_process.php
│   ├── student_process.php
│   ├── room_process.php
│   ├── registration_process.php
│   └── payment_process.php
├── views/                  # View files (Templates)
│   ├── student/
│   │   ├── student.php
│   │   ├── create_student.php
│   │   └── edit_student.php
│   ├── room/
│   │   ├── room.php
│   │   ├── create_room.php
│   │   └── edit_room.php
│   ├── registration/
│   │   ├── registration.php
│   │   ├── create_registration.php
│   │   └── edit_registration.php
│   └── payment/
│       ├── payment.php
│       ├── create_payment.php
│       └── edit_payment.php
├── css/                    # Stylesheets
│   ├── style.css
│   └── style_login.css
├── index.php              # Dashboard
├── login.php              # Login page
├── logout.php             # Logout handler
└── database.sql           # Database schema
```

## Installation

1. **Prerequisites**
   - XAMPP (or any PHP/MySQL server)
   - PHP 7.4 or higher
   - MySQL 5.7 or higher

2. **Database Setup**
   - Create a MySQL database
   - Import `database.sql` file
   - Update database credentials in `functions/connect.php` if needed

3. **Configuration**
   - Open `functions/connect.php`
   - Update database connection details:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'ky_tuc_xa');
     ```

4. **Access the Application**
   - Start Apache and MySQL in XAMPP
   - Navigate to: `http://localhost/BTLMN/`
   - Default login credentials:
     - Username: `admin`
     - Password: `admin123`

## Database Schema

### Tables

1. **users**: System administrators and staff
2. **sinhvien**: Student information
3. **phong**: Room details
4. **dangky**: Room registrations
5. **thanhtoan**: Payment records

## Default Users

- **Admin**: username: `admin`, password: `admin123`
- **Staff**: username: `staff1`, password: `staff123`

## Features by Module

### Student Management
- Add new students with complete information
- Edit student details
- Delete students
- View all students in a table

### Room Management
- Create rooms with type (standard/premium/VIP)
- Set room capacity (number of beds)
- Set monthly price
- Track room status (available/occupied/maintenance)
- Edit and delete rooms

### Registration Management
- Register students to rooms
- Track registration status (pending/approved/active/completed/cancelled)
- Set start and end dates
- Automatic room status update

### Payment Management
- Record payments for registrations
- Track payment types (deposit/monthly/final)
- Record payment methods (cash/bank transfer/card)
- View payment history

## Security Features

- Session-based authentication
- SQL injection prevention (prepared statements)
- Input sanitization
- Password hashing (MD5 - consider upgrading to bcrypt for production)

## Usage

1. **Login**: Access the system with admin or staff credentials
2. **Dashboard**: View system statistics
3. **Manage Students**: Add and manage student information
4. **Manage Rooms**: Create and manage dormitory rooms
5. **Process Registrations**: Register students to rooms
6. **Handle Payments**: Record and track payments

## Notes

- The application uses Vietnamese language for UI
- All dates are stored in MySQL DATE format
- Currency is displayed in Vietnamese Dong (VND)
- The system automatically updates room status based on registrations

## Development

The application follows a clean architecture pattern:
- **Functions**: Reusable utilities and database connection
- **Handle**: Business logic and data processing
- **Views**: Presentation layer

## License

This project is for educational purposes.
