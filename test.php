<?php
echo "Pisal Masala Project is working!";
echo "<br>";
echo "Current directory: " . __DIR__;
echo "<br>";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] ?? 'Not set';
echo "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] ?? 'Not set';
echo "<br>";
echo "PHP is working correctly!";
?>
