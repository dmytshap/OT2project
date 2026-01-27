<?php
// --- Database Configuration ---
// This file will contain database connection details.
// Also database manipulation functions etc.

// in order to get PDO to work:
// copy php.ini-development file -> rename it into php.ini file
// uncomment extension=pdo_mysql and extension_dir = "ext"

// I had separate config.php file, where servername, username, password 
// and dbname were stored
// Store them in .env file?
require 'config.php';

try {
  $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "Connected successfully.";
} catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>

