<?php
include('connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = isset($_GET['table']) ? $_GET['table'] : 'revenue'; // Default to 'revenue'


if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the current data for the given ID
    $sql = "SELECT * FROM $table WHERE id = ?";
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
    $descriptionOrNote = $_POST['descriptionOrNote'];

    // Debug: Print out all POST data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    try {
        if ($table === 'revenue') {
            $payment_type = $_POST['payment_type'];
            $sql = "UPDATE $table SET amount = ?, payment_type = ?, note = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('dssi', $amount, $payment_type, $descriptionOrNote, $id);
        } else {
            $sql = "UPDATE $table SET amount = ?, description = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('dsi', $amount, $descriptionOrNote, $id);
        }

        // Debug: Print out SQL query and any potential errors
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


<form id="editForm" action="edit_add_payment.php?table=<?php echo htmlspecialchars($table); ?>" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id'] ?? ''); ?>">
    <div class="form-group">
        <label for="amount">Amount:</label>
        <input type="number" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($record['amount'] ?? ''); ?>" required>
    </div>
    <?php if ($table === 'revenue'): ?>
    <div class="mb-3">
        <label class="form-label" style="font-size: 1rem; font-weight: bold;">Payment Type</label>
        <select class="form-control form-control-lg" name="payment_type" required>
            <option value="">Select Payment Type</option>
            <option value="Cash" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Cash') ? 'selected' : ''; ?>>Cash</option>
            <option value="Bkash" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Bkash') ? 'selected' : ''; ?>>Bkash</option>
            <option value="Nagad" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Nagad') ? 'selected' : ''; ?>>Nagad</option>
            <option value="Rocket" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Rocket') ? 'selected' : ''; ?>>Rocket</option>
            <option value="Bank Transfer" <?php echo (isset($record['payment_type']) && $record['payment_type'] === 'Bank Transfer') ? 'selected' : ''; ?>>Bank Transfer</option>
        </select>
      </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="descriptionOrNote"><?php echo $table === 'revenue' ? 'Note' : 'Description'; ?>:</label>
        <textarea class="form-control" id="descriptionOrNote" name="descriptionOrNote" required><?php echo htmlspecialchars($table === 'revenue' ? $record['note'] ?? '' : $record['description'] ?? ''); ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
