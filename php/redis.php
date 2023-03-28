<?php
// Start Redis session
require_once 'redis_session.php';
startRedisSession();

// Connect to Redis
$redis = new Redis();
if (!$redis->connect('127.0.0.1', 6379)) {
    echo json_encode(array('error' => 'Redis connection failed'));
    exit;
}

// Get email from Redis
$user_id = $_SESSION['user_id'];
$user_data = $redis->get('user:' . $user_id);
$user = json_decode($user_data, true);
$email = $user['email'];

// Send email to the PHP script
$data = array('email' => $email);
$options = array(
    'http' => array(
        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);
$context = stream_context_create($options);
$result = file_get_contents('process.php', false, $context);

echo $result;
?>
