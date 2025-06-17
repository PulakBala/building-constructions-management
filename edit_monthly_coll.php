<?php
include('connection.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['f_flatId'])) {
    $id = $_GET['f_flatId'];
    $month = isset($_GET['month']) ? $_GET['month'] : date('F', strtotime('-1 month'));
    $year = isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('-1 month'));

    // Changed 'id' to 'f_id' in the SELECT statement
    $sql = "SELECT f_id, f_flat_rent, f_c_current_bill, f_guard_slry, f_c_center_various, f_due_flat, f_date FROM flat_bill WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iss', $id, $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $records = $result->fetch_all(MYSQLI_ASSOC);

    if (!$records) {
        echo "Records not found.";
        exit;
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $f_id = $_POST['f_id'];
    $f_flat_rent = $_POST['f_flat_rent'];
    $f_c_current_bill = $_POST['f_c_current_bill'];
    $f_guard_slry = $_POST['f_guard_slry'];
    $f_c_center_various = $_POST['f_c_center_various'];
    $f_due_flat = $_POST['f_due_flat'];
    $f_date = $_POST['f_date'];

    // Get flatId, month and year from the form
    $sql_get_details = "SELECT f_flatId, f_month, f_year FROM flat_bill WHERE f_id = ?";
    $stmt_details = $conn->prepare($sql_get_details);
    $stmt_details->bind_param('i', $f_id);
    $stmt_details->execute();
    $result_details = $stmt_details->get_result();
    $details = $result_details->fetch_assoc();
    
    if ($details) {
        $id = $details['f_flatId'];
        $month = $details['f_month'];
        $year = $details['f_year'];
        
        try {
            // 1. প্রথমে flat_bill টেবিল আপডেট করি
            $sql = 'UPDATE flat_bill SET 
                    f_flat_rent = ?, 
                    f_c_current_bill = ?, 
                    f_guard_slry = ?, 
                    f_c_center_various = ?, 
                    f_due_flat = ? 
                    WHERE f_id = ?';
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('dddddi', $f_flat_rent, $f_c_current_bill, $f_guard_slry, $f_c_center_various, $f_due_flat, $f_id);
            
            if (!$stmt->execute()) {
                throw new Exception("Error updating flat_bill: " . $conn->error);
            }
    
            // 2. এরপর flat_bill থেকে মোট f_due_flat ক্যালকুলেট করি
            $sql_sum = "SELECT SUM(f_due_flat) as total_due 
                       FROM flat_bill 
                       WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
            $stmt_sum = $conn->prepare($sql_sum);
            $stmt_sum->bind_param('iss', $id, $month, $year);
            
            if (!$stmt_sum->execute()) {
                throw new Exception("Error calculating total due: " . $conn->error);
            }
            
            $result_sum = $stmt_sum->get_result();
            $row_sum = $result_sum->fetch_assoc();
            $total_due = $row_sum['total_due'];
    
            // 3. payments টেবিলে মোট f_due আপডেট করি
            $sql_payment = "UPDATE payments 
                           SET f_due = ? 
                           WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
            $stmt_payment = $conn->prepare($sql_payment);
            $stmt_payment->bind_param('diss', $total_due, $id, $month, $year);
            
            if (!$stmt_payment->execute()) {
                throw new Exception("Error updating payments: " . $conn->error);
            }
    
            echo '<div class="alert alert-success">Record updated successfully in both tables.</div>';
    
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        } finally {
            if (isset($stmt)) $stmt->close();
            if (isset($stmt_sum)) $stmt_sum->close();
            if (isset($stmt_payment)) $stmt_payment->close();
            if (isset($stmt_details)) $stmt_details->close();
        }
    } else {
        echo '<div class="alert alert-danger">Could not find flat details for the given ID.</div>';
    }
}
?>

<div class="container">
    <?php if (!empty($records)): ?>
        <h4>Edit Flat Rent Records</h4>
        <?php foreach ($records as $index => $record): ?>
            <form id="editForm_<?php echo $record['f_id']; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" class="mb-3">
                <input type="hidden" name="f_id" value="<?php echo htmlspecialchars($record['f_id']); ?>">
                <input type="hidden" name="f_date" value="<?php echo htmlspecialchars($record['f_date']); ?>">
                
                <div class="form-group mb-3">
                    <label for="f_flat_rent_<?php echo $record['f_id']; ?>">Flat Rent (Date: <?php echo htmlspecialchars($record['f_date']); ?>):</label>
                    <input type="number" class="form-control" id="f_flat_rent_<?php echo $record['f_id']; ?>" name="f_flat_rent" 
                           value="<?php echo htmlspecialchars($record['f_flat_rent']); ?>" required>
                </div>

                 
         <!-- Added fields for current bill, guard salary, and center various -->
        <div class="form-group mb-3">
            <label for="f_c_current_bill_<?php echo $record['f_id']; ?>">Current Bill:</label>
            <input type="number" class="form-control" id="f_c_current_bill_<?php echo $record['f_id']; ?>" name="f_c_current_bill" 
                   value="<?php echo htmlspecialchars($record['f_c_current_bill']); ?>" required>
        </div>
        
        <div class="form-group mb-3">
            <label for="f_guard_slry_<?php echo $record['f_id']; ?>">Guard Salary:</label>
            <input type="number" class="form-control" id="f_guard_slry_<?php echo $record['f_id']; ?>" name="f_guard_slry" 
                   value="<?php echo htmlspecialchars($record['f_guard_slry']); ?>" required>
        </div>
        
        <div class="form-group mb-3">
            <label for="f_c_center_various_<?php echo $record['f_id']; ?>">Other Expense:</label>
            <input type="number" class="form-control" id="f_c_center_various_<?php echo $record['f_id']; ?>" name="f_c_center_various" 
                   value="<?php echo htmlspecialchars($record['f_c_center_various']); ?>" required>
        </div>
        
          <div class="form-group mb-3">
                    <label for="f_due_flat_<?php echo $record['f_id']; ?>">Flat due amount:</label>
                    <input type="number" class="form-control" id="f_due_flat_<?php echo $record['f_id']; ?>" name="f_due_flat" 
                        value="<?php echo htmlspecialchars($record['f_due_flat']); ?>" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>
</div>