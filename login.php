<?php

// login page for the user to login to the website and access the content of the website named "KlinoffRoad" that is a website for off-road enthusiasts.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $db_name = "klinoffroad";

    // klinoff is the error message for validation
    $klinoff = check_user($username, $password, $servername, $db_username, $db_password, $db_name);
    
    // echo $klinoff;
    // die();
    // if conatins text
    if (empty($klinoff)) {
        $conn = new mysqli($servername, $db_username, $db_password, $db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM users WHERE Username='$username' AND PasswordHash='$password'";

        // echo $sql;
        // die();
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            if (!isset($_COOKIE['KlinoffUsername'])) {
                setcookie("KlinoffUsername", $username, time() + 1800, "/"); // set username as a cookie for KlinoffU0 minutes. The "/" means that the cookie is available in the entire website.
            } else {
                $error = "Session already exists";
                header("Location: login.php?error=$error");
                exit();
            }
            echo "<script>
                localStorage.setItem('username', '" . $username . "');
                localStorage.setItem('password', '" . $password . "');
                window.location.href = 'sop.php';
            </script>";
            exit();
        } else {
            // Pass back the entered data
            $error = "Error: " . $sql . "<br>" . $conn->error;
            header("Location: login.php?error=password is incorrect");
            exit();
        }
    }
    else {
        // goto new_user.php with error message
        header("Location: login.php?error=$klinoff");
    }
    $conn->close();
}

function check_user($username, $password, $servername, $db_username, $db_password, $db_name) {
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
    if ($result->num_rows == 1) {
        return "";
    }
    else if ($result->num_rows == 0) {
        return "Username does not exist";
    }
    else {
        return "Fatal error contact KlinoffAdmin";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlinoffRoad</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
</head>
<body>
    <h1>Login</h1>

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

    <form action="login.php" method="post">
        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username"><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Continue the journey of Klinoff">
    </form>
    <button onclick="window.location.href = 'new_user.php';">Still haven't started the Klinoff journey?</button>
</body>