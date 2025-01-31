<?php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
include('connection.php');
include('header.php');
include('sidebar.php');

// Initialize a success message variable
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $projects = $_POST['projects'] ?? null;
    $date = $_POST['date'] ?? null;

    // Check required fields
    if ($projects && $date) {
        // Use prepared statement for secure data insertion
        $query = $conn->prepare("INSERT INTO main_project (project_name, date) VALUES (?, ?)");
        $query->bind_param("ss", $projects, $date);

        if ($query->execute()) {
            $successMessage = "New asset added successfully!";
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
              <h2 class="text-center mb-4 text-uppercase" style="letter-spacing: 2px; color: #3498db;">Create Project</h2>

              <div class="mb-3">
                <input type="text" name="projects" class="form-control" placeholder="Project Name" required>
              </div>

              <div class="mb-3">
                <input type="date" name="date" class="form-control">
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