<?php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00; 
    $description = isset($_POST['description']) ? htmlspecialchars(trim($_POST['description'])) : null;

    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $imageName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $image = $imageName; // Store the image name in the database
        } else {
            echo "Error uploading the image.";
            exit;
        }
    }

    // Insert product into the database
    $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $description, $image);

    if ($stmt->execute()) {
        header("Location: /admin.php");
        exit;
    } else {
        echo "Error adding product: " . $stmt->error;
    }

    $stmt->close();
} else {
    header("Location: /admin.php");
    exit;
}

$conn->close();
?>
