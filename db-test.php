<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Test - Pisal Masala</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; }
        .error { color: red; }
        .info { color: blue; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .code { background: #f4f4f4; padding: 10px; margin: 10px 0; }
    </style>
</head>
<body>
    <h1>Database Connection Test - Pisal Masala E-commerce</h1>
    
    <?php
    echo "<h2>Step 1: Testing Database Connection</h2>";
    
    // Database configuration
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'pisal_ecommerce');
    
    echo "<div class='info'>Attempting to connect to: " . DB_HOST . " / " . DB_NAME . "</div>";
    
    try {
        // Create database connection
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        echo "<div class='success'>✅ Database connection successful!</div>";
        echo "<div class='info'>Connected to database: " . DB_NAME . "</div>";
        echo "<div class='info'>MySQL Server version: " . $conn->server_info . "</div>";
        
        // Test if products table exists
        echo "<h2>Step 2: Checking Products Table</h2>";
        $result = $conn->query("SHOW TABLES LIKE 'products'");
        if ($result->num_rows > 0) {
            echo "<div class='success'>✅ Products table found!</div>";
            
            // Get table structure
            $structure = $conn->query("DESCRIBE products");
            echo "<h3>Products Table Structure:</h3>";
            echo "<table>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
            while ($row = $structure->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Default']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Count products
            $count = $conn->query("SELECT COUNT(*) as total FROM products");
            $total = $count->fetch_assoc()['total'];
            echo "<h3>Product Count: " . $total . "</h3>";
            
            // Show products
            if ($total > 0) {
                echo "<h3>Products in Database:</h3>";
                $products = $conn->query("SELECT * FROM products ORDER BY id ASC LIMIT 10");
                echo "<table>";
                echo "<tr><th>ID</th><th>Name</th><th>SKU</th><th>Price</th><th>Stock</th><th>Featured</th><th>Status</th></tr>";
                while ($row = $products->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['sku']) . "</td>";
                    echo "<td>₹" . number_format($row['price'], 2) . "</td>";
                    echo "<td>" . htmlspecialchars($row['stock_quantity']) . "</td>";
                    echo "<td>" . ($row['featured'] ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
                
                // Test the exact query used in index.php
                echo "<h2>Step 3: Testing Index.php Query</h2>";
                echo "<div class='code'>Query: SELECT * FROM products WHERE featured = 1 OR status = 'active' ORDER BY created_at DESC LIMIT 4</div>";
                
                $sql = "SELECT * FROM products WHERE featured = 1 OR status = 'active' ORDER BY created_at DESC LIMIT 4";
                $result = $conn->query($sql);
                
                if ($result && $result->num_rows > 0) {
                    echo "<div class='success'>✅ Query successful! Found " . $result->num_rows . " products</div>";
                    $featured_products = $result->fetch_all(MYSQLI_ASSOC);
                    
                    echo "<h4>Products that would appear on homepage:</h4>";
                    foreach ($featured_products as $product) {
                        echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
                        echo "<strong>" . htmlspecialchars($product['name']) . "</strong><br>";
                        echo "Price: ₹" . number_format($product['price'], 2) . "<br>";
                        echo "Description: " . htmlspecialchars($product['short_description'] ?? 'No description') . "<br>";
                        echo "Rating: " . htmlspecialchars($product['rating'] ?? 'N/A') . "<br>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='error'>❌ Query failed or returned no results</div>";
                    echo "<div class='info'>Trying fallback query: SELECT * FROM products LIMIT 4</div>";
                    
                    $fallback = $conn->query("SELECT * FROM products LIMIT 4");
                    if ($fallback && $fallback->num_rows > 0) {
                        echo "<div class='success'>✅ Fallback query successful! Found " . $fallback->num_rows . " products</div>";
                    } else {
                        echo "<div class='error'>❌ Even fallback query failed</div>";
                    }
                }
            } else {
                echo "<div class='info'>No products found in database. You need to insert some sample data.</div>";
                echo "<div class='code'>Use the mysql-database-setup.sql file to create sample products</div>";
            }
            
        } else {
            echo "<div class='error'>❌ Products table not found!</div>";
            echo "<h3>Available Tables:</h3>";
            $tables = $conn->query("SHOW TABLES");
            echo "<ul>";
            while ($table = $tables->fetch_row()) {
                echo "<li>" . htmlspecialchars($table[0]) . "</li>";
            }
            echo "</ul>";
            echo "<div class='info'>Please run the mysql-database-setup.sql file to create the required tables.</div>";
        }
        
        $conn->close();
        
    } catch (Exception $e) {
        echo "<div class='error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</div>";
        
        // Try to connect without specifying database to see available databases
        echo "<h2>Step 4: Checking Available Databases</h2>";
        try {
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
            echo "<h3>Available Databases:</h3>";
            $databases = $conn->query("SHOW DATABASES");
            echo "<ul>";
            while ($db = $databases->fetch_row()) {
                echo "<li>" . htmlspecialchars($db[0]) . "</li>";
            }
            echo "</ul>";
            $conn->close();
        } catch (Exception $e2) {
            echo "<div class='error'>❌ Cannot connect to MySQL server: " . htmlspecialchars($e2->getMessage()) . "</div>";
            echo "<div class='info'>Please check:</div>";
            echo "<ul>";
            echo "<li>MySQL server is running</li>";
            echo "<li>Username and password are correct</li>";
            echo "<li>Host is correct (usually 'localhost')</li>";
            echo "</ul>";
        }
    }
    ?>
    
    <h2>Next Steps:</h2>
    <ol>
        <li>If database connection failed, check your MySQL server and credentials</li>
        <li>If products table doesn't exist, import the mysql-database-setup.sql file</li>
        <li>If no products found, add some sample products to test</li>
        <li>Once everything is working, your index.php should display real products</li>
    </ol>
    
    <div class="code">
        <strong>Quick Setup Commands:</strong><br>
        1. mysql -u root -p pisal_ecommerce < mysql-database-setup.sql<br>
        2. Check this test page again<br>
        3. Visit your index.php page
    </div>
</body>
</html>
