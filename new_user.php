<?php
include "encryptklinoffname.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];


        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $db_name = "klinoffroad";

        // klinoff is the error message for validation
        // it is named "klinoff" because it is the error message for the validation and klinoff is mostly used in the code because "Big Klinoff" is watching. :O
        $klinoff = check_validity($username, $password, $servername, $db_username, $db_password, $db_name);
        
        // encrypt the password
        $password = encryptString($password);

        // if conatins text
        if (empty($klinoff)) {
            $conn = new mysqli($servername, $db_username, $db_password, $db_name);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "INSERT INTO users (username, PasswordHash) VALUES ('$username', '$password')";
            $result = $conn->query($sql);
            if ($result === TRUE) {
                // Redirect to login page
                header("Location: index.php?success=Account created successfully, please login");
                exit();
            } else {
                // Pass back the entered data
                $error = "Error: " . $sql . "<br>" . $conn->error;
                header("Location: new_user.php?error=$klinoff");
                exit();
            }
        }
        else {
            // goto new_user.php with error message
            header("Location: new_user.php?error=$klinoff");
        }
        $conn->close();
    }

    function check_validity($username, $password, $servername, $db_username, $db_password, $db_name) {
        if (empty($username) || empty($password)) {
            return  "Please fill in all fields";
        }

        // regex no special characters
        if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) {
            return  "Invalid username format";
        }

        if (!preg_match("/^[a-zA-Z0-9]*$/", $password)) {
            return  "Invalid password format";
        }

        // lengh of password 100 and username 50
        if (strlen($username) > 50 || strlen($password) > 100) {
            return  "Username or password too long";
        }

        $conn = new mysqli($servername, $db_username, $db_password, $db_name);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM users WHERE username='$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            return  "Username already exists";
        }

        return "";
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlinoffRoad</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="logincss.css">
</head>
<body>
    <h1>Register</h1>

        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </p>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <p style="color: green;">
                <?php echo htmlspecialchars($_GET['success']); ?>
            </p>
        <?php endif; ?>

        <form action="new_user.php" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password">
            <input type="submit" value="Start the journey of Klinoff">
        </form>
    <button onclick="window.location.href = 'login.php'">Already started the Klinoff journey?</button>
</body>