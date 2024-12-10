<?php
include('connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$table = isset($_GET['table']) ? $_GET['table'] : 'workers_details';

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

    // Debug: Print out all POST data
    error_log("POST Data: " . print_r($_POST, true));

    try {
        // Prepare SQL for updating all fields in workers_details
        $sql = "UPDATE workers_details 
                SET name = ?, 
                    address = ?, 
                    contact = ?, 
                    worker_title = ?, 
                    date = ? 
                WHERE id = ?";
        
        $stmt = $conn->prepare($sql);
        
        // Debug: Check if prepare was successful
        if (!$stmt) {
            error_log("Prepare failed: " . $conn->error);
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Bind parameters: 5 strings and 1 integer for ID
        $stmt->bind_param('sssssi', 
            $_POST['name'], 
            $_POST['address'], 
            $_POST['contact'], 
            $_POST['worker_title'], 
            $_POST['date'], 
            $id
        );

        // Debug: Check bind_param result
        if ($stmt->errno) {
            error_log("Bind param failed: " . $stmt->error);
            throw new Exception("Bind param failed: " . $stmt->error);
        }

        $executeResult = $stmt->execute();
        
        // Debug: Check execute result
        if (!$executeResult) {
            error_log("Execute failed: " . $stmt->error);
            throw new Exception("Execute failed: " . $stmt->error);
        }

        // More detailed error logging
        error_log("Affected rows: " . $stmt->affected_rows);
        
        if ($executeResult) {
            echo "Record updated successfully.";
        } else {
            throw new Exception("No rows updated.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        error_log($e->getMessage());
    } finally {
        if (isset($stmt)) {
            $stmt->close();
        }
    }
}
?>

<form id="editForm" action="edit_add_payment.php?table=workers_details" method="post">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id'] ?? ''); ?>">
    
    <div class="form-group">
        <label for="name">Worker Name:</label>
        <input type="text" class="form-control" id="name" name="name" 
               value="<?php echo htmlspecialchars($record['name'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="address">Address:</label>
        <input type="text" class="form-control" id="address" name="address" 
               value="<?php echo htmlspecialchars($record['address'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="contact">Contact Number:</label>
        <input type="text" class="form-control" id="contact" name="contact" 
               value="<?php echo htmlspecialchars($record['contact'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="worker_title">Worker Title:</label>
        <input type="text" class="form-control" id="worker_title" name="worker_title" 
               value="<?php echo htmlspecialchars($record['worker_title'] ?? ''); ?>" required>
    </div>

    <div class="form-group">
        <label for="date">Date:</label>
        <input type="date" class="form-control" id="date" name="date" 
               value="<?php echo htmlspecialchars($record['date'] ?? ''); ?>" required>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
</form>