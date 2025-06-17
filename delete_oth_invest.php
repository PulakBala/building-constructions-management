<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM other_invest WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: oth_ex_invest.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}