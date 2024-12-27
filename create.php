<?php
include('connection.php');
include('header.php');
include('sidebar.php');

// Initialize a success message variable
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tel = $_POST['tel'] ?? null;
    $optional_number = $_POST['optional_number'] ?? null;
    $flatname = $_POST['flatname'] ?? null;
    $owner = $_POST['owner'] ?? null;

    // Check required fields
    if ($tel && $flatname && $owner) {
        // Use prepared statement for secure data insertion
        $query = $conn->prepare("INSERT INTO flats (flatname, mobile_number, optional_number, owner_name) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss", $flatname, $tel, $optional_number, $owner);

        if ($query->execute()) {
            $successMessage = "New flat added successfully!";
        } else {
            echo "Error: " . $query->error;
        }
    } else {
        echo "Please fill all required fields.";
    }
}

$conn->close();
?>

<main class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="form-group col-md-12">
        <section class="content">
          <div class="contact-form-wrapper">
            <form class="form p-5 shadow-lg rounded" action="" method="POST">
              <?php if ($successMessage): ?>
                <div id="success-message" class="alert alert-success"><?= $successMessage ?></div>
              <?php endif; ?>
              <h2 class="text-center mb-4 text-uppercase" style="letter-spacing: 2px; color: #3498db;">ADD MANAGER</h2>

              <div class="mb-3">
                <input type="text" name="owner" class="form-control" placeholder="Manager Name" required>
              </div>

              <div class="mb-3">
                <input type="text" name="flatname" class="form-control" placeholder="Building Name" required>
              </div>

              <div class="mb-3">
                <input type="tel" name="tel" class="form-control" placeholder="Phone Number" required>
              </div>

              <div class="mb-3">
                <input type="tel" name="optional_number" class="form-control" placeholder="Optional Number">
              </div>

              <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary w-100" style="font-size: 1.2rem;">Submit</button>
              </div>
            </form>
          </div>
        </section>
      </div>
    </div>
  </div>
</main>

<script>
  setTimeout(function() {
    var successMessage = document.getElementById('success-message');
    if (successMessage) {
      successMessage.style.display = 'none';
    }
  }, 5000); // 5 seconds
</script>
<?php include('footer.php'); ?>
