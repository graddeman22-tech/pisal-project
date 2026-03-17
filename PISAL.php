
<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "pisal_ecommerce"; 

// Connection create karna
$conn = new mysqli($servername, $username, $password, $dbname);

// Check karna
if ($conn->connect_error) {
    die("Connection fail ho gaya: " . $conn->connect_error);
}
echo "Badhiya! Database connect ho gaya hai.";
?>