<?php
// Start Redis session
require_once 'redis_session.php';
startRedisSession();

// Destroy Redis session
session_destroy();

// Redirect to login page
header('Location: login.html');
exit;
?>
