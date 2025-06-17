<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM projects WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: add_payment_con.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}