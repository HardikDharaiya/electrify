<?php
// config/db_connect.php

// -- DATABASE CONFIGURATION --
// We define constants here to hold our database credentials.
// Using constants is a good practice for values that will not change.

define('DB_HOST', 'localhost'); // The server where the database resides. 'localhost' is standard for Laragon.
define('DB_USER', 'root');      // The database username. 'root' is the default for Laragon.
define('DB_PASS', '');          // The database password. By default, Laragon's root user has no password.
define('DB_NAME', 'electrify_db'); // The name of the database we created.


// -- ESTABLISH DATABASE CONNECTION --
// We will now attempt to connect to the database using the credentials defined above.
// We use the MySQLi extension, which is a modern and secure way to interact with MySQL/MariaDB databases in PHP.

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);


// -- CHECK FOR CONNECTION ERRORS --
// It's crucial to check if the connection was successful.
// If the connection fails, the script should stop immediately and show an error message.
// This prevents further issues and potential security vulnerabilities on the site.

if ($conn->connect_error) {
    // The die() function terminates the script's execution.
    die("Connection failed: " . $conn->connect_error);
}

// Optional: Set the character set to utf8mb4 to ensure proper handling of all characters.
$conn->set_charset("utf8mb4");

// If the script reaches this point, the connection was successful.
// The '$conn' variable can now be used in other PHP files to perform database queries.
?>