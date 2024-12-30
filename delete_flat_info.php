<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM flat_info WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: all_flat_information.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
