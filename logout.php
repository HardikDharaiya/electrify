<?php
// logout.php - Destroys the user's session

// 1. Start the session to access it.
session_start();

// 2. Unset all of the session variables.
$_SESSION = array();

// 3. Destroy the session itself.
session_destroy();

// 4. Redirect the user to the home page.
header("Location: index.php");
exit();
?>