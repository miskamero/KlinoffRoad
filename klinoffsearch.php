<?php
include 'encryptklinoffname.php';

$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "klinoffroad";
$conn = new mysqli($servername, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $_GET['query'];
$sql = "SELECT * FROM products WHERE ProductName LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = '%' . $query . '%';
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<form method='post' class='product-row'>";
    echo "<div class='productid'>" . htmlspecialchars($row['ProductID']) . "</div>";
    echo "<div><input type='text' name='product_name' value='" . htmlspecialchars($row['ProductName']) . "'></div>";
    echo "<div><input type='number' name='price' value='" . htmlspecialchars($row['Price']) . "' step='0.01'></div>";
    echo "<div><input type='number' name='stock' value='" . htmlspecialchars($row['Stock']) . "' step='1'></div>";
    echo "<div><input type='text' name='description' value='" . htmlspecialchars($row['Description']) . "'></div>";
    echo "<div>";
    echo "<input type='hidden' name='product_id' value='" . $row['ProductID'] . "'>";
    echo "<input type='submit' name='delete_product' value='Delete'>";
    echo "<input type='submit' name='modify_product' value='Modify'>";
    echo "</div>";
    echo "</form>";
}

$stmt->close();
$conn->close();
?>