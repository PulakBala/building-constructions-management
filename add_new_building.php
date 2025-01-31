<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<?php
// Initialize a success message variable
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data using the correct `name` attributes
    $name = $_POST['name'] ?? null;
    $address = $_POST['address'] ?? null;
    $manager_name = $_POST['manager_name'] ?? null;
    $manager_number = $_POST['tel'] ?? null; // Manager's number
    $guard_name = $_POST['guard_name'] ?? null;
    $guard_number = $_POST['tel_guard'] ?? null; // Guard's number

    // Ensure all required fields are filled
    if ($name && $address && $manager_name && $manager_number && $guard_name && $guard_number) {
        // Insert query
        $query = "INSERT INTO building_info (name, address, manager_name, manager_number, guard_name, guard_number) 
                  VALUES ('$name', '$address', '$manager_name', '$manager_number', '$guard_name', '$guard_number')";

        if ($conn->query($query) === TRUE) {
            $successMessage = "New building added successfully!";
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
        <h2 class="text-center mb-4 text-uppercase" style="letter-spacing: 2px; color: #3498db;">ADD NEW BUILDING</h2>

        <div class="mb-3">
          <input type="text" name="name" class="form-control" placeholder="Building Name" required>
        </div>

        <div class="mb-3">
          <input type="text" name="address" class="form-control" placeholder="Address" required>
        </div>

        <div class="mb-3">
          <input type="text" name="manager_name" class="form-control" placeholder="Manager Name" required>
        </div>

        <div class="mb-3">
          <input type="tel" name="tel" class="form-control" placeholder="Manager Number" required>
        </div>

        <div class="mb-3">
          <input type="text" name="guard_name" class="form-control" placeholder="Guard Name" required>
        </div>

        <div class="mb-3">
          <input type="tel" name="tel_guard" class="form-control" placeholder="Guard Number" required>
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

<?php include('footer.php'); ?>
zzz