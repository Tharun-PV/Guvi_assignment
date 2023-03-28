<?php
// Start session
session_start();

// Retrieve user info from Redis
$redis = new Redis();
$redis->connect("localhost");
$email = $_SESSION["email"];
$name = $redis->get("user:" . $email . ":name");

// Check if user is logged in
if (!isset($_SESSION["email"])) {
  header("Location: login.php");
  exit();
}

// Handle profile update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve form data
  $age = $_POST["age"];
  $dob = $_POST["dob"];
  $contact = $_POST["contact"];

  // Update user info in Redis
  $redis = new Redis();
  $redis->connect("localhost");
  $redis->set("user:" . $email . ":age", $age);
  $redis->set("user:" . $email . ":dob", $dob);
  $redis->set("user:" . $email . ":contact", $contact);

  // Send JSON response
  $response = array("status" => "success");
  echo json_encode($response);
}
?>