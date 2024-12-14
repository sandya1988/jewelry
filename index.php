<?php include 'components/header.php';

include 'gold_price_api.php';
// Get latest 3 products
$stmt_latest = $conn->prepare("SELECT id, name, price, description, image FROM products ORDER BY created_at DESC LIMIT 3");
$stmt_latest->execute();
$latest_products = $stmt_latest->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_latest->close();

try {
   $goldPrices = getGoldPriceFromGoldAPI('USD'); // Fetch gold price in USD
} catch (Exception $e) {
   echo "Error: " . $e->getMessage();
}
?>

<section class="banner_main">
   <img src="images/banner.webp" alt="Banner">
   <div class="booking_ocline">
      <div class="container">
         <div class="row">
            <div class="col-md-5">
               <div class="book_room">
                  <h1>Sandy Jewelry</h1>
                  <p>Timeless Elegance, Crafted for You</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<div class="container my-5">
   <div class="text-center mb-5">
      <h1 class="display-4" style="font-weight: bold; color: #FFD700;">Gold Price Dashboard</h1>
      <p style="font-size: 1.2rem; color: #6c757d;">Stay updated with the latest gold prices at a glance</p>
   </div>

   <!-- Prices Per Ounce -->
   <div class="card mb-4 shadow-lg border-0">
      <div class="card-header text-white" style="background: linear-gradient(45deg, #FFD700, #FFA500);">
         <h2 class="text-center">Prices Per Ounce</h2>
      </div>
      <div class="card-body">
         <div class="row text-center">
            <div class="col-md-4">
               <p><strong>Previous Close:</strong></p>
               <h3 class="text-warning">$<?php echo number_format($goldPrices['prev_close_price'], 2); ?></h3>
            </div>
            <div class="col-md-4">
               <p><strong>Open Price:</strong></p>
               <h3 class="text-warning">$<?php echo number_format($goldPrices['open_price'], 2); ?></h3>
            </div>
            <div class="col-md-4">
               <p><strong>Current Price:</strong></p>
               <h3 class="text-warning">$<?php echo number_format($goldPrices['price'], 2); ?></h3>
            </div>
         </div>
         <hr>
         <div class="row text-center">
            <div class="col-md-4">
               <p><strong>Low Price:</strong></p>
               <h3 class="text-danger">$<?php echo number_format($goldPrices['low_price'], 2); ?></h3>
            </div>
            <div class="col-md-4">
               <p><strong>High Price:</strong></p>
               <h3 class="text-success">$<?php echo number_format($goldPrices['high_price'], 2); ?></h3>
            </div>
            <div class="col-md-4">
               <p><strong>Change:</strong></p>
               <h3 class="text-<?php echo $goldPrices['ch'] >= 0 ? 'success' : 'danger'; ?>">
                  $<?php echo number_format($goldPrices['ch'], 2); ?>
               </h3>
            </div>
         </div>
      </div>
   </div>

   <!-- Bid/Ask Prices -->
   <div class="card mb-4 shadow-lg border-0">
      <div class="card-header bg-info text-white text-center">
         <h2>Bid/Ask Prices</h2>
      </div>
      <div class="card-body text-center">
         <div class="row">
            <div class="col-md-6">
               <p><strong>Bid Price:</strong></p>
               <h3 class="text-primary">$<?php echo number_format($goldPrices['bid'], 2); ?></h3>
            </div>
            <div class="col-md-6">
               <p><strong>Ask Price:</strong></p>
               <h3 class="text-primary">$<?php echo number_format($goldPrices['ask'], 2); ?></h3>
            </div>
         </div>
      </div>
   </div>

   <!-- Prices Per Gram -->
   <div class="card mb-4 shadow-lg border-0">
      <div class="card-header text-white text-center" style="background: linear-gradient(45deg, #FFAA00, #FF8000);">
         <h2>Gold Price Per Gram</h2>
      </div>
      <div class="card-body">
         <div class="row text-center">
            <?php
            $goldGrades = [
               "24K" => $goldPrices['price_gram_24k'],
               "22K" => $goldPrices['price_gram_22k'],
               "21K" => $goldPrices['price_gram_21k'],
               "20K" => $goldPrices['price_gram_20k'],
               "18K" => $goldPrices['price_gram_18k'],
               "16K" => $goldPrices['price_gram_16k'],
               "14K" => $goldPrices['price_gram_14k'],
               "10K" => $goldPrices['price_gram_10k']
            ];
            foreach ($goldGrades as $grade => $price) : ?>
               <div class="col-md-3 mb-3">
                  <div class="p-3 border shadow-sm rounded" style="background-color: #FFF5E6;">
                     <h5><strong><?php echo $grade; ?></strong></h5>
                     <p class="mb-0 text-warning" style="font-size: 1.3rem;">$<?php echo number_format($price, 2); ?></p>
                  </div>
               </div>
            <?php endforeach; ?>
         </div>
      </div>
   </div>
</div>


<div class="about">
   <div class="container-fluid">
      <div class="row">
         <div class="col-md-5">
            <div class="titlepage">
               <h2>About Us</h2>
               <p>At Sandy Jewelry, we combine timeless elegance and modern craftsmanship to create stunning pieces of jewelry that speak to the heart. Our collection is thoughtfully curated to meet the diverse tastes of our customers, ensuring a perfect match for every occasion. Discover our commitment to quality and style, and let us help you make your moments unforgettable.</p>
               <a class="read_more" href="about.php"> Read More</a>
            </div>
         </div>
         <div class="col-md-7">
            <div class="about_img">
               <figure><img src="images/banner2.webp" alt="About Us" /></figure>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="our_room">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="titlepage">
               <h2>Our Products</h2>
               <p>Explore our latest collection of stunning jewelry pieces.</p>
            </div>
         </div>
      </div>
      <div class="row">
         <?php foreach ($latest_products as $product) : ?>
            <a href="/product_detail.php?id=<?php echo htmlspecialchars($product['id']); ?>" class="col-md-4 col-sm-6">
               <div id="serv_hover" class="room">
                  <div class="bed_room">
                     <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                     <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                     <span>Price: $<?php echo number_format($product['price'], 2); ?></span>
                  </div>
               </div>
            </a>
         <?php endforeach; ?>
      </div>
   </div>
</div>

<div class="contact">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="titlepage">
               <h2>Contact Us</h2>
            </div>
         </div>
      </div>
      <div class="row justify-content-center">
         <div class="col-md-6 text-center">
            <div class="contact-button-container">
               <h3>Wanna Contact Us?</h3>
               <p>We're here to help and answer any questions you might have. We look forward to hearing from you!</p>
               <a href="contact.php" class="btn btn-primary btn-lg contact-button mt-3">Get in Touch</a>
            </div>
         </div>
      </div>
   </div>
</div>

<?php include 'components/footer.php'; ?>