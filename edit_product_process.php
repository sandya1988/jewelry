<?php
require_once 'connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']); 
    $name = htmlspecialchars(trim($_POST['name']));
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.00; // Ensure price is a valid float
    $description = isset($_POST['description']) ? htmlspecialchars(trim($_POST['description'])) : null;

    $image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $imageName = time() . '_' . basename($_FILES['image']['name']); // Create a unique name for the image
        $uploadFile = $uploadDir . $imageName;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $image = $imageName; // Save the image name for database update

            // get and delete the old image bcz we dont need it anymore
            $stmt_old_image = $conn->prepare("SELECT image FROM products WHERE id = ?");
            $stmt_old_image->bind_param("i", $id);
            $stmt_old_image->execute();
            $stmt_old_image->bind_result($oldImage);
            $stmt_old_image->fetch();
            $stmt_old_image->close();

            if (!empty($oldImage) && file_exists("uploads/" . $oldImage)) {
                unlink("uploads/" . $oldImage);
            }
        } else {
            echo "Error uploading the image.";
            exit;
        }
    }

    if ($image) {
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sdssi", $name, $price, $description, $image, $id);
    } else {
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, description = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $name, $price, $description, $id);
    }

    if ($stmt->execute()) {
        header("Location: /admin.php");
        exit;
    } else {
        echo "Error updating product: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: /admin.php");
    exit;
}
?>
