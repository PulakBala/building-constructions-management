<?php
include('connection.php');

$id = $_GET['id'] ?? null;

if ($id) {
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // First, find all assets that have this ID as their parent_asset_id
        $findChildrenQuery = "SELECT id FROM assets WHERE parent_asset_id = '$id'";
        $childrenResult = mysqli_query($conn, $findChildrenQuery);
        
        // Delete all child assets first
        while ($child = mysqli_fetch_assoc($childrenResult)) {
            $deleteChildQuery = "DELETE FROM assets WHERE id = '{$child['id']}'";
            if (!mysqli_query($conn, $deleteChildQuery)) {
                throw new Exception("Error deleting child record: " . mysqli_error($conn));
            }
        }
        
        // Then delete the main asset
        $deleteQuery = "DELETE FROM assets WHERE id = '$id'";
        if (!mysqli_query($conn, $deleteQuery)) {
            throw new Exception("Error deleting main record: " . mysqli_error($conn));
        }
        
        // Commit the transaction
        mysqli_commit($conn);
        
        header("Location: display_main_assets.php?status=deleted");
        exit();
        
    } catch (Exception $e) {
        // Rollback the transaction if anything fails
        mysqli_rollback($conn);
        echo "Error: " . $e->getMessage();
    }
}