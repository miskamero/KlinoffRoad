<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to create a new user
function createUser($conn, $username, $email, $password) {
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, password_hash($password, PASSWORD_BCRYPT));
    
    if ($stmt->execute()) {
        echo "New user created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

// Example usage
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    createUser($conn, $username, $email, $password);
}

$conn->close();
?>