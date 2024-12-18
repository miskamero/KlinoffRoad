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
    
    // decrypt the password
    include 'encryptklinoffname.php';

    // $originalString = "Hello, World!";
    // $encryptedString = encryptString($originalString);
    // $decryptedString = decryptString($encryptedString);

    // echo "Original: $originalString\n";
    // echo "Encrypted: $encryptedString\n";
    // echo "Decrypted: $decryptedString\n";

    // echo $password;
    // $password = decryptString($password);
    // if ($password === false) {
    //     echo "Password decryption failed.<br>";
    // } else {
    //     echo $password;
    // }
    // die();
    
    // echo $klinoff;
    // die();
    // if conatins text
    if (empty($klinoff)) {
        $conn = new mysqli($servername, $db_username, $db_password, $db_name);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $sql = "SELECT * FROM users WHERE Username='$username'";

        // echo $sql;
        // die();
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            // decrypt the password
            $row = $result->fetch_assoc();
            $decrypted = decryptString($row['PasswordHash']);
            if ($decrypted === false) {
                echo "Password decryption failed.<br>";
            } else {
                // echo $decrypted;
            }

            if ($password != $decrypted) {
                $error = "Password is incorrect";
                header("Location: login.php?error=$error");
                exit();
            }


            if (!isset($_COOKIE["KlinoffUsername"])) {
                setcookie("KlinoffUsername", encryptString($username), time() + 1800, "/"); // set username as a cookie for KlinoffU0 minutes. The "/" means that the cookie is available in the entire website.
            } else {
                $error = "Session already exists";
                header("Location: login.php?error=$error");
                exit();
            }
            // echo "<script>
            //     localStorage.setItem('username', '" . $username . "');
            //     localStorage.setItem('password', '" . $password . "');
            //     window.location.href = 'sop.php';
            // </script>";
            // turn to php

            header("Location: sop.php");
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
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="logincss.css">
</head>
<body>
    <h1>Login</h1>

    <?php
    echo 
        "<script>
        document.addEventListener('DOMContentLoaded', function() {
                const feedbackText = document.getElementById('feedbackText');
                if (!window.location.href.endsWith('.php')) {
                    setTimeout(() => {
                        feedbackText.style.opacity = '0';
                    }, 3000);
                }
            });
        </script>";
    ?>
    <?php if (isset($_GET['error'])): ?>
        <p id='feedbackText' style="color: red;">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </p>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <p id='feedbackText' style="color: green;">
            <?php echo htmlspecialchars($_GET['success']); ?>
        </p>
    <?php endif; ?>

    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password">
        <input type="submit" value="Continue the journey of Klinoff">
    </form>
    <button onclick="window.location.href = 'new_user.php';">Still haven't started the Klinoff journey?</button>
</body>