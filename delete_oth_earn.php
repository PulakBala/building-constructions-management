<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM oth_earn WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: oth_earn.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}