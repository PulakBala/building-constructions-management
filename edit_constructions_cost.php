<?php
include('connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = 'construction'; // Hardcoded for construction table

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current data for the given ID
    $sql = "SELECT * FROM construction_cost WHERE id = ?";
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
    $project_name = $_POST['project_name'];
    $construction_name = $_POST['construction_name'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $payment_type = $_POST['payment_type'];
    $note = $_POST['note'];

    $table = 'construction_cost';

    try {
        $sql = "UPDATE $table SET 
                project_name = ?, 
                construction_name = ?, 
                amount = ?, 
                date = ?, 
                payment_type = ?, 
                note = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssdsssi', 
            $project_name, 
            $construction_name, 
            $amount, 
            $date, 
            $payment_type, 
            $note, 
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

<form id="editForm" action="edit_constructions_cost.php" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id'] ?? ''); ?>">
    
    <div class="form-group mb-3">
        <label for="project_name">Project Name:</label>
        <input type="text" class="form-control" id="project_name" name="project_name" 
               value="<?php echo htmlspecialchars($record['project_name'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="construction_name">Construction Name:</label>
        <input type="text" class="form-control" id="construction_name" name="construction_name" 
               value="<?php echo htmlspecialchars($record['construction_name'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="amount">Amount:</label>
        <input type="number" class="form-control" id="amount" name="amount" 
               value="<?php echo htmlspecialchars($record['amount'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="date">Date:</label>
        <input type="date" class="form-control" id="date" name="date" 
               value="<?php echo htmlspecialchars($record['date'] ?? ''); ?>" required>
    </div>
    
    <div class="form-group mb-3">
        <label for="payment_type">Payment Type:</label>
        <select class="form-control" id="payment_type" name="payment_type" required>
            <option value="">Select Payment Type</option>
            <option value="Cash" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Cash') ? 'selected' : ''; ?>>Cash</option>
            <option value="Bkash" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Bkash') ? 'selected' : ''; ?>>Bkash</option>
            <option value="Nagad" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Nagad') ? 'selected' : ''; ?>>Nagad</option>
            <option value="Rocket" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Rocket') ? 'selected' : ''; ?>>Rocket</option>
            <option value="Bank Transfer" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
        </select>
    </div>
    
    <div class="form-group mb-3">
        <label for="note">Note:</label>
        <textarea class="form-control" id="note" name="note" rows="3"><?php echo htmlspecialchars($record['note'] ?? ''); ?></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">Update</button>
</form>