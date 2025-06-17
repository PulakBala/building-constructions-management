<?php
include('connection.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Optional: Delete the image from the server
    $getImage = mysqli_query($conn, "SELECT signature_path FROM bank_accounts WHERE id=$id");
    $imgData = mysqli_fetch_assoc($getImage);
    if (!empty($imgData['signature_path']) && file_exists($imgData['signature_path'])) {
        unlink($imgData['signature_path']);
    }

    // Delete the record
    $query = "DELETE FROM bank_accounts WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        header("Location: bank_list.php?deleted=1");
        exit;
    } else {
        echo "Failed to delete: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request.";
}
?>
