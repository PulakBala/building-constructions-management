<?php
include('connection.php');
include('header.php');
include('sidebar.php');

$id = $_GET['id'] ?? null;

$flat = []; // Initialize the flat array
if ($id) {
    $sql = "SELECT * FROM flats WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $flat = mysqli_fetch_assoc($result);
}

$updateSuccessful = false; // Flag to check if the update was successful

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $owner = $_POST['owner'];
    $flatname = $_POST['flatname'];
    $tel = $_POST['tel'];
    $optional_number = $_POST['optional_number'];
   
  
    $updateQuery = "UPDATE flats SET owner_name='$owner', flatname='$flatname', mobile_number='$tel', optional_number='$optional_number' WHERE id='$id'";
    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['message'] = "Flat updated successfully!";
        $_SESSION['msg_type'] = "success";
        $updateSuccessful = true; // Set flag to true on successful update
    } else {
        $_SESSION['message'] = "Error updating flat.";
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
                        <h2 class="text-center mb-4">EDIT AND UPDATE</h2>
                        <div class="mb-3">
                          <input type="text" name="owner" class="form-control" value="<?= $updateSuccessful ? htmlspecialchars($owner) : htmlspecialchars($flat['owner_name']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <input type="text" name="flatname" class="form-control" value="<?= $updateSuccessful ? htmlspecialchars($flatname) : htmlspecialchars($flat['flatname']) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                          <input type="tel" name="tel" class="form-control" value="<?= $updateSuccessful ? htmlspecialchars($tel) : htmlspecialchars($flat['mobile_number']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <input type="text" name="optional_number" class="form-control" value="<?= $updateSuccessful ? htmlspecialchars($optional_number) : htmlspecialchars($flat['optional_number']) ?>" required>
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
