<?php
include('connection.php');

// Get ID
$id = $_GET['id'];

// Delete query
$sql = "DELETE FROM people_info WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    // Optional: redirect back after deletion
    header("Location: contact_info.php");
    exit();
} else {
    echo "❌ Error deleting record: " . $conn->error;
}

$conn->close();
?>
