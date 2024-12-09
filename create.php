<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<?php
// Initialize a success message variable
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $tel = $_POST['tel'] ?? null;
  $optional_number = $_POST['optional_number'] ?? null;
  $flatno = $_POST['flatno'] ?? null;
  $flatname = $_POST['flatname'] ?? null;
  $owner = $_POST['owner'] ?? null;
  $rent = $_POST['rent'] ?? null;
  $advance = $_POST['advance'] ?? null;

  if ($tel && $flatno && $owner) {
    $query = "INSERT INTO flats(flat_number, flatname, mobile_number, optional_number, owner_name, rent, advance) VALUES('$flatno', '$flatname', '$tel', '$optional_number', '$owner', '$rent', '$advance')";
    if ($conn->query($query) === TRUE) {
      $successMessage = "New flat added successfully!";
    } else {
      echo "Error: " . $query . "<br>" . $conn->error;
    }
  } else {
    echo "Please fill all fields.";
  }
}

$conn->close();
?>

<main class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="form-group col-md-12">
        <section class="">
          <div class="content">
            <div class="contact-form-wrapper">
              <form class="form p-5 shadow-lg rounded" action="" method="POST">
                <?php if ($successMessage): ?>
                  <div id="success-message" class="alert alert-success"><?= $successMessage ?></div>
                <?php endif; ?>
                <h2 class="text-center mb-4 text-uppercase" style="letter-spacing: 2px; color: #3498db;">ADD NEW FLAT</h2>

                <div class="mb-3">
                  <input type="text" name="owner" class="form-control" placeholder="Owner Name" required>
                </div>

                <div class="mb-3">
                  <input type="text" name="flatname" class="form-control" placeholder="Flat Name" required>
                </div>

                <div class="mb-3">
                  <input type="text" name="flatno" class="form-control" placeholder="Flat Number" required>
                </div>

                <div class="mb-3">
                  <input type="tel" name="tel" class="form-control" placeholder="Phone number" required>
                </div>

                <div class="mb-3">
                  <input type="tel" name="optional_number" class="form-control" placeholder="Optional number">
                </div>

                <!-- Rent and Advance Fields in a Single Row -->
                <div class="row g-3">
                  <div class="col-md-6">
                    <input type="number" step="0.01" name="rent" class="form-control" placeholder="Rent" required>
                  </div>
                  <div class="col-md-6">
                    <input type="number" step="0.01" name="advance" class="form-control" placeholder="Advance" required>
                  </div>
                </div>

                <div class="text-center mt-4">
                  <button type="submit" class="btn btn-primary w-100" style="font-size: 1.2rem;">Submit</button>
                </div>
              </form>

            </div>
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
<?php include('footer.php') ?>