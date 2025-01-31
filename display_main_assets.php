<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>
<?php
// Add this code block before including footer.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $main_assets_id = mysqli_real_escape_string($conn, $_POST['main_assets_id']);
    $asset_name = mysqli_real_escape_string($conn, $_POST['asset_name']);
    $asset_value = mysqli_real_escape_string($conn, $_POST['asset_value']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    
    $sql = "INSERT INTO assets (main_assets_id, asset_name, asset_value, date) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "isds", $main_assets_id, $asset_name, $asset_value, $date);
    
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
  $note_id = mysqli_real_escape_string($conn, $_POST['note_id']);
  $note_number = mysqli_real_escape_string($conn, $_POST['note_number']);

  $sql = "UPDATE main_assets SET note_number = ? WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "si", $note_number, $note_id);

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
          // Updated SQL query for assets table
          $sqlSelect = "SELECT id, asset_name, date, note_number FROM main_assets"; 
          $result = mysqli_query($conn, $sqlSelect);

          if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
          ?>
              <div class="col-md-4 mb-4">
                <div class="card">
                  <div class="card-body p-4 shadow-lg rounded" style="background-color: #f8f9fa;">
                    <h5 class="card-title">
                      <span class="fw-bold" style="color: #3498db;">Asset Name:
                      </span> </br> </br>
                      <span class="text-muted"><?php echo htmlspecialchars($data["asset_name"]); ?></span>
                    </h5>
                    <hr>
                    <p class="card-text">
                      <span class="fw-bold" style="color: #2ecc71;">Date:</span>
                      <span class="text-muted"><?php echo htmlspecialchars($data["date"]); ?></span>
                    </p>

                    <!-- Updated buttons with icons -->
                    <div class="mt-3">
                      <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#phoneModal" onclick="setNoteId(<?php echo $data['id']; ?>, '<?php echo htmlspecialchars($data['note_number']); ?>')">
                        <i class="fas fa-phone"></i>
                      </a>
                      <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addAssetModal" onclick="setMainAssetId(<?php echo $data['id']; ?>)">
                        <i class="fas fa-plus"></i>
                      </a>
                      <a href="view_assets.php?id=<?php echo $data['id']; ?>" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> View
                      </a>
                      <a href="delete_main_assets.php?id=<?php echo $data["id"]; ?>" class="btn btn-danger btn-sm" onclick='return confirm("Are you sure you want to delete this record?");'>
                        <i class="fas fa-trash"></i> Delete
                      </a>
                    </div>
                  </div>
                </div>
              </div>
          <?php
            }
          } else {
            echo "<p class='text-center text-muted'>No assets found!</p>";
          }
          ?>
        </div>
      </section>
    </div>
  </div>
</main>

<div class="modal fade" id="addAssetModal" tabindex="-1" aria-labelledby="addAssetModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="card-title mb-0" style="font-size: 1.2rem;">ADD NEW ASSETS</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="card-body" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return confirmSubmission()">
          <input type="hidden" name="main_assets_id" id="main_assets_id">
            <div class="mb-3">
              <label class="form-label" style="font-size: 1rem; font-weight: bold;">Asset Name</label>
              <input type="text" class="form-control form-control" name="asset_name" placeholder="Enter asset name" required>
            </div>

            <div class="mb-3">
              <label class="form-label" style="font-size: 1rem; font-weight: bold;">Asset Value</label>
              <input type="text" class="form-control form-control" name="asset_value" placeholder="Enter asset value" required>
            </div>

            <div class="mb-3">
              <label class="form-label" style="font-size: 1rem; font-weight: bold;">By Date</label>
              <input type="date" class="form-control form-control" name="date" required>
            </div>

            <button type="submit" class="btn btn-primary">
              <i class="align-middle" data-feather="plus"></i>
              Add New Assets
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

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
          <p id="note_number_display"></p>
          <textarea class="form-control" name="note_number" rows="4" placeholder="Enter your text here..."></textarea>
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
    document.getElementById('main_assets_id').value = id;
}
function setNoteId(id, noteNumber) {
    document.getElementById('note_id').value = id;
    document.getElementById('note_number_display').innerText = noteNumber;
}
</script>

<?php include('footer.php'); ?>
