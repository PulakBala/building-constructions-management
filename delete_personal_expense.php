<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    $deleteQuery = "DELETE FROM expense WHERE id = '$id'";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: personal_expense.php?status=deleted");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}