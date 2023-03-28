<?php
// Start Redis session
require_once 'redis_session.php';
startRedisSession();

// Set database credentials
$host = 'localhost';
$dbname = 'guvi';
$username = 'root';
$password = '';

// Create a PDO instance for database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(array('error' => 'Connection failed: ' . $e->getMessage()));
    exit;
}

// Get the POST data
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Check if email and password are provided
if(empty($email) || empty($password)){
    echo json_encode(array('error' => 'Email and Password are required'));
    exit;
}

// Check if email exists in the database
$stmt = $pdo->prepare("SELECT * FROM register WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if(!$user){
    echo json_encode(array('error' => 'Email address not found'));
    exit;
}

// Verify the password
if(password_verify($password, $user['password'])){

    // Store user details in Redis
    $redis = new Redis();

    if(!$redis->connect('127.0.0.1', 6379)) {
        echo json_encode(array('error' => 'Redis connection failed'));
        exit;
    }

    $redis->set('user:'.$user['id'], json_encode($user));

    // Store user id in browser localstorage
    $response = ['response'=>true];
    echo json_encode($response);
    exit;
}
else{
    echo json_encode(array('error' => 'Incorrect password'));
    exit;
}
?>
