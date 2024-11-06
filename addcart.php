<?php
// Check if productid and username are provided
if (!isset($_GET['productid']) || !isset($_GET['username'])) {
    die("Product ID or username is missing.");
}

$productid = $_GET['productid'];
$username = $_GET['username'];

echo "<script>
    if (localStorage.getItem('username') != '$username') {
        window.location.href = 'index.php';
    }
</script>";

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

    // Update quantity or add the new product
    if (isset($items[$productid])) {
        $items[$productid] += 1; // Increment quantity by 1
    } else {
        $items[$productid] = 1; // Add new product with quantity 1
    }

    // remove one stock
    $stock_sql = "SELECT Stock FROM products WHERE ProductID = ?";
    $stock_stmt = $conn->prepare($stock_sql);
    $stock_stmt->bind_param("i", $productid);
    $stock_stmt->execute();
    $stock_result = $stock_stmt->get_result();
    $stock = $stock_result->fetch_assoc();
    $stock = $stock['Stock'];

    if ($stock > 0) {
        $stock = $stock - 1;
    } else {
        die("Out of stock.");
    }

    $update_stock_sql = "UPDATE products SET Stock = ? WHERE ProductID = ?";
    $update_stock_stmt = $conn->prepare($update_stock_sql);
    $update_stock_stmt->bind_param("ii", $stock, $productid);
    $update_stock_stmt->execute();

    // Update the cart with new items and timestamp
    $items_json = json_encode($items);
    $update_cart_sql = "UPDATE carts SET Items = ?, LastUpdated = NOW() WHERE CartID = ?";
    $update_cart_stmt = $conn->prepare($update_cart_sql);
    $update_cart_stmt->bind_param("si", $items_json, $cartID);
    $update_cart_stmt->execute();
} else {
    // No cart exists, so create a new one
    $items = [$productid => 1];
    $items_json = json_encode($items);

    $create_cart_sql = "INSERT INTO carts (UserID, Items, LastUpdated) VALUES (?, ?, NOW())";
    $create_cart_stmt = $conn->prepare($create_cart_sql);
    $create_cart_stmt->bind_param("is", $userid, $items_json);
    $create_cart_stmt->execute();
}

$conn->close();

// Redirect back to shop or show a success message
header("Location: sop.php");
exit();
?>
