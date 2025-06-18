<?php
include_once('connection.php');
header('Content-Type: application/json');

// Input validation
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$paid_amount = isset($_POST['paid_amount']) ? floatval($_POST['paid_amount']) : 0;
$due_note = isset($_POST['due_note']) ? trim($_POST['due_note']) : '';

$month = date('F', strtotime('first day of -1 month'));
$year = date('Y', strtotime('first day of -1 month'));

if ($id <= 0 || $paid_amount <= 0 || $due_note == '') {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
    exit;
}

// Check if due note exists and get due amount
$checkDueAmountQuery = "SELECT f_due_flat FROM flat_bill WHERE f_flatId = ? AND f_month = ? AND f_year = ? AND f_due_note = ?";
$stmtCheck = $conn->prepare($checkDueAmountQuery);
$stmtCheck->bind_param("isss", $id, $month, $year, $due_note);
$stmtCheck->execute();
$stmtCheck->bind_result($due_flat_amount);
$stmtCheck->fetch();
$stmtCheck->close();

if ($due_flat_amount === null) {
    echo json_encode(['success' => false, 'message' => 'The specified Flat Due Note does not exist.']);
    exit;
} elseif ($paid_amount > $due_flat_amount) {
    echo json_encode(['success' => false, 'message' => 'The payment amount exceeds the due amount for this note.']);
    exit;
}

// Update payments table
$updatePaymentQuery = "UPDATE payments 
                       SET f_paid_amount = f_paid_amount + ?,
                           f_due = f_due - ?
                       WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
$stmt = $conn->prepare($updatePaymentQuery);
$stmt->bind_param("ddiss", $paid_amount, $paid_amount, $id, $month, $year);
$stmt->execute();
$stmt->close();

// Update flat_bill table
$removeDueFlatQuery = "UPDATE flat_bill
                       SET f_due_flat = f_due_flat - ?
                       WHERE f_flatId = ? AND f_month = ? AND f_year = ? AND f_due_note = ?";
$stmtRemove = $conn->prepare($removeDueFlatQuery);
$stmtRemove->bind_param("disss", $paid_amount, $id, $month, $year, $due_note);
$stmtRemove->execute();
$stmtRemove->close();

echo json_encode(['success' => true, 'message' => 'Due amount updated successfully.']);
exit;
?>