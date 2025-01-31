<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<?php
// Add this code block before including footer.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $main_projects_id = mysqli_real_escape_string($conn, $_POST['main_projects_id']);
    $project_name = mysqli_real_escape_string($conn, $_POST['project_name']);
    $asset_value = mysqli_real_escape_string($conn, $_POST['asset_value']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    
    $sql = "INSERT INTO projects (main_projects_id, project_name, asset_value, date) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isds", $main_projects_id, $project_name, $asset_value, $date);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Asset added successfully!');
            window.location.href = '" . $_SERVER['PHP_SELF'] . "';
        </script>";
    } else {
        echo "<script>
            alert('Error adding asset: " . mysqli_error($conn) . "');
        </script>";
    }
    
    mysqli_stmt_close($stmt);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {


  if (mysqli_stmt_execute($stmt)) {
      echo "<script>
          alert('Note updated successfully!');
          window.location.href = '" . $_SERVER['PHP_SELF'] . "';
      </script>";
  } else {
      echo "<script>
          alert('Error updating note: " . mysqli_error($conn) . "');
      </script>";
  }

  mysqli_stmt_close($stmt);
}
?>
<main class="page-content">
  <div class="container-fluid">
    <div class="row">
      <section class="container my-4">

        <div class="row justify-content-end mb-4">
          <div class="col-md-6">
            <!-- Search Input with Bootstrap Design -->
            <div class="input-group">
              <input type="text" id="searchInput" class="form-control" placeholder="Search by Name or Address...">
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Results will appear here -->
        <div id="searchResults" class="my-4"></div>

        <div class="card-container row" style="gap:0;">
          <?php
          // Updated SQL query for projects table
          $sqlSelect = "SELECT id, project_name, date FROM main_project"; 
          $result = mysqli_query($conn, $sqlSelect);
          // var_dump($result);

          if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
          ?>
              <div class="col-md-4 mb-4">
                <div class="card">
                  <div class="card-body p-4 shadow-lg rounded" style="background-color: #f8f9fa;">
                    <h5 class="card-title">
                      <span class="fw-bold" style="color: #3498db;">Project Name:
                      </span> </br> </br>
                      <span class="text-muted"><?php echo htmlspecialchars($data["project_name"]); ?></span>
                    </h5>
                    <hr>
                    <p class="card-text">
                      <span class="fw-bold" style="color: #2ecc71;">Date:</span>
                      <span class="text-muted"><?php echo htmlspecialchars($data["date"]); ?></span>
                    </p>

                    <!-- Updated buttons with icons -->
                    <div class="mt-3">
                      <a href="add_payment_con.php?id=<?php echo $data['id']; ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus">A P</i>
                      </a>
                      <a href="add_worker_details.php?id=<?php echo $data['id']; ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> W D
                      </a>
                      <a href="add_construction.php?id=<?php echo $data['id']; ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> C S
                      </a>
                      <a href="add_constructions_element.php?id=<?php echo $data['id']; ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> C E
                      </a>
                      <a href="delete_main_projects.php?id=<?php echo $data["id"]; ?>" class="btn btn-danger btn-sm" onclick='return confirm("Are you sure you want to delete this record?");'>
                        <i class="fas fa-trash"></i> 
                      </a>
                    </div>
                  </div>
                </div>
              </div>
          <?php
            }
          } else {
            echo "<p class='text-center text-muted'>No projects found!</p>";
          }
          ?>
        </div>
      </section>
    </div>
  </div>
</main>



<div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="phoneModalLabel">Phone Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
          <input type="hidden" name="note_id" id="note_id">
         
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<script>
function setMainAssetId(id) {
    document.getElementById('main_projects_id').value = id;
}

</script>

<?php include('footer.php'); ?>
