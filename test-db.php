<?php
// Database Test File
echo "<h2>Database Connection Test</h2>";

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pisal_ecommerce'); // Updated to match your database name

try {
    // Create database connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    echo "<p>Connected to database: " . DB_NAME . "</p>";
    
    // Test if products table exists
    $result = $conn->query("SHOW TABLES LIKE 'products'");
    if ($result->num_rows > 0) {
        echo "<p style='color: green;'>✅ Products table found!</p>";
        
        // Get table structure
        $structure = $conn->query("DESCRIBE products");
        echo "<h3>Products Table Structure:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        while ($row = $structure->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Count products
        $count = $conn->query("SELECT COUNT(*) as total FROM products");
        $total = $count->fetch_assoc()['total'];
        echo "<p>Total products in database: <strong>" . $total . "</strong></p>";
        
        // Show first 5 products
        if ($total > 0) {
            echo "<h3>First 5 Products:</h3>";
            $products = $conn->query("SELECT * FROM products LIMIT 5");
            echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
            
            // Get column names
            $columns = [];
            while ($field = $products->fetch_field()) {
                $columns[] = $field->name;
                echo "<th>" . $field->name . "</th>";
            }
            echo "</tr>";
            
            // Reset result pointer and show data
            $products->data_seek(0);
            while ($row = $products->fetch_assoc()) {
                echo "<tr>";
                foreach ($columns as $col) {
                    echo "<td>" . htmlspecialchars($row[$col]) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Products table not found!</p>";
        echo "<p>Available tables:</p>";
        $tables = $conn->query("SHOW TABLES");
        echo "<ul>";
        while ($table = $tables->fetch_row()) {
            echo "<li>" . $table[0] . "</li>";
        }
        echo "</ul>";
    }
    
    $conn->close();
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    
    // Try to connect without specifying database to see available databases
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        echo "<h3>Available Databases:</h3>";
        $databases = $conn->query("SHOW DATABASES");
        echo "<ul>";
        while ($db = $databases->fetch_row()) {
            echo "<li>" . $db[0] . "</li>";
        }
        echo "</ul>";
        $conn->close();
    } catch (Exception $e2) {
        echo "<p style='color: red;'>Cannot connect to MySQL server: " . $e2->getMessage() . "</p>";
    }
}
?>
