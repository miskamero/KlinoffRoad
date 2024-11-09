<?php
include 'encryptklinoffname.php';

if (!isset($_COOKIE['KlinoffUsername'])) {
    header("Location: index.php");
}

$username = decryptString($_COOKIE['KlinoffUsername']) 
    ?: die("Username is missing.");

if ($username !== "admin") {
    die("You are not authorized to access this page.");
}

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "klinoffroad";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle product addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    $sql = "INSERT INTO products (ProductName, Price, Stock, Description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdis", $productName, $price, $stock, $description);
    $stmt->execute();
    $stmt->close();
}

// Handle product deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_product'])) {
    $productID = $_POST['product_id'];

    $sql = "DELETE FROM products WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modify_product'])) {
    $productID = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $sql = "UPDATE products SET ProductName = ?, Price = ?, Stock = ?, Description = ? WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdisi", $productName, $price, $stock, $description, $productID);
    $stmt->execute();
    $stmt->close();
}

include 'encryptklinoffname.php';

// Handle user modification
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modify_user'])) {
    $userID = $_POST['user_id'];
    $username = $_POST['username'];
    $password = encryptString($_POST['password']);

    $sql = "UPDATE users SET Username = ?, PasswordHash = ? WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $username, $password, $userID);
    $stmt->execute();
    $stmt->close();
}

// Fetch all products
$products_sql = "SELECT * FROM products";
$products_result = $conn->query($products_sql);

// Fetch all users
$users_sql = "SELECT * FROM users";
$users_result = $conn->query($users_sql);

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="admincss.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
</head>
<body>
    <div class="header">
        <h1>KlinoffRoad Admin Panel</h1>
    </div>

    <div class="productsContainer">
        <h2>Products</h2>
        <div class="product-headers">
            <div class="product-header">Product ID</div>
            <div class="product-header">Product Name</div>
            <div class="product-header">Price</div>
            <div class="product-header">Stock</div>
            <div class="product-header">Description</div>
            <div class="product-header">Action</div>
        </div>
        <div class="products-search">
            <div id="search-filter-by">
                <span class="material-symbols-outlined">chevron_right</span>
                <p>Filter by:</p>
            </div>
            <input type="text" id="search" placeholder="Search products...">
            <span id="search-icon" class="material-symbols-outlined">search</span>
        </div>
        <div class="product-grid">
            <?php while ($row = $products_result->fetch_assoc()): ?>
            <form method="post" class="product-row">
                <div class="productid"><?php echo htmlspecialchars($row['ProductID']); ?></div>
                <div><input type="text" name="product_name" value="<?php echo htmlspecialchars($row['ProductName']); ?>"></div>
                <div><input type="number" name="price" value="<?php echo htmlspecialchars($row['Price']); ?>" step="0.01"></div>
                <div><input type="number" name="stock" value="<?php echo htmlspecialchars($row['Stock']); ?>" step="1"></div>
                <div><input type="text" name="description" value="<?php echo htmlspecialchars($row['Description']); ?>"></div>
                <div>
                    <input type="hidden" name="product_id" value="<?php echo $row['ProductID']; ?>">
                    <input type="submit" name="modify_product" value="Save">
                    <input type="submit" name="delete_product" value="Delete">
                </div>
            </form>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="add-product-container">
        <h2>Add a Product</h2>
        <div class="add-product-labels">
            <label for="product_name"> Name</label>
            <label for="price">Price</label>
            <label for="stock">Stock</label>
        </div>
        <form method="post">
            <div class="add-product-inputs">
                <input type="text" id="product_name" name="product_name" placeholder="Name of product">
                <input type="number" step="0.01" id="price" name="price" placeholder="Price of product">
                <input type="number" step="1" id="stock" name="stock" placeholder="Amount of stock">
            </div>
            <div class="add-product-description">
                <label for="description">Description:</label><br>
                <textarea id="description" name="description"></textarea><br>
            </div>
            <input type="submit" name="add_product" value="Add Product">
        </form>
    </div>

    <div class="users-container">
        <h2>Users</h2>
        <div class="users-headers">
            <div class="users-header">User ID</div>
            <div class="users-header">Username</div>
            <div class="users-header">Password</div>
            <div class="users-header">Save</div>
        </div>
        <div class="users-grid">
            <?php while ($row = $users_result->fetch_assoc()): ?>
            <form method="post" class="user-row">
                <div><?php echo htmlspecialchars($row['UserID']); ?></div>
                <div><input type="text" name="username" value="<?php echo htmlspecialchars($row['Username']); ?>"></div>
                <div><input type="password" name="password"></div>
                <div>
                    <input type="hidden" name="user_id" value="<?php echo $row['UserID']; ?>">
                    <input type="submit" name="modify_user" value="Save">
                </div>
            </form>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="footer">
        <button onclick="window.location.href = 'logout.php';">Logout</button>
        <!-- back to sop.php -->
        <button onclick="window.location.href = 'sop.php';">Back to Shop</button>
    </div>

    <script>
        function searchProducts() {
            const searchInput = document.getElementById('search').value;
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `klinoffsearch.php?query=${encodeURIComponent(searchInput)}`, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const productsContainer = document.querySelector('.product-grid');
                    productsContainer.innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        document.getElementById('search').addEventListener('input', searchProducts);
    </script>
</body>
</html>