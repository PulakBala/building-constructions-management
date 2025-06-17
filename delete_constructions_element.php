<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM constructions_element WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: add_constructions_element.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}