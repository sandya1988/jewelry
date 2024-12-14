<?php 
include 'components/header.php';

// get products
$stmt = $conn->prepare("SELECT id, name, price, description, image FROM products");
$stmt->execute();
$products = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="our_room">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="titlepage">
                    <h2>Our Products</h2>
                    <p>Explore our latest collection and find your perfect match!</p>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="col-md-4 col-sm-6">
                    <div id="serv_hover" class="room">
                        <a href="product_detail.php?id=<?php echo htmlspecialchars($product['id']); ?>" style="text-decoration: none; color: inherit;">
                            <div class="bed_room text-center">
                                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="img-fluid mb-3" style="width: 100%; height: 250px; object-fit: cover;">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <span class="text-primary">$<?php echo number_format($product['price'], 2); ?></span>
                            </div>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>
