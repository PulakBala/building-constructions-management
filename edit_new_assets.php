<?php
include('connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = 'assets'; // Hardcoded for construction table

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // First get the asset name for the selected ID
    $sql = "SELECT asset_name FROM assets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $asset = $result->fetch_assoc();
    $asset_name = $asset['asset_name'];

    // Then get all values for this asset name
    $sql = "SELECT * FROM assets WHERE asset_name = ? ORDER BY date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $asset_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $all_values = $result->fetch_all(MYSQLI_ASSOC);

    if (!$all_values) {
        echo "Record not found.";
        exit;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $asset_name = $_POST['asset_name'];
    $values = $_POST['values'];
    $dates = $_POST['dates'];
    $ids = $_POST['ids'];

    try {
        // Update each value
        for ($i = 0; $i < count($ids); $i++) {
            $sql = "UPDATE assets SET 
                    asset_name = ?, 
                    asset_value = ?, 
                    date = ? 
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssi', 
                $asset_name,
                $values[$i],
                $dates[$i],
                $ids[$i]
            );

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }
            $stmt->close();
        }
        
        echo "Records updated successfully.";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<form id="editForm" action="edit_new_assets.php" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
    
    <div class="form-group mb-3">
        <label for="asset_name">Asset Name:</label>
        <input type="text" class="form-control" id="asset_name" name="asset_name" 
               value="<?php echo htmlspecialchars($asset_name); ?>" required>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Value</th>
                    <th>Date</th>
                    <!-- <th>Action</th> -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all_values as $value): ?>
                <tr>
                    <td>
                        <input type="number" class="form-control" name="values[]" 
                               value="<?php echo htmlspecialchars($value['asset_value']); ?>">
                    </td>
                    <td>
                        <input type="date" class="form-control" name="dates[]" 
                               value="<?php echo htmlspecialchars($value['date']); ?>">
                    </td>
                    <td>
                        <input type="hidden" name="ids[]" value="<?php echo htmlspecialchars($value['id']); ?>">
                    
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
   
    <button type="submit" class="btn btn-primary">Update All</button>
</form>

<script>
function deleteValue(id) {
    if (confirm('Are you sure you want to delete this value?')) {
        $.ajax({
            url: 'delete_asset_value.php',
            type: 'POST',
            data: { id: id },
            success: function(response) {
                location.reload();
            },
            error: function() {
                alert('Failed to delete value.');
            }
        });
    }
}
</script>