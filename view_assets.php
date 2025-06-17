<?php include ('connection.php'); ?>
<?php include ('header.php'); ?>
<?php
include ('sidebar.php');
// Check if the form is submitted

// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Get main_assets_id from URL parameter
$main_assets_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Get search parameter
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';

// Modify the count query to include search condition
$total_sql = "SELECT COUNT(*) as count FROM assets WHERE main_assets_id = $main_assets_id";
if (!empty($search)) {
    $total_sql .= " AND (asset_name LIKE '%$search%' OR asset_value LIKE '%$search%' OR date LIKE '%$search%')";
}
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['count'];
$total_pages = ceil($total_records / $records_per_page); 



// Modify the main query to include search condition
$sql = "SELECT id, asset_name, asset_value, date FROM assets
        WHERE main_assets_id = $main_assets_id";
if (!empty($search)) {
    $sql .= " AND (asset_name LIKE '%$search%' OR asset_value LIKE '%$search%' OR date LIKE '%$search%')";
}
$sql .= " ORDER BY assets.created_at DESC LIMIT $offset, $records_per_page";
$result = mysqli_query($conn, $sql);
$assets = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Calculate total asset value including parent relationships
$total_value_sql = "SELECT SUM(a.asset_value) as total_value 
                    FROM assets a
                    LEFT JOIN assets p ON a.parent_asset_id = p.id
                    WHERE a.main_assets_id = $main_assets_id 
                    OR p.main_assets_id = $main_assets_id";
$total_value_result = mysqli_query($conn, $total_value_sql);
$total_value_row = mysqli_fetch_assoc($total_value_result);
$total_asset_value = $total_value_row['total_value'] ?? 0;

?>


<main class="page-content">

  <div class="container-fluid">

    <div class="row">

<!-- Include Toastr CSS and JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>



<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w-100" id="confirmModalLabel" style="font-size: 1.25rem; font-weight: bold;  margin-top: 20px;">Are you sure?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to add this assets?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 100px;">No</button>
                <button type="button" class="btn btn-primary" id="confirmAdd" style="width: 100px;">Yes</button>
            </div>
        </div>
    </div>
