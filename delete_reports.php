<?php
include('connection.php');

$f_id = $_GET['id'] ?? null; // Flat ID
$month = $_GET['month'] ?? null; // Month parameter
$year = $_GET['year'] ?? null; // Year parameter

if ($f_id && $month && $year) {
    // Transaction start kora
    mysqli_begin_transaction($conn);

    try {
        // Payments table theke delete kora
        $deletePaymentsQuery = "DELETE FROM payments WHERE f_flatId = '$f_id' AND f_month = '$month' AND f_year = '$year'";
        mysqli_query($conn, $deletePaymentsQuery);

        // Flat_bill table theke delete kora
        $deleteFlatBillQuery = "DELETE FROM flat_bill WHERE f_flatId = '$f_id' AND f_month = '$month' AND f_year = '$year'";
        mysqli_query($conn, $deleteFlatBillQuery);

        // Transaction commit kora
        mysqli_commit($conn);
        header("Location: monthly-coll.php?status=deleted");
        exit();
    } catch (Exception $e) {
        // Jodi kono error hoy, tahole transaction rollback kora
        mysqli_rollback($conn);
        echo "Error deleting record: " . mysqli_error($conn);
    }
}


