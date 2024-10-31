
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    echo "Username: " . $username . "<br>";
    echo "Email: " . $email . "<br>";
    echo "Password: " . $password . "<br>";

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "klinoffroad";

    check_validity($username, $email, $password, $servername, $db_username, $db_password, $db_name);

    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO users (username, Email, PasswordHash) VALUES ('$username', '$email', '$password')";
// test
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    
    // injection proof is true for this code
}

function check_validity($username, $email, $password, $servername, $db_username, $db_password, $db_name) {
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill in all fields";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit();
    }

    // regex no special characters
    if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
        echo "Invalid username format";
        exit();
    }

    if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
        echo "Invalid password format";
        exit();
    }

    // lengh of password 100 and username 50
    if (strlen($username) > 50 || strlen($password) > 100) {
        echo "Username or password too long";
        exit();
    }

    $conn = new mysqli($servername, $db_username, $db_password, $db_name);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username='$username' OR Email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Username or email already exists";
        exit();
    }
}

// we have injection proof code above and we have a check_validity function that checks for valid input if below code is not injection proof, contact me
// maybe klinoff will fix it, but if not, contact me
?>