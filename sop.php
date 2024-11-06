<?php
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "klinoffroad";

// Create connection
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - KlinoffRoad</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
</head>
<body>
    <h1>Shop</h1>
    <div id="logoutButton">
        <button onclick="window.location.href = 'logout.php';">Logout</button>
    </div>
    <?php
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2>" . htmlspecialchars($row["ProductName"]) . "</h2>";
            echo "<p>Price: $" . htmlspecialchars($row["Price"]) . "</p>";
            echo "<p>Stock: " . htmlspecialchars($row["Stock"]) . "</p>";
            echo "<p>Description: " . htmlspecialchars($row["Description"]) . "</p>";
            echo "</div>";
        }
    } else {
        echo "No products available.";
    }
    $conn->close();
    ?>
</body>
</html>