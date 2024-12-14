<?php
require_once 'connection.php'; 

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT id, name, price, description, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Product not found.";
        exit;
    }

    $product = $result->fetch_assoc();

    $stmt->close();
} else {
    echo "Product ID not provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Product</h1>
        <form action="edit_product_process.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo isset($product['id']) ? $product['id'] : ''; ?>">
            
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($product['name']) ? htmlspecialchars($product['name']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo isset($product['price']) ? htmlspecialchars($product['price']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo isset($product['description']) ? htmlspecialchars($product['description']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="image">Product Image:</label>
                <input type="file" class="form-control-file" id="image" name="image">
                <?php if (!empty($product['image'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" class="img-thumbnail mt-2" style="width: 150px;">
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary mb-5">Update Product</button>
        </form>
    </div>

</body>
</html>
