<?php
include('connection.php');
include('header.php');
include('sidebar.php');

$id = $_GET['id'] ?? null;

$flat = []; // Initialize the flat array
if ($id) {
    // Fetch data for the selected flat
    $sql = "SELECT * FROM flat_info WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $flat = mysqli_fetch_assoc($result);
}

$updateSuccessful = false; // Flag to check if the update was successful

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $owner_name = $_POST['owner_name'];
    $mobile_number = $_POST['mobile_number'];
    $rent = $_POST['rent'];
    $advance = $_POST['advance'];
    $nid_number = $_POST['nid_number'];

    // Update query
    $updateQuery = "UPDATE flat_info 
                    SET name = '$owner_name', 
                        mobile_number = '$mobile_number', 
                        rent = '$rent', 
                        advance = '$advance',
                        nid_number = '$nid_number'
                    WHERE id = '$id'";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['message'] = "Flat updated successfully!";
        $_SESSION['msg_type'] = "success";
        $updateSuccessful = true; // Set flag to true on successful update
    } else {
        $_SESSION['message'] = "Error updating flat: " . mysqli_error($conn);
        $_SESSION['msg_type'] = "error";
    }
}
?>

<main class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="form-group col-md-12">
        <section class="">
          <div class="content">
            <div class="contact-form-wrapper">
              <form class="form p-5 shadow-lg rounded" method="POST">
                <!-- Notification message display -->
                <?php if (isset($_SESSION['message'])): ?>
                  <div class="alert alert-<?= $_SESSION['msg_type'] ?> text-center" id="notification">
                      <?= htmlspecialchars($_SESSION['message']) ?>
                      <?php unset($_SESSION['message']); ?> <!-- Clear message after displaying -->
                  </div>
                <?php endif; ?>
                <h2 class="text-center mb-4">Update Flat Info</h2>

                <div class="mb-3">
                  <label for="owner_name">Owner Name</label>
                  <input type="text" name="owner_name" class="form-control" value="<?= htmlspecialchars($flat['name'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                  <label for="mobile_number">Mobile Number</label>
                  <input type="tel" name="mobile_number" class="form-control" value="<?= htmlspecialchars($flat['mobile_number'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                  <label for="rent">Rent (in decimal)</label>
                  <input type="number" step="0.01" name="rent" class="form-control" value="<?= htmlspecialchars($flat['rent'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                  <label for="advance">Advance (in decimal)</label>
                  <input type="number" step="0.01" name="advance" class="form-control" value="<?= htmlspecialchars($flat['advance'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                  <label for="flat_number">Nid Number</label>
                  <input type="text" name="nid_number" class="form-control" value="<?= htmlspecialchars($flat['nid_number'] ?? '') ?>" required>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary w-100">Update</button>
                </div>
              </form>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</main>

<?php include('footer.php') ?>

<script>
    // Hide the notification after 5 seconds
    setTimeout(function() {
        const notification = document.getElementById('notification');
        if (notification) {
            notification.style.display = 'none';
        }
    }, 5000); // 5000 milliseconds = 5 seconds
</script>
