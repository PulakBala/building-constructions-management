<?php
include('connection.php');

$table = isset($_GET['table']) ? $_GET['table'] : 'revenue'; // Default to 'revenue'

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare the SQL statement to prevent SQL injection
    $sql = "DELETE FROM $table WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param('i', $id); // Bind the parameter as an integer

        // Execute the statement and check for success
        if ($stmt->execute()) {
            if ($table === 'revenue') {
                echo "<script>window.location.href='income.php';</script>";
                header('location:add_payment_con.php');
            } elseif ($table === 'expense') {
                echo "<script>window.location.href='personal_expense.php';</script>";
            }elseif ($table === 'construction_cost') {
                echo "<script>window.location.href='add_construction.php';</script>";
            }elseif ($table === 'workers_details') {
                echo "<script>window.location.href='add_worker_details.php';</script>";
            }elseif ($table === 'assets') {
                echo "<script>window.location.href='add_new_assets.php';</script>";
            }
        } else {
            echo "<script>alert('Error deleting record.');</script>";
        }

        $stmt->close(); // Close the statement
    } else {
        echo "<script>alert('Error preparing the statement.');</script>";
    }
} else {
    echo "Invalid request.";
}

$conn->close(); // Close the database connection
?>
