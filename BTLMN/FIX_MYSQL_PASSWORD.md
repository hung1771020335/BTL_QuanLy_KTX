# Fix MySQL Access Denied Error

## Problem
You're getting this error:
```
Access denied for user 'root'@'localhost' (using password: NO)
```

This means MySQL is requiring a password but your application is trying to connect without one.

## Solutions

### Solution 1: Set MySQL Root Password to Empty (Recommended for XAMPP)

**Option A: Using XAMPP Control Panel**
1. Open XAMPP Control Panel
2. Stop MySQL server
3. Open MySQL configuration file:
   - Click "Config" button next to MySQL
   - Select "my.ini" or "my.cnf"
4. Find the `[mysqld]` section
5. Add or update this line:
   ```
   skip-grant-tables
   ```
6. Save the file
7. Start MySQL server
8. Open phpMyAdmin: `http://localhost/phpmyadmin`
9. Run this SQL command:
   ```sql
   USE mysql;
   UPDATE user SET authentication_string='' WHERE User='root';
   UPDATE user SET plugin='mysql_native_password' WHERE User='root';
   FLUSH PRIVILEGES;
   ```
10. Remove `skip-grant-tables` from my.ini
11. Restart MySQL

**Option B: Using phpMyAdmin**
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click on "User accounts" tab
3. Find "root" user with host "localhost"
4. Click "Edit privileges"
5. Click "Change password" tab
6. Leave password fields empty
7. Click "Go"
8. Restart MySQL in XAMPP

**Option C: Reset MySQL Password via Command Line**
1. Open Command Prompt as Administrator
2. Navigate to XAMPP MySQL bin:
   ```bash
   cd C:\xampp\mysql\bin
   ```
3. Stop MySQL in XAMPP Control Panel
4. Start MySQL in safe mode:
   ```bash
   mysqld --skip-grant-tables
   ```
5. Open a new Command Prompt window
6. Connect to MySQL:
   ```bash
   mysql -u root
   ```
7. Run these commands:
   ```sql
   USE mysql;
   UPDATE user SET authentication_string='' WHERE User='root';
   UPDATE user SET plugin='mysql_native_password' WHERE User='root';
   FLUSH PRIVILEGES;
   EXIT;
   ```
8. Close both Command Prompt windows
9. Restart MySQL normally in XAMPP

### Solution 2: Set Password in Application

If you want to keep a MySQL password, update the connection file:

1. Open `functions/connect.php`
2. Find line 13:
   ```php
   define('DB_PASS', '');  // Change this to your MySQL password
   ```
3. Update it to:
   ```php
   define('DB_PASS', 'your_mysql_password');
   ```
4. Save the file

### Solution 3: Create New MySQL User (Advanced)

If you can't reset root password, create a new user:

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "User accounts" tab
3. Click "Add user account"
4. Set:
   - Username: `dormitory_user`
   - Hostname: `localhost`
   - Password: (leave empty or set one)
5. Grant ALL privileges on database `ky_tuc_xa`
6. Click "Go"
7. Update `functions/connect.php`:
   ```php
   define('DB_USER', 'dormitory_user');
   define('DB_PASS', '');
   ```

## Quick Test

After applying a solution, test the connection:

1. Open: `http://localhost/BTLMN/test_connection.php`
2. Check if all tests pass
3. If successful, try: `http://localhost/BTLMN/login.php`

## Recommended for XAMPP

For XAMPP development, **Solution 1 (Option B)** is the easiest:
- Open phpMyAdmin
- Set root password to empty
- Keep your connection file as is (empty password)

## Still Having Issues?

1. Check XAMPP MySQL is running
2. Try accessing phpMyAdmin: `http://localhost/phpmyadmin`
3. If phpMyAdmin doesn't work, MySQL has a configuration issue
4. Check XAMPP error logs for more details



