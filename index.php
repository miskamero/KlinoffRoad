<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlinoffRoad</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h1 id="welcome">KlinoffRoad</h1>
    <?php
        session_start();
        echo 
        "<script>
        //     // get for local storage the username if it exists
        //     if (localStorage.getItem('username') === 'admin') {
        //         window.location.href = 'admin.php';
        //     } else if (localStorage.getItem('username')) {
        //         window.location.href = 'sop.php';
        //     } else {
                document.getElementById('welcome').innerHTML = 'KlinoffRoad';
                if (!window.location.href.endsWith('.php')) {
                    setTimeout(() => {
                        feedbackText.style.opacity = '0';
                    }, 3000);
                }
        //     }
        </script>";
        // turn to cookies
        if (isset($_COOKIE['KlinoffUsername'])) {
            if ($_COOKIE['KlinoffUsername'] === 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: sop.php');
            }
        }


        // check for the "error" in url, and get the data from it and display it
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            echo "<p id='feedbackText' style='color: red;'>$error</p>";
        }
        if (isset($_GET['success'])) {
            $error = $_GET['success'];
            echo "<p id='feedbackText' style='color: green;'>$error</p>";
        }
        
    ?>

    <button onclick="window.location.href = 'new_user.php';">New klinoff? Join Here!</button>
    <button class="login" onclick="window.location.href = 'login.php';">Klinogin</button>

    <?php
        include 'encryptklinoffname.php';

        // Example plaintext
        $plaintext = "<script>prompt('KlinoffRoad');</script>";

        // Verify the key
        if ($key === false) {
            echo "Key not found.<br>";
        } else {
            echo "Key: " . $key . "<br>";

            // Encrypt the plaintext
            $encrypted = encryptString($plaintext, $key);
            if ($encrypted === false) {
                echo "Encryption failed.<br>";
            } else {
                echo "Encrypted: " . $encrypted . "<br>";

                // Decrypt the ciphertext
                $decrypted = decryptString($encrypted, $key);
                if ($decrypted === false) {
                    echo "Decryption failed.<br>";
                } else {
                    echo "Decrypted: " . $decrypted . "<br>";
                }
            }
        }
    ?>
</body>

</html>