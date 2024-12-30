<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<?php
// Initialize a success message variable
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $tel = $_POST['tel'] ?? null;
  // $flatno = $_POST['flatno'] ?? null;
  $name = $_POST['flatname'] ?? null;
  $rent = $_POST['rent'] ?? null;
  $advance = $_POST['advance'] ?? null;
  $nid_number = $_POST['nid_number'] ?? null;
  $nid_img = $_POST['nid_img'] ?? null;

  if ($tel && $nid_number) {
    $query = "INSERT INTO flat_info(name, mobile_number, rent, advance, nid_number, nid_img) VALUES('$name', '$tel', '$rent', '$advance', '$nid_number', '$nid_img')";
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
    <div class="contact-form-wrapper">
      <form class="form p-5 shadow-lg rounded" action="" method="POST">
        <?php if ($successMessage): ?>
          <div id="success-message" class="alert alert-success"><?= $successMessage ?></div>
        <?php endif; ?>
        <h2 class="text-center mb-4 text-uppercase" style="letter-spacing: 2px; color: #3498db;">ADD NEW FLAT</h2>


        <div class="mb-3">
          <input type="number" name="rent" class="form-control" placeholder="Rent" required>
        </div>

        <div class="mb-3">
          <input type="number" name="advance" class="form-control" placeholder="Advance" required>
        </div>

        <div class="mb-3">
          <input type="tel" name="tel" class="form-control" placeholder="Mobile number" required>
        </div>

        <div class="mb-3">
          <input type="text" name="flatname" class="form-control" placeholder="Name" required>
        </div>

        <!-- <div class="mb-3">
          <input type="text" name="flatno" class="form-control" placeholder="Flat Number" required>
        </div> -->



        <div class="mb-3">
          <input type="text" name="nid_number" class="form-control" placeholder="NID Number" required>
        </div>

        <div class="mb-3">
          <input type="text" name="nid_img" class="form-control" placeholder="NID Image URL">
        </div>

        <div class="text-center mt-4">
          <button type="submit" class="btn btn-primary w-100" style="font-size: 1.2rem;">Submit</button>
        </div>
      </form>

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