<?php
ob_start();
include('connection.php');
include('header.php');
include('sidebar.php');

// Step 1: Get the ID from URL
if (!isset($_GET['id'])) {
    echo "<p style='color: red;'>No ID provided.</p>";
    exit;
}

$id = $_GET['id'];

// Step 2: Fetch existing data
$sql = "SELECT * FROM flat_details WHERE id = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<p style='color: red;'>Record not found.</p>";
    exit;
}

$row = mysqli_fetch_assoc($result);

// Step 3: Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = $_POST['name'];
    $amount = $_POST['amount'];
    $date   = $_POST['date'];
    $note   = $_POST['note'];

    $update = "UPDATE flat_details SET name='$name', amount='$amount', date='$date', note='$note' WHERE id = $id";
    
    if (mysqli_query($conn, $update)) {
        echo "<p style='color: green;'>Record updated successfully.</p>";
        // Optional: Redirect to the listing page
        header("Location: add_flat.php?id=" . $row['building_id']);
        exit;
    } else {
        echo "<p style='color: red;'>Error updating: " . mysqli_error($conn) . "</p>";
    }
}
?>

<main class="page-content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card shadow p-4" style="border-radius: 10px; background-color: #f8f9fa;">
          <h4 class="mb-4 text-center">Edit Payment Details</h4>
          <form method="POST">
            <div class="mb-3">
              <label class="form-label">Name</label>
              <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Amount</label>
              <input type="number" name="amount" step="0.01" class="form-control" value="<?php echo htmlspecialchars($row['amount']); ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Date</label>
              <input type="date" name="date" class="form-control" value="<?php echo $row['date']; ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Note</label>
              <textarea name="note" class="form-control" rows="3"><?php echo htmlspecialchars($row['note']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include('footer.php'); ?>
