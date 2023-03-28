<?php
// Start Redis session
require_once '/php/redis_session.php';
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

// Get user details from Redis
$redis = new Redis();

if(!$redis->connect('127.0.0.1', 6379)) {
    echo json_encode(array('error' => 'Redis connection failed'));
    exit;
}

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if(empty($email)){
    echo json_encode(array('error' => 'User not found'));
    exit;
}

$userData = json_decode($redis->get('user:'.$email), true);

// Get user details from MongoDB
require_once 'mongo_connection.php';

$collection = $client->guvi->users;

$user = $collection->findOne(['email' => $email]);

if(!$user){
    echo json_encode(array('error' => 'User not found in MongoDB'));
    exit;
}

// Merge Redis and MongoDB user details
$userData = array_merge($userData, $user);

// Return user details as JSON
echo json_encode($userData);

// Update user details in MongoDB
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $age = isset($_POST['age']) ? $_POST['age'] : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $contact = isset($_POST['contact']) ? $_POST['contact'] : '';

    // Validate input
    if(empty($age) || empty($dob) || empty($contact)){
        echo 'error';
        exit;
    }

    // Update user details
    $result = $collection->updateOne(
        ['email' => $email],
        ['$set' => ['age' => $age, 'dob' => $dob, 'contact' => $contact]]
    );

    if($result->getModifiedCount() === 1){
        echo 'success';
    }
    else{
        echo 'error';
    }
}
?>
