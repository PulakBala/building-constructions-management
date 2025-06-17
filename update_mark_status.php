<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $flatId = $_POST['f_flatId'];
    $month = $_POST['month'];
    $year = $_POST['year'];
    $marked = $_POST['marked'];
    
    // Determine status based on marked value
    $status = ($marked == 1) ? 'Received' : 'Pending';
    
    // Update both marked status and f_status
    $query = "UPDATE flat_bill 
              SET marked = ?, 
                  f_status = ?
              WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isiss", $marked, $status, $flatId, $month, $year);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'status' => $status]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    
    $stmt->close();
    $conn->close();
}
?>