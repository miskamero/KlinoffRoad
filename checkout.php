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
$cart_sql = "SELECT Items FROM carts WHERE UserID = ?";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("i", $userid);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();
$items = [];
if ($cart_result->num_rows > 0) {
    $cart = $cart_result->fetch_assoc();
    $items = json_decode($cart['Items'], true);
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - KlinoffRoad</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="checkoutcss.css">
</head>
<body>
    <h1>Checkout  - For the 
            <?php
                $adjectives = ['adventurous', 'bold', 'brave', 'courageous', 'daring', 'fearless', 'heroic', 'intrepid', 'valiant', 'valorous'];
                $adjective = $adjectives[array_rand($adjectives)];
                echo $adjective;

                // username
                include 'encryptklinoffname.php';
                $username = decryptString($_COOKIE['KlinoffUsername']);
                echo " $username";

            ?> </h1>
    
    <img src="assets/favicon.png" alt="KlinoffRoad Logo">
    <?php if (empty($items)): ?>
        <p>Your cart is empty. <a href="sop.php">Go back to shop</a></p>
    <?php else: ?>
        <h2>Your Cart</h2>
        <ul>
            <?php
            $total = 0;
            foreach ($items as $productID => $quantity):
                $conn = new mysqli($servername, $db_username, $db_password, $db_name);
                $product_sql = "SELECT ProductName, Price FROM products WHERE ProductID = ?";
                $product_stmt = $conn->prepare($product_sql);
                $product_stmt->bind_param("i", $productID);
                $product_stmt->execute();
                $product_result = $product_stmt->get_result();
                $product = $product_result->fetch_assoc();
                $conn->close();
                $total += $product['Price'] * $quantity;
            ?>
                <li>
                    <?php echo htmlspecialchars($product['ProductName']); ?> - Quantity: <?php echo $quantity; ?> - Price: <?php echo htmlspecialchars($product['Price'] * $quantity); ?> €
                </li>
            <?php endforeach; ?>
        </ul>
        <h3>Total: <?php echo $total; ?> €</h3>
        <form action="purchasing.php" method="post">
            <input type="hidden" name="userid" value="<?php echo $userid; ?>">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <label for="credit_card">Credit Card Number:</label>
            <input type="text" id="credit_card" name="credit_card" placeholder="1234-5678-9012-3456">
            <input type="text" id="expiration_date" name="expiration_date" placeholder="MM/YY">
            <input type="number" id="cvv" name="cvv" placeholder="CVV">
            <br>
            <input type="submit" value="Complete Checkout">
        </form>
    <?php endif; ?>
</body>
</html>