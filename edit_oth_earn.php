<?php
include('connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = 'oth_earn'; // Hardcoded for construction table

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current data for the given ID
    $sql = "SELECT * FROM oth_earn WHERE id = ?";
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
    
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $note = $_POST['note'];

    $table = 'oth_earn';

    try {
        $sql = "UPDATE $table SET 
                amount = ?, 
                date = ?, 
                note = ? 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssi', 
            $amount, 
            $date, 
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

<form id="editForm" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id'] ?? ''); ?>">
    
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
        <label for="note">Note:</label>
        <input type="text" class="form-control" id="note" name="note" 
               value="<?php echo htmlspecialchars($record['note'] ?? ''); ?>" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Update</button>
</form>