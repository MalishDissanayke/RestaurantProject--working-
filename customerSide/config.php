
<?php
// Check if constants are already defined
if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', '');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'restaurantDB');
}

// Create Connection
$link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check Connection
if ($link->connect_error) {
    die('Connection Failed: ' . $link->connect_error);
}
?>
