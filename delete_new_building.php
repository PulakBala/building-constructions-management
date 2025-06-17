<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM building_info WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: display_new_building.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}
