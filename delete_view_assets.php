<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM assets WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: display_main_assets.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}