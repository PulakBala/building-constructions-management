<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM main_project WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: project_display.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}