<?php

if (!isset($_GET['username'])) {
    header("Location: index.php");
}

$username = $_GET['username'];

// echo $username;
// die();

echo "<script>
// console.log(localStorage.getItem('username') != '$username');
    if (localStorage.getItem('username') != '$username') {
        window.location.href = 'index.php';
    }
</script>";

session_start();
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

// Get the username from local storage
echo "<script>
    const username = localStorage.getItem('username');
    if (username === null) {
        window.location.href = 'index.php';
    }
</script>";

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
    <title>Shopping Cart - KlinoffRoad</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
</head>
<body>
    <h1>Shopping Cart</h1>
    <?php if (empty($items)): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($items as $productID => $quantity): ?>
                <?php
                // Fetch product details
                $conn = new mysqli($servername, $db_username, $db_password, $db_name);
                $product_sql = "SELECT ProductName, Price FROM products WHERE ProductID = ?";
                $product_stmt = $conn->prepare($product_sql);
                $product_stmt->bind_param("i", $productID);
                $product_stmt->execute();
                $product_result = $product_stmt->get_result();
                $product = $product_result->fetch_assoc();
                $conn->close();
                ?>
                
                <li>
                    <?php echo htmlspecialchars($product['ProductName']); ?> - Quantity: <?php echo $quantity; ?> - Price: $<?php echo htmlspecialchars($product['Price'] * $quantity); ?>
                    <button class="remove-from-cart" data-productid="<?php echo $productID; ?>">Remove</button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <button onclick="window.location.href = 'sop.php';">Continue Shopping</button>
    <?php if (!empty($items)): ?>
        <button onclick="window.location.href = 'checkout.php';">Checkout</button>
    <?php endif; ?>
    <script>
        const removeFromCartButtons = document.querySelectorAll('.remove-from-cart');
        removeFromCartButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productID = button.getAttribute('data-productid');
                const username = localStorage.getItem('username');
                window.location.href = `klinoffdeleteoperation.php?username=${username}&productid=${productID}`;
            });
        });
    </script>
</body>
</html>