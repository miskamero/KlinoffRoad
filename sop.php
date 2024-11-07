<?php
// echo "<script>
// const username = localStorage.getItem('username');
// if (username === null) {
//     window.location.href = 'index.php';
// }
// </script>";

if (!isset($_COOKIE['KlinoffUsername'])) {
    header('Location: index.php');
    exit;
}

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
    
    <?php
        include 'encryptklinoffname.php';

        // Get plaintext from cookie named "KlinoffUsername"
        $plaintext = $_COOKIE['KlinoffUsername'];

        // Encrypt the plaintext
        $encrypted = encryptString($plaintext);
        if ($encrypted === false) {
            echo "Encryption failed.<br>";
        } else {
            echo "Encrypted: " . $encrypted . "<br>";

            // Decrypt the ciphertext
            $decrypted = decryptString($encrypted);
            if ($decrypted === false) {
                echo "Decryption failed.<br>";
            } else {
                echo "Decrypted: " . htmlentities($decrypted, ENT_QUOTES | ENT_HTML5, 'UTF-8') . "<br>";
            }
        }
    ?>
    <h1>Shop</h1>
    <div id="logoutButton">
        <button onclick="window.location.href = 'logout.php';">Logout</button>
    </div>
    <div id="shoppingCart">
        <button onclick="EpicCar()">Shopping Cart</button>
    </div>

    <div id="products">
        <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    $productID = htmlspecialchars($row["ProductID"]);
                    $productName = htmlspecialchars($row["ProductName"]);
                    $price = htmlspecialchars($row["Price"]);
                    $stock = htmlspecialchars($row["Stock"]);
                    $description = htmlspecialchars($row["Description"]);
                    
                    echo "<div>";
                    echo "<h2>$productName</h2>";
                    echo "<p>Price: $$price</p>";
                    echo "<p>Stock: $stock</p>";
                    echo "<p>Description: $description</p>";
                    
                    // Generate Add to Cart button with a link containing the product ID
                    echo "<button class='add-to-cart' data-productid='$productID'>Add to cart</button>";
                    echo "</div>";
                    echo "<hr>";
                }
            } else {
                echo "No products available.";
            }
            $conn->close();
        ?>
    </div>

    <script>
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', () => {
                const productId = button.getAttribute('data-productid');
                // const username = localStorage.getItem('username');
                if (<?php echo isset($_COOKIE['KlinoffUsername']); ?>) {
                    const url = `addcart.php?productid=${productId}&username=<?php echo ($_COOKIE['KlinoffUsername']); ?>`;
                    window.location.href = url;
                } else {
                    window.location.href = 'index.php';
                }
            });
        });

        const EpicCar = () => {
            if (<?php echo isset($_COOKIE['KlinoffUsername']); ?>) {
                window.location.href = 'snakecasecart.php?username=<?php echo ($_COOKIE['KlinoffUsername']); ?>';
            } else {
                window.location.href = 'index.php';
            }
        }
        
    </script>
</body>
</html>
