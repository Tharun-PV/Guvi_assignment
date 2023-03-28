<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
session_start();
// connect to MySQL database
$link = mysqli_connect("localhost", "root", "", "guvi");
// Check connection
if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}
// Get data from POST request
$name = $_POST["name"];
$email = $_POST["email"];
$password = $_POST["password"];
$age = $_POST["age"];
$dob = $_POST["dob"];
$contact = $_POST["contact"];
// Validate data (such as checking if the email is already registered)
$stmt = $link->prepare("SELECT email FROM register WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    // Email already registered
    echo "Email already registered";
} else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data into MySQL database using prepared statement
    $stmt = $link->prepare("INSERT INTO register (name, email, password, age, dob, contact) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssssss", $name, $email, $hashed_password, $age, $dob, $contact);
    if (!$stmt->execute()) {
        // Insert failed
        echo "Error inserting data: " . $stmt->error;
    } else {
        // Insert successful
        echo "success";
    }
}
// Close the statement and database connection
$stmt->close();
$link->close();
?>
