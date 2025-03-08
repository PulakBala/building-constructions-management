<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $flatId = $_POST['f_flatId'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $marked = $_POST['marked'];

    $updateQuery = "UPDATE flat_bill SET marked = ? WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("iiss", $marked, $flatId, $month, $year);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
?>