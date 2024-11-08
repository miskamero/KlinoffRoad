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

// Handle book addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_book'])) {
    $bookName = $_POST['book_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];

    $sql = "INSERT INTO products (ProductName, Price, Stock, Description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdis", $bookName, $price, $stock, $description);
    $stmt->execute();
    $stmt->close();
}

// Handle book deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_book'])) {
    $bookID = $_POST['book_id'];

    $sql = "DELETE FROM products WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookID);
    $stmt->execute();
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['modify_book'])) {
    $bookID = $_POST['book_id'];
    $bookName = $_POST['book_name'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $sql = "UPDATE products SET ProductName = ?, Price = ?, Stock = ?, Description = ? WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdisi", $bookName, $price, $stock, $description, $bookID);
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

// Fetch all books
$books_sql = "SELECT * FROM products";
$books_result = $conn->query($books_sql);

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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Admin Page</h1>

    <h2>Books</h2>
    <table>
    <table>
        <tr>
            <th>Book ID</th>
            <th>Book Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $books_result->fetch_assoc()): ?>
        <tr>
            <form method="post">
                <td><?php echo htmlspecialchars($row['ProductID']); ?></td>
                <td><input type="text" name="book_name" value="<?php echo htmlspecialchars($row['ProductName']); ?>"></td>
                <td><input type="number" name="price" value="<?php echo htmlspecialchars($row['Price']); ?>" step="0.01"></td>
                <td><input type="number" name="stock" value="<?php echo htmlspecialchars($row['Stock']); ?>" step="1"></td>
                <td><input type="text" name="description" value="<?php echo htmlspecialchars($row['Description']); ?>"></td>
                <td>
                    <input type="hidden" name="book_id" value="<?php echo $row['ProductID']; ?>">
                    <input type="submit" name="delete_book" value="Delete">
                    <input type="submit" name="modify_book" value="Modify">
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Add Book</h2>
    <form method="post">
        <label for="book_name">Book Name:</label><br>
        <input type="text" id="book_name" name="book_name"><br>
        <label for="price">Price:</label><br>
        <input type="number" step="0.01" id="price" name="price"><br>
        <label for="stock">Stock:</label><br>
        <input type="number" step="1" id="stock" name="stock"><br>
        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br>
        <input type="submit" name="add_book" value="Add Book">
    </form>

    <h2>Users</h2>
    <table>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $users_result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['UserID']); ?></td>
            <td><?php echo htmlspecialchars($row['Username']); ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="user_id" value="<?php echo $row['UserID']; ?>">
                    <label for="username">Username:</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($row['Username']); ?>"><br>
                    <label for="password">Password:</label>
                    <input type="password" name="password"><br>
                    <input type="submit" name="modify_user" value="Modify">
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>