<?php include ('connection.php'); ?>
<?php include ('header.php'); ?>
<?php include ('sidebar.php'); ?>
<?php

// Add this code block before including footer.php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
  // Check if asset form is submitted
  if (isset($_POST['asset_name']) && isset($_POST['asset_value']) && isset($_POST['date'])) {
    $main_assets_id = mysqli_real_escape_string($conn, $_POST['main_assets_id']);
    $asset_name = mysqli_real_escape_string($conn, $_POST['asset_name']);
    $asset_value = mysqli_real_escape_string($conn, $_POST['asset_value']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $parent_asset_id = isset($_POST['parent_asset_id']) ? intval($_POST['parent_asset_id']) : NULL;

    $sql = 'INSERT INTO assets (main_assets_id, asset_name, asset_value, date,  parent_asset_id) VALUES (?, ?, ?, ?, ?)';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'isdsi', $main_assets_id, $asset_name, $asset_value, $date, $parent_asset_id);

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

  // Check if note form is submitted
  if (isset($_POST['note_id']) && isset($_POST['note_number'])) {
    $note_id = mysqli_real_escape_string($conn, $_POST['note_id']);
    $note_number = trim($_POST['note_number']); // Trim whitespace
    
    // Debug information
    error_log("Updating note - ID: " . $note_id . ", Note: " . $note_number);

    // If note_number is empty, set it to NULL
    if (empty($note_number)) {
        $note_number = null;
    }
    

    $sql = 'UPDATE main_assets SET note_number = ? WHERE id = ?';
    $stmt = mysqli_prepare($conn, $sql);
    
    // Use 's' for string or NULL
    mysqli_stmt_bind_param($stmt, 'si', $note_number, $note_id);

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
}

// Add search parameter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Updated SQL query for assets table with search condition
$sqlSelect = 'SELECT id, asset_name, date, note_number FROM main_assets';
if (!empty($search)) {
    $sqlSelect .= " WHERE asset_name LIKE '%$search%' OR note_number LIKE '%$search%'";
}
$result = mysqli_query($conn, $sqlSelect);

?>
<main class="page-content">
  <div class="container-fluid">
    <div class="row">
      <section class="container my-4">

        <div class="row justify-content-end mb-4">
          <div class="col-md-6">
            <!-- Search Input with Bootstrap Design -->
            <form method="GET" class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search by Name or Note..." value="<?php echo htmlspecialchars($search); ?>">
              <div class="input-group-append">
                <button type="submit" class="input-group-text"><i class="fas fa-search"></i></button>
              </div>
              <?php if (!empty($search)): ?>
                <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">Clear</a>
              <?php endif; ?>
            </form>
          </div>
        </div>

        <div class="card-container row" style="gap:0;">
          <?php
          if (mysqli_num_rows($result) > 0) {
            while ($data = mysqli_fetch_assoc($result)) {
              ?>
              <div class="col-md-4 mb-4">
                <div class="card">
                  <div class="card-body p-4 shadow-lg rounded" style="background-color: #f8f9fa;">
                    <h5 class="card-title">
                      <span class="fw-bold" style="color: #3498db;">Asset Name:
                      </span> </br> </br>
                      <span class="text-muted"><?php echo htmlspecialchars($data['asset_name']); ?></span>
                    </h5>
                    <hr>
                   <p class="card-text">
                      <span class="fw-bold" style="color: #e67e22;">Total Amount:</span>
                      <span class="text-muted" id="totalAmount_<?php echo $data['id']; ?>">
                      <?php
                      // Fetch total amount for the main asset including parent-child relationships
                      $sqlTotal = "SELECT SUM(a.asset_value) as total_value 
                                   FROM assets a
                                   LEFT JOIN assets p ON a.parent_asset_id = p.id
                                   WHERE a.main_assets_id = ? 
                                   OR p.main_assets_id = ?";
                      $stmtTotal = $conn->prepare($sqlTotal);
                      $stmtTotal->bind_param('ii', $data['id'], $data['id']);
                      $stmtTotal->execute();
                      $resultTotal = $stmtTotal->get_result();
                      $totalRow = $resultTotal->fetch_assoc();
                      echo htmlspecialchars(number_format($totalRow['total_value'] ?? 0, 0));
                      ?>
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
                         <a href="tax.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-sm">
                              <i class="fas fa-dollar-sign"></i>
                          </a>
                         <a href="#" onclick="loadEditForm(<?php echo $data['id']; ?>)" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="delete_main_assets.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm" onclick='return confirm("Are you sure you want to delete this record?");'>
                        <i class="fas fa-trash"></i> 
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

<!-- add new assets model  -->

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
            <input type="hidden" name="parent_asset_id" id="parent_asset_id">
            <!-- <div class="mb-3">
              <label class="form-label" style="font-size: 1rem; font-weight: bold;">Asset Name</label>
              <input type="text" class="form-control form-control" name="asset_name" placeholder="Enter asset name" required>
            </div> -->
            <div class="mb-3">
                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Asset Name</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="asset_name" id="asset_name" placeholder="Enter asset name" required>
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Select Asset
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" id="assetDropdown">
                        <?php
                        // Get the main_assets_id from the hidden input field
                        $main_assets_id = isset($_POST['main_assets_id']) ? intval($_POST['main_assets_id']) : 0;
                        var_dump($main_assets_id);

                        // Query to get assets for the selected main asset
                        $sql = 'SELECT id, asset_name FROM assets WHERE main_assets_id = ? ORDER BY date';
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $main_assets_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        while ($row = $result->fetch_assoc()) {
                          echo '<li><a class="dropdown-item" href="#" onclick="selectAsset(' . $row['id'] . ", '" . htmlspecialchars($row['asset_name']) . '\')">'
                            . htmlspecialchars($row['asset_name']) . '</a></li>';
                        }
                        ?>
                    </ul>
                </div>
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

<!-- add contact number  -->
<div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="phoneModalLabel">Phone </h5>
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

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Asset</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- The form will be loaded here via AJAX -->
      </div>
     
    </div>
  </div>
</div>



<script>
function setMainAssetId(id) {
    document.getElementById('main_assets_id').value = id;
    fetchAssets(id);
}

function fetchAssets(main_assets_id) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_assets.php?main_assets_id=' + main_assets_id, true);
    xhr.onload = function() {
        if (this.status == 200) {
            document.getElementById('assetDropdown').innerHTML = this.responseText;
        }
    };
    xhr.send();
}

function setNoteId(id, noteNumber) {
    document.getElementById('note_id').value = id;
    document.getElementById('note_number_display').innerText = noteNumber || 'No note available';
    // Clear the textarea when opening the modal
    document.querySelector('textarea[name="note_number"]').value = noteNumber || '';
}

function selectAsset(id, name) {
    document.getElementById('main_assets_id').value = id;
    document.getElementById('asset_name').value = name;
    document.getElementById('parent_asset_id').value = id; // set parent asset id
}

</script>

<script>
    // Function to load edit form into the modal
    function loadEditForm(id) {
        $.ajax({
            url: 'edit_asset.php',
            type: 'GET',
            data: { id: id, table: 'main_assets' },
            success: function(response) {
                $('#editModal .modal-body').html(response);
                $('#editModal').modal('show');

                // Attach submit event handler to the form
                $('#editForm').on('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission
                  

                    $.ajax({
                        url: $(this).attr('action'), // Ensure this is correct
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            // Handle success (e.g., close modal, show success message)
                            $('#editModal').modal('hide');
                            toastr.success('Record updated successfully!');
                            location.reload(); // Refresh the page to see changes
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error); // Log error
                            toastr.error('Error updating record.');
                        }
                    });
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error); // Log error
            }
        });
    }
</script>

<?php include ('footer.php'); ?>
