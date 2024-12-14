<?php
require_once 'connection.php';
include 'components/header.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']); // Sanitize input
    $stmt_product = $conn->prepare("SELECT id, name, description, price, image FROM products WHERE id = ?");
    $stmt_product->bind_param("i", $product_id);
    $stmt_product->execute();
    $product = $stmt_product->get_result()->fetch_assoc();
    $stmt_product->close();

    if (!$product) {
        header("Location: products.php");
        exit;
    }
} else {
    header("Location: products.php");
    exit;
}
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="row no-gutters">
                    <div class="col-md-6">
                        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="height: 100%;object-fit:cover;" class="img-fluid rounded-left">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <h2 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class="card-text text-muted mb-4"><?php echo htmlspecialchars($product['description']); ?></p>
                            <h3 class="text-primary mb-4">$<?php echo number_format($product['price'], 2); ?></h3>
                            <a 
                                href="https://wa.me/96170102006?text=<?php echo urlencode('Hello Sandy ! I am interested in buying this product: ' . $product['name']); ?>" 
                                target="_blank" 
                                class="btn btn-lg btn-success mb-3 w-100">
                                <i class="fa fa-whatsapp"></i> WhatsApp Us
                            </a>
                            <a href="contact.php" class="btn btn-lg btn-primary">Contact Us</a>
                            <a href="products.php" class="btn btn-lg btn-outline-secondary ml-3">Back to Products</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'components/footer.php'; ?>
