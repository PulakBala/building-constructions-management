<?php
include('connection.php');
// File upload folder
$uploadDir = "uploads/signatures/";

// Check folder exists or create it
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Collect form data
$bank_name = $_POST['bank_name'];
$account_number = isset($_POST['account_number']) ? $_POST['account_number'] : null;
$branch = isset($_POST['branch']) ? $_POST['branch'] : null;
$account_name = isset($_POST['account_name']) ? $_POST['account_name'] : null;
$contact = isset($_POST['contact']) ? $_POST['contact'] : null;
$amount = isset($_POST['amount']) && $_POST['amount'] !== '' ? $_POST['amount'] : 0; // Set default to 0 if empty
$date = isset($_POST['date']) && $_POST['date'] !== '' ? $_POST['date'] : date('Y-m-d'); // Set current date if empty

// Handle signature upload if provided
$signature_path = null;
if (isset($_FILES['signature']) && $_FILES['signature']['error'] === UPLOAD_ERR_OK) {
    $signature_file = $_FILES['signature']['name'];
    $temp_path = $_FILES['signature']['tmp_name'];
    $ext = pathinfo($signature_file, PATHINFO_EXTENSION);
    $new_name = uniqid('sig_', true) . '.' . $ext;
    $destination = $uploadDir . $new_name;
    
    if (move_uploaded_file($temp_path, $destination)) {
        $signature_path = $destination;
    }
}

// Insert into database
$query = "INSERT INTO bank_accounts 
    (bank_name, account_number, branch, account_name, contact, signature_path, amount, date)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ssssssds", 
    $bank_name, 
    $account_number, 
    $branch, 
    $account_name, 
    $contact, 
    $signature_path, 
    $amount, 
    $date
);

if (mysqli_stmt_execute($stmt)) {
    echo "<script>
        alert('Data inserted successfully!');
        window.location.href = 'bank_form.php';
    </script>";
} else {
    echo "<script>
        alert('Error inserting data: " . mysqli_error($conn) . "');
        window.location.href = 'bank_form.php';
    </script>";
}
?>