</div>


    <div class="container-fluid p-0">

        <div class=" mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0" style="font-size: 1.5rem; ">Assets History</h5>
            </div>
            <div class="card-body">
                <!-- Add search form -->
                <form method="GET" class="mb-4">
                    <input type="hidden" name="id" value="<?php echo $main_assets_id; ?>">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search assets..." value="<?php echo htmlspecialchars($search); ?>">
                                <button class="btn btn-primary" type="submit">Search</button>
                                <?php if (!empty($search)): ?>
                                    <a href="?id=<?php echo $main_assets_id; ?>" class="btn btn-secondary">Clear</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-8 text-end">
                            <div class="d-inline-block bg-info text-white p-2 rounded">
                                <strong>Total Asset Value: </strong><?php echo number_format($total_asset_value, 2); ?> ৳
                            </div>
                        </div>
                    </div>
                </form>

                <table class="table table-striped" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Asset Name</th>
                            <th>Asset Value</th>
                            <th>By Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assets as $asset): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($asset['asset_name']); ?></td>
                               

                                <td>
                                <button class="btn btn-sm btn-info" onclick="viewAssetValues('<?php echo addslashes($asset['id']); ?>')">View</button>
                                </td>

                               
                                <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($asset['date']))); ?></td>
                               
                                <td>
                                <!--<a href="#" class="btn btn-sm btn-warning">Edit</a>-->
                                <a href="#" class="btn btn-sm btn-warning" onclick="loadEditForm(<?php echo $asset['id']; ?>)">Edit</a>

                                <a href="delete_view_assets.php?id=<?php echo $asset['id']; ?>" 
                                    class="btn btn-sm btn-danger" 
                                    onclick="return confirm('Are you sure you want to delete this item?');">
                                    Delete
                                </a>
                                
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Professional Pagination -->
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <div class="d-flex gap-3">
                        <a href="?page=<?php echo $page - 1; ?>&id=<?php echo $main_assets_id; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"
                            onclick="return handlePageChange(this, <?php echo $page - 1; ?>)"
                            class="btn btn-outline-primary px-4 <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            Previous
                        </a>

                        <?php
                        // Determine the range of page numbers to display
                        if ($total_pages <= 3) {
                            $start_page = 1;
                            $end_page = $total_pages;
                        } else {
                            if ($page == 1) {
                                $start_page = 1;
                                $end_page = 3;
                            } elseif ($page == $total_pages) {
                                $start_page = $total_pages - 2;
                                $end_page = $total_pages;
                            } else {
                                $start_page = $page - 1;
                                $end_page = $page + 1;
                            }
                        }

                        for ($i = $start_page; $i <= $end_page; $i++):
                            ?>
                             <a href="?id=<?php echo $main_assets_id; ?>&page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" 
                               class="btn btn-outline-secondary <?php echo ($i == $page) ? 'active' : ''; ?>"
                               onclick="return handlePageChange(this, <?php echo $i; ?>)">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <a href="?page=<?php echo $page + 1; ?>&id=<?php echo $main_assets_id; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>"
                            onclick="return handlePageChange(this, <?php echo $page + 1; ?>)"
                            class="btn btn-outline-primary px-4 <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            Next
                        </a>
                    </div>
                </div>

  <!-- Add detailed calculation history -->
  <div class="mt-4">
                    <h6>Calculation History</h6>
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Asset Name</th>
                                <th>Value</th>
                                <th>Date</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get detailed calculation history
                            $history_sql = "SELECT a.asset_name, a.asset_value, a.date, 
                                          CASE 
                                              WHEN a.id = p.id THEN 'Main Asset'
                                              WHEN a.parent_asset_id = p.id THEN 'Child Asset'
                                              ELSE 'Related Asset'
                                          END as asset_type
                                          FROM assets a
                                          LEFT JOIN assets p ON a.parent_asset_id = p.id
                                          WHERE a.main_assets_id = $main_assets_id 
                                          OR p.main_assets_id = $main_assets_id
                                          ORDER BY a.date DESC";
                            
                            $history_result = mysqli_query($conn, $history_sql);
                            $running_total = 0;
                            
                            while ($history_row = mysqli_fetch_assoc($history_result)) {
                                $running_total += floatval($history_row['asset_value']);
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($history_row['asset_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($history_row['asset_value']) . "</td>";
                                echo "<td>" . htmlspecialchars($history_row['date']) . "</td>";
                                echo "<td>" . htmlspecialchars($history_row['asset_type']) . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="3" class="text-end"><strong>Total Calculated Value:</strong></td>
                                <td><strong><?php echo number_format($running_total, 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="assetValuesModal" tabindex="-1" aria-labelledby="assetValuesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="assetValuesModalLabel">Asset Values</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="assetValuesContent">
        <!-- Values will load here dynamically -->
      </div>
    </div>
  </div>
</div>


<!-- Add Modal HTML -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit and update</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Content will be loaded here from "edit_addData.php" -->
            </div>
        </div>
    </div>
</div>

<script>
function viewAssetValues(assetId) {
    $.ajax({
        url: 'fetch_asset_values.php',
        type: 'POST',
        data: { asset_id: assetId },
        success: function(response) {
            $('#assetValuesContent').html(response);
            $('#assetValuesModal').modal('show');
        },
        error: function() {
            alert('Failed to fetch asset values.');
        }
    });
}
</script>

<script>
    // Function to load edit form into the modal
    function loadEditForm(id) {
        $.ajax({
            url: 'edit_new_assets.php',
            type: 'GET',
            data: { id: id,
            table: 'assets' 
          },
            success: function(response) {
                $('#editModal .modal-body').html(response);
                $('#editModal').modal('show');

                // Attach submit event handler to the form
                $('#editForm').on('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission

                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: $(this).serialize(),
                        success: function(response) {
                            // Handle success (e.g., close modal, show success message)
                            $('#editModal').modal('hide');
                            toastr.success('Record updated successfully!');
                            // Optionally, refresh the data on the page
                            location.reload();
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            toastr.error('Error updating record.');
                        }
                    });
                });
            }
        });
    }
</script>

<script>
    function confirmSubmission() {
        $('#confirmModal').modal('show');
        return false; // Prevent form submission
    }
    
     document.getElementById('confirmAdd').addEventListener('click', function() {
        $('#confirmModal').modal('hide');
        document.querySelector('form').submit(); // Submit the form
    });

    function handlePageChange(link, pageNum) {
        if (pageNum < 1 || pageNum > <?php echo $total_pages; ?>) {
            return false;
        }

        sessionStorage.setItem('scrollPosition', window.scrollY);

        event.preventDefault();
        window.location.href = '?page=' + pageNum + '&id=<?php echo $main_assets_id; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>';
        return false;
    }

    // Restore scroll position when page loads
    window.onload = function() {
        let scrollPosition = sessionStorage.getItem('scrollPosition');
        if (scrollPosition) {
            window.scrollTo(0, scrollPosition);
            sessionStorage.removeItem('scrollPosition');
        }
    }
</script>

<!-- Add Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center w-100" id="deleteConfirmModalLabel" style="font-size: 1.25rem; font-weight: bold; margin-top: 20px;">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this record?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 100px;">No</button>
                <button type="button" class="btn btn-primary" id="confirmDelete" style="width: 100px;">Yes</button>
            </div>
        </div>
    </div>
</div>

<script>
    let deleteUrl = '';

    // Update the delete button to open the modal
    function openDeleteModal(url) {
        deleteUrl = url;
        $('#deleteConfirmModal').modal('show');
    }

    // Handle the confirmation within the modal
    document.getElementById('confirmDelete').addEventListener('click', function() {
        $('#deleteConfirmModal').modal('hide');
        window.location.href = deleteUrl;
    });
</script>


    </div>

  </div>
</main>
<?php include ('footer.php') ?>