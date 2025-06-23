<?php

// Database Configuration
define('DB_HOST', 'localhost');         // Replace with your database host
define('DB_USERNAME', 'db_user');       // Replace with your database username
define('DB_PASSWORD', 'db_password');   // Replace with your database password
define('DB_NAME', 'cms_db');            // Replace with your database name

// Error reporting - Recommended for development, adjust for production
error_reporting(E_ALL);
ini_set('display_errors', 1); // Set to 0 in production

// Default Timezone (optional, but good practice)
// date_default_timezone_set('America/New_York'); // Example: New York

?>
