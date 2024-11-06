<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KlinoffRoad</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
</head>

<body>
    <h1>KlinoffRoad</h1>

    <?php
        // check for the "error" in url, and get the data from it and display it
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            echo "<p style='color: red;'>$error</p>";
        }
        if (isset($_GET['success'])) {
            $error = $_GET['success'];
            echo "<p style='color: green;'>$error</p>";
        }
    ?>

    <button onclick="window.location.href = 'new_user.php';">New klinoff? Join Here!</button>
    <button class="login" onclick="window.location.href = 'login.php';">Klinogin</button>
</body>

</html>