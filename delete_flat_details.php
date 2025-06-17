<?php
include('connection.php');

// Check if ID is set
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Optional: Get building_id to redirect back correctly (if needed)
    $get_building = mysqli_query($conn, "SELECT building_id FROM flat_details WHERE id = $id");
    $building = mysqli_fetch_assoc($get_building);
    $building_id = $building['building_id'];

    // Delete the record
    $sql = "DELETE FROM flat_details WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        // Redirect back to previous page with building ID
        header("Location: add_flat.php?id=$building_id&deleted=1");
        exit;
    } else {
        echo "<p style='color: red;'>Error deleting record: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color: red;'>No ID specified to delete.</p>";
}
?>
