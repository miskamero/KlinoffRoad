<?php
include 'encryptklinoffname.php';

// Check if productid and username are provided
if (!isset($_GET['productid'])) {
    die("Product ID is missing.");
}

if (!isset($_COOKIE['KlinoffUsername'])) {
    header("Location: index.php");
}

$productid = $_GET['productid'];
$username = decryptString($_COOKIE['KlinoffUsername']) 
    ?: die("Username is missing.");
    

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

// Check if the user exists in the database
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

// Check if there's an existing cart for this user
$cart_sql = "SELECT CartID, Items FROM carts WHERE UserID = ?";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("i", $userid);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

if ($cart_result->num_rows > 0) {
    // Cart exists, so update the existing cart
    $cart = $cart_result->fetch_assoc();
    $cartID = $cart['CartID'];
    $items = json_decode($cart['Items'], true) ?: [];

    // Update quantity or remove the product
    if (isset($items[$productid])) {
        if ($items[$productid] > 1) {
            $items[$productid] -= 1; // Decrement quantity by 1
        } else {
            unset($items[$productid]); // Remove product if quantity is 1
        }

        // add one stock 
        $product_sql = "SELECT Stock FROM products WHERE ProductID = ?";
        $product_stmt = $conn->prepare($product_sql);
        $product_stmt->bind_param("i", $productid);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        $product = $product_result->fetch_assoc();
        $product = $product['Stock'];
        
        $product += 1;

        $update_stock_sql = "UPDATE products SET Stock = ? WHERE ProductID = ?";
        $update_stock_stmt = $conn->prepare($update_stock_sql);
        $update_stock_stmt->bind_param("ii", $product, $productid);
        $update_stock_stmt->execute();

        // Update the cart with new items and timestamp
        $items_json = json_encode($items);
        $update_cart_sql = "UPDATE carts SET Items = ?, LastUpdated = NOW() WHERE CartID = ?";
        $update_cart_stmt = $conn->prepare($update_cart_sql);
        $update_cart_stmt->bind_param("si", $items_json, $cartID);
        $update_cart_stmt->execute();
    } else {
        echo "Product not found in cart.";
    }
} else {
    echo "No cart found for user.";
}

$conn->close();

// Redirect back to shop or show a success message
header("Location: snakecasecart.php?username=$username");
exit();
?>