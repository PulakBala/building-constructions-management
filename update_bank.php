<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $bank_name = $_POST['bank_name'];
    $account_number = $_POST['account_number'];
    $branch = $_POST['branch'];
    $account_name = $_POST['account_name'];
    $contact = $_POST['contact'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];

    // Signature image upload if new
    $signature_path = '';
    if (isset($_FILES['signature']) && $_FILES['signature']['error'] == 0) {
        $uploadDir = 'uploads/';
        $filename = time() . '_' . basename($_FILES['signature']['name']);
        $targetPath = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['signature']['tmp_name'], $targetPath)) {
            $signature_path = $targetPath;
        }
    }

    // If new signature uploaded, update it
    if (!empty($signature_path)) {
        $query = "UPDATE bank_accounts SET 
            bank_name='$bank_name', account_number='$account_number',
            branch='$branch', account_name='$account_name',
            contact='$contact', signature_path='$signature_path',
            amount='$amount', date='$date' WHERE id=$id";
    } else {
        $query = "UPDATE bank_accounts SET 
            bank_name='$bank_name', account_number='$account_number',
            branch='$branch', account_name='$account_name',
            contact='$contact',
            amount='$amount', date='$date' WHERE id=$id";
    }

    if (mysqli_query($conn, $query)) {
        header("Location: bank_list.php?updated=1");
        exit;
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>
