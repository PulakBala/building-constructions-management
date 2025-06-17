<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM construction_cost WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: add_construction.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}