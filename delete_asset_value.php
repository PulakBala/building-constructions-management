<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $sql = "DELETE FROM assets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    
    if ($stmt->execute()) {
        echo "Value deleted successfully.";
    } else {
        echo "Error deleting value.";
    }
    
    $stmt->close();
}
?>