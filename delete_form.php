<?php
require_once 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $form_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM forms WHERE id = ?");
    $stmt->bind_param("i", $form_id);

    if ($stmt->execute()) {
        header("Location: /admin.php");
        exit;
    } else {
        echo "Error deleting form entry.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: /admin.php");
    exit;
}
?>
