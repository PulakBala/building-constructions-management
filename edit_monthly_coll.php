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
    $sql = "SELECT f_id, f_flat_rent, f_date FROM flat_bill WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
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
    $f_id = $_POST['f_id']; // Changed from 'id' to 'f_id'
    $f_flat_rent = $_POST['f_flat_rent'];
    $f_date = $_POST['f_date'];

    try {
        // Changed 'id' to 'f_id' in the WHERE clause
        $sql = "UPDATE flat_bill SET f_flat_rent = ? WHERE f_id = ? AND f_date = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('dis', $f_flat_rent, $f_id, $f_date);

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
                
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>
</div>