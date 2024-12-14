<?php
require_once 'connection.php';

session_start();

//check if admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 2) {
    header("Location: /register.php");
    exit;
}

$stmt_products = $conn->prepare("SELECT id, name, price, description, image FROM products");
$stmt_products->execute();
$products = $stmt_products->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_products->close();

// get all forms
$stmt_forms = $conn->prepare("SELECT id, Name, Email, Phone_Number, Message, created_at FROM forms ORDER BY created_at DESC");
$stmt_forms->execute();
$forms = $stmt_forms->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_forms->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css" media="screen">
</head>

<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            <a href="/logout.php" class="btn btn-danger">Logout</a>
        </div>
        
        <h2 class="mt-5">Products List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($product['price'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>
                            <?php if (!empty($product['image'])) : ?>
                                <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" style="width: 100px; height: auto;">
                            <?php else : ?>
                                No image
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="add_product.php" class="btn btn-success">Add Product</a>

        <h2 class="mt-5">Submitted Forms</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($forms as $form) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($form['id']); ?></td>
                        <td><?php echo htmlspecialchars($form['Name']); ?></td>
                        <td><?php echo htmlspecialchars($form['Email']); ?></td>
                        <td><?php echo htmlspecialchars($form['Phone_Number']); ?></td>
                        <td><?php echo htmlspecialchars($form['Message']); ?></td>
                        <td><?php echo htmlspecialchars($form['created_at']); ?></td>
                        <td>
                            <a href="delete_form.php?id=<?php echo $form['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>
