<?php
include('connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = 'assets'; // Hardcoded for construction table

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current data for the given ID
    $sql = "SELECT * FROM assets WHERE id = ?";
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
    $asset_value = $_POST['asset_value'];
    $contact = $_POST['contact'];
    $registration_cost = $_POST['registration_cost'];
    $other_expenses = $_POST['other_expenses'];
    $date = $_POST['date'];


    try {
        $sql = "UPDATE $table SET 
                asset_name = ?, 
                asset_value = ?, 
                contact = ?, 
                registration_cost = ?, 
                other_expenses = ?, 
                date = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdsssi', 
            $asset_name, 
            $asset_value, 
            $contact, 
            $registration_cost, 
            $other_expenses, 
            $date, 
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

<form id="editForm" action="edit_new_assets.php" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id'] ?? ''); ?>">
    
    
    <div class="form-group mb-3">
        <label for="asset_name">Asset Name:</label>
        <input type="text" class="form-control" id="asset_name" name="asset_name" 
               value="<?php echo htmlspecialchars($record['asset_name'] ?? ''); ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="asset_value">Asset Value:</label>
        <input type="number" class="form-control" id="asset_value" name="asset_value" 
               value="<?php echo htmlspecialchars($record['asset_value'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="contact">Contact:</label>
        <input type="text" class="form-control" id="contact" name="contact" 
               value="<?php echo htmlspecialchars($record['contact'] ?? ''); ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="registration_cost">Registration Cost:</label>
        <input type="number" class="form-control" id="registration_cost" name="registration_cost" 
               value="<?php echo htmlspecialchars($record['registration_cost'] ?? ''); ?>" required>
    </div>

    <div class="form-group mb-3">
        <label for="other_expenses">Other Expenses:</label>
        <input type="number" class="form-control" id="other_expenses" name="other_expenses" 
               value="<?php echo htmlspecialchars($record['other_expenses'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="date">Date:</label>
        <input type="date" class="form-control" id="date" name="date" 
                  value="<?php echo htmlspecialchars($record['date'] ?? ''); ?>" required>
    </div>
   
    <button type="submit" class="btn btn-primary">Update</button>
</form>