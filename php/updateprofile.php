<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION["email"])) {
  header("Location: login.php");
  exit();
}

// Get user's email from session
$email = $_SESSION["email"];

// Retrieve user info from Redis
$redis = new Redis();
$redis->connect("localhost");
$name = $redis->get("user:" . $email . ":name");
$email = $redis->get("user:" . $email . ":email");

// Handle profile update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $name = $_POST["name"];
  $age = $_POST["age"];
  $dob = $_POST["dob"];
  $contact = $_POST["contact"];

  // Update user info in MongoDB
  $mongo = new MongoDB\Client("mongodb://localhost:27017");
  $db = $mongo->selectDatabase("mydb");
  $users = $db->selectCollection("users");
  $result = $users->updateOne(
    array("email" => $email),
    array('$set' => array("name" => $name, "age" => $age, "dob" => $dob, "contact" => $contact))
  );

  // Send JSON response
  if ($result->getModifiedCount() == 1) {
    $response = array("status" => "success");
    echo json_encode($response);
  } else {
    $response = array("status" => "error");
    echo json_encode($response);
  }
}
?>
