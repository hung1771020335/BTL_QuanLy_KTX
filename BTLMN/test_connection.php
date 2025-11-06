<?php
/**
 * Database Connection Test
 * Use this file to test your database connection
 */

// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'ky_tuc_xa';  // Change this to your actual database name

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Connection Test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; padding: 20px; }
        .card { margin-bottom: 20px; }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; }
        code { background-color: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">🔌 Database Connection Test</h3>
                    </div>
                    <div class="card-body">
                        <h5>Current Configuration:</h5>
                        <div class="alert alert-info">
                            <strong>Host:</strong> <?= htmlspecialchars($db_host) ?><br>
                            <strong>User:</strong> <?= htmlspecialchars($db_user) ?><br>
                            <strong>Password:</strong> <?= $db_pass ? '***' : '(empty)' ?><br>
                            <strong>Database:</strong> <code><?= htmlspecialchars($db_name) ?></code>
                        </div>

                        <hr>

                        <h5>Test 1: MySQL Server Connection</h5>
                        <?php
                        // Test MySQL connection (without database)
                        $mysqli = @new mysqli($db_host, $db_user, $db_pass);
                        
                        if ($mysqli->connect_error) {
                            echo '<div class="alert alert-danger">';
                            echo '<span class="error">❌ FAILED</span><br>';
                            echo 'Error: ' . $mysqli->connect_error . '<br>';
                            echo '<strong>Solution:</strong> Make sure MySQL server is running in XAMPP Control Panel.';
                            echo '</div>';
                            $server_ok = false;
                        } else {
                            echo '<div class="alert alert-success">';
                            echo '<span class="success">✅ SUCCESS</span><br>';
                            echo 'MySQL server is running and accessible.';
                            echo '</div>';
                            $server_ok = true;
                        }
                        ?>

                        <hr>

                        <h5>Test 2: Database Existence</h5>
                        <?php
                        if ($server_ok) {
                            // Check if database exists
                            $result = $mysqli->query("SHOW DATABASES LIKE '$db_name'");
                            
                            if ($result && $result->num_rows > 0) {
                                echo '<div class="alert alert-success">';
                                echo '<span class="success">✅ SUCCESS</span><br>';
                                echo "Database '<code>$db_name</code>' exists!";
                                echo '</div>';
                                $db_exists = true;
                            } else {
                                echo '<div class="alert alert-danger">';
                                echo '<span class="error">❌ FAILED</span><br>';
                                echo "Database '<code>$db_name</code>' does not exist!<br>";
                                echo '<strong>Solution:</strong> Create the database in phpMyAdmin or MySQL.';
                                echo '</div>';
                                $db_exists = false;
                            }
                        } else {
                            $db_exists = false;
                        }
                        ?>

                        <hr>

                        <h5>Test 3: Database Connection</h5>
                        <?php
                        if ($server_ok && $db_exists) {
                            // Test connection to specific database
                            $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
                            
                            if ($conn->connect_error) {
                                echo '<div class="alert alert-danger">';
                                echo '<span class="error">❌ FAILED</span><br>';
                                echo 'Error: ' . $conn->connect_error;
                                echo '</div>';
                                $connection_ok = false;
                            } else {
                                echo '<div class="alert alert-success">';
                                echo '<span class="success">✅ SUCCESS</span><br>';
                                echo 'Successfully connected to database!';
                                echo '</div>';
                                $connection_ok = true;
                                
                                // Check tables
                                echo '<hr><h5>Test 4: Tables Check</h5>';
                                $tables = ['users', 'sinhvien', 'phong', 'dangky', 'thanhtoan'];
                                $tables_found = [];
                                $tables_missing = [];
                                
                                foreach ($tables as $table) {
                                    $result = $conn->query("SHOW TABLES LIKE '$table'");
                                    if ($result && $result->num_rows > 0) {
                                        $tables_found[] = $table;
                                    } else {
                                        $tables_missing[] = $table;
                                    }
                                }
                                
                                if (empty($tables_missing)) {
                                    echo '<div class="alert alert-success">';
                                    echo '<span class="success">✅ All tables exist!</span><br>';
                                    echo 'Tables found: ' . implode(', ', $tables_found);
                                    echo '</div>';
                                } else {
                                    echo '<div class="alert alert-warning">';
                                    echo '<span class="info">⚠️ Some tables are missing!</span><br>';
                                    if (!empty($tables_found)) {
                                        echo 'Found: ' . implode(', ', $tables_found) . '<br>';
                                    }
                                    echo 'Missing: ' . implode(', ', $tables_missing) . '<br>';
                                    echo '<strong>Solution:</strong> Run the database.sql file in your database.';
                                    echo '</div>';
                                }
                                
                                $conn->close();
                            }
                        } else {
                            echo '<div class="alert alert-warning">';
                            echo 'Cannot test database connection - previous tests failed.';
                            echo '</div>';
                            $connection_ok = false;
                        }
                        ?>

                        <hr>

                        <h5>📋 Next Steps</h5>
                        <div class="alert alert-info">
                            <?php if ($server_ok && $db_exists && isset($connection_ok) && $connection_ok): ?>
                                <p><strong>✅ Everything looks good!</strong></p>
                                <ol>
                                    <li>Make sure the database name in <code>functions/connect.php</code> matches your database: <code><?= htmlspecialchars($db_name) ?></code></li>
                                    <li>If tables are missing, run the <code>database.sql</code> file in your database</li>
                                    <li>Try accessing the application: <a href="login.php" class="btn btn-primary btn-sm">Go to Login</a></li>
                                </ol>
                            <?php else: ?>
                                <p><strong>⚠️ Please fix the issues above first:</strong></p>
                                <ol>
                                    <?php if (!$server_ok): ?>
                                        <li>Start MySQL server in XAMPP Control Panel</li>
                                    <?php endif; ?>
                                    <?php if (!$db_exists): ?>
                                        <li>Create database named: <code><?= htmlspecialchars($db_name) ?></code>
                                            <ul>
                                                <li>Open phpMyAdmin: <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a></li>
                                                <li>Click "New" → Enter database name: <code><?= htmlspecialchars($db_name) ?></code></li>
                                                <li>Click "Create"</li>
                                            </ul>
                                        </li>
                                        <li>After creating database, run <code>database.sql</code> to create tables</li>
                                    <?php endif; ?>
                                    <li>Update database name in <code>functions/connect.php</code> if you used a different name</li>
                                </ol>
                            <?php endif; ?>
                        </div>

                        <div class="text-center mt-4">
                            <a href="test_connection.php" class="btn btn-primary">Refresh Test</a>
                            <a href="login.php" class="btn btn-success">Go to Login</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">📝 Configuration Instructions</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>To update database connection settings:</strong></p>
                        <ol>
                            <li>Open file: <code>functions/connect.php</code></li>
                            <li>Update these values to match your MySQL setup:
                                <pre class="bg-light p-3 mt-2"><code>define('DB_HOST', 'localhost');  // Usually 'localhost'
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password (empty if no password)
define('DB_NAME', 'ky_tuc_xa');   // Your database name</code></pre>
                            </li>
                            <li>Also update the database name in this test file (<code>test_connection.php</code>) if needed</li>
                            <li>Save and refresh this test page</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>



