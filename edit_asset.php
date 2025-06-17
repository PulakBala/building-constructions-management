<?php
include('connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = 'main_assets'; // Hardcoded for construction table

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current data for the given ID
    $sql = "SELECT * FROM main_assets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();

    if (!$record) {
        echo "Record not found.";
        exit;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    $asset_name = $_POST['asset_name'];
    try {
        $sql = "UPDATE $table SET 
                asset_name = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', 
            $asset_name, 
            $id
        );

        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $executeResult = $stmt->execute();
        
        if ($executeResult) {
            echo "Record updated successfully.";
        } else {
            throw new Exception("Execute failed: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
?>

<form id="editForm" action="edit_asset.php" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id'] ?? ''); ?>">
    
    
    <div class="form-group mb-3">
        <label for="asset_name">Asset Name:</label>
        <input type="text" class="form-control" id="asset_name" name="asset_name" 
               value="<?php echo htmlspecialchars($record['asset_name'] ?? ''); ?>" required>
    </div>

   
   
    <button type="submit" class="btn btn-primary">Update</button>
</form>