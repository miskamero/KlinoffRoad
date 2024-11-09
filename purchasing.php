<?php
include 'encryptklinoffname.php';
if (!isset($_COOKIE['KlinoffUsername'])) {
    header("Location: index.php");
    exit();
}
$username = decryptString($_COOKIE['KlinoffUsername']);
if ($username === false) {
    header("Location: index.php");
    exit();
}

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "klinoffroad";
$conn = new mysqli($servername, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user ID
$user_sql = "SELECT UserID FROM users WHERE Username = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $username);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
if ($user_result->num_rows == 0) {
    die("User not found.");
}
$user = $user_result->fetch_assoc();
$userid = $user['UserID'];

// Fetch cart items
$cart_sql = "SELECT CartID, Items FROM carts WHERE UserID = ?";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("i", $userid);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();
if ($cart_result->num_rows == 0) {
    die("Cart is empty.");
}
$cart = $cart_result->fetch_assoc();
$cartID = $cart['CartID'];
$items = json_decode($cart['Items'], true);

// Calculate total price
$total = 0;
foreach ($items as $productID => $quantity) {
    $product_sql = "SELECT Price FROM products WHERE ProductID = ?";
    $product_stmt = $conn->prepare($product_sql);
    $product_stmt->bind_param("i", $productID);
    $product_stmt->execute();
    $product_result = $product_stmt->get_result();
    $product = $product_result->fetch_assoc();
    $total += $product['Price'] * $quantity;
}

// CREATE TABLE orders (
//     OrderID INT AUTO_INCREMENT PRIMARY KEY,
//     UserID INT NOT NULL,
//     CartID INT NOT NULL,
//     Items JSON,
//     Total DECIMAL(10, 2) NOT NULL,
//     OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
//     FOREIGN KEY (UserID) REFERENCES users(UserID) ON DELETE CASCADE,
//     FOREIGN KEY (CartID) REFERENCES carts(CartID) ON DELETE CASCADE
// );
// Insert order into orders table
$order_sql = "INSERT INTO orders (UserID, CartID, Items, Total) VALUES (?, ?, ?, ?)";
$order_stmt = $conn->prepare($order_sql);
$items_json = json_encode($items);
$order_stmt->bind_param("iisd", $userid, $cartID, $items_json, $total);
$order_stmt->execute();

// Clear cart items
$clear_sql = "UPDATE carts SET Items = '{}' WHERE CartID = ?";
$clear_stmt = $conn->prepare($clear_sql);
$clear_stmt->bind_param("i", $cartID);
$clear_stmt->execute();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You for Purchasing from KlinoffRoad</title>
    <style>
        body {
            display: grid;
            place-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        #cont {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .loader {
            width: 100px;
            height: 100px;
            background-image: url('assets/favicon.png');
            background-size: cover;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div id="cont">
        <div class="loader"></div>
        <h1>Thank you for purchasing from KlinoffRoad!</h1>
        <p>Redirecting you back to the homepage...</p>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = 'sop.php';
        }, 3000);
    </script>
</body>
</html>