# Database Setup Guide

## How to Create the Database in MySQL

### Method 1: Using phpMyAdmin (Recommended for Beginners)

1. **Open phpMyAdmin**
   - Start XAMPP
   - Open your browser and go to: `http://localhost/phpmyadmin`

2. **Import the SQL file**
   - Click on the "Import" tab at the top
   - Click "Choose File" button
   - Navigate to: `D:\xampp\htdocs\BTLMN\database.sql`
   - Click "Go" button at the bottom
   - Wait for the success message

3. **Verify the database**
   - You should see a new database called `ky_tuc_xa` in the left sidebar
   - Click on it to see all the tables: `users`, `sinhvien`, `phong`, `dangky`, `thanhtoan`

### Method 2: Using MySQL Command Line

1. **Open Command Prompt/Terminal**

2. **Navigate to MySQL bin directory** (if MySQL is not in PATH)
   ```bash
   cd C:\xampp\mysql\bin
   ```

3. **Login to MySQL**
   ```bash
   mysql -u root -p
   ```
   (Press Enter when asked for password, or enter your MySQL root password)

4. **Run the SQL file**
   ```sql
   source D:/xampp/htdocs/BTLMN/database.sql
   ```
   OR
   ```sql
   \. D:/xampp/htdocs/BTLMN/database.sql
   ```

5. **Verify**
   ```sql
   SHOW DATABASES;
   USE ky_tuc_xa;
   SHOW TABLES;
   ```

### Method 3: Using MySQL Workbench

1. **Open MySQL Workbench**
2. **Connect to your MySQL server**
3. **Click on "File" → "Open SQL Script"**
4. **Select**: `D:\xampp\htdocs\BTLMN\database.sql`
5. **Click the "Execute" button** (⚡ icon) or press `Ctrl+Shift+Enter`

### Method 4: Copy and Paste SQL Commands

1. **Open phpMyAdmin**
2. **Click on "SQL" tab**
3. **Open the file** `database.sql` in a text editor
4. **Copy all the content**
5. **Paste it into the SQL tab**
6. **Click "Go"**

## Verification

After setup, verify the database by running:

```sql
USE ky_tuc_xa;

-- Check all tables
SHOW TABLES;

-- Check users table
SELECT * FROM users;

-- Check rooms table
SELECT * FROM phong;

-- Check students table (should be empty initially)
SELECT COUNT(*) FROM sinhvien;
```

## Default Login Credentials

After database setup, you can login with:

- **Username**: `admin`
- **Password**: `admin123`

OR

- **Username**: `staff1`
- **Password**: `staff123`

## Troubleshooting

### Error: "Database already exists"
If you get this error, you can either:
1. Drop the existing database first:
   ```sql
   DROP DATABASE ky_tuc_xa;
   ```
   Then run the SQL file again.

2. Or modify the SQL file to use `CREATE DATABASE IF NOT EXISTS` (already included)

### Error: "Access denied"
Make sure you're using the correct MySQL username and password. Default XAMPP MySQL credentials:
- Username: `root`
- Password: (empty/blank)

### Error: "Foreign key constraint fails"
This usually means tables are being created in the wrong order. The current SQL file handles this correctly by creating parent tables first.

## Database Configuration in Application

Make sure your `functions/connect.php` file has the correct database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ky_tuc_xa');
```

## Next Steps

1. ✅ Database created
2. ✅ Tables created
3. ✅ Default data inserted
4. 🔄 Configure database connection in `functions/connect.php` if needed
5. 🚀 Access the application at: `http://localhost/BTLMN/`



