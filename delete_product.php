<?php
require_once 'connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($currentImage);
    $stmt->fetch();
    $stmt->close();

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        if (!empty($currentImage) && file_exists("uploads/" . $currentImage)) {
            unlink("uploads/" . $currentImage);
        }
        header("Location: /admin.php");
        exit;
    } else {
        echo "Error deleting product: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: /admin.php");
    exit;
}
?>
