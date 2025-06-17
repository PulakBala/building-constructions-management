<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('connection.php');?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); 
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve form data
  $main_project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Get main_projects_id from URL
  $worker_name = mysqli_real_escape_string($conn, $_POST['worker_name']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $contact = mysqli_real_escape_string($conn, $_POST['contact']);
  $worker_title = mysqli_real_escape_string($conn, $_POST['worker_title']);
  $date = mysqli_real_escape_string($conn, $_POST['date']);

 

  $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session

  // Prepare and execute the SQL query to insert data using MySQLi
  $sql = "INSERT INTO workers_details (name, address, contact, worker_title, main_project_id, date) VALUES ('$worker_name', '$address', '$contact', '$worker_title', '$main_project_id', '$date')";

  if (mysqli_query($conn, $sql)) {
    echo "<script>toastr.success('Worker details added successfully!');</script>";
    echo "<script>window.location.href = window.location.href;</script>";
  } else {
    echo "<script>toastr.error('Error: " . mysqli_error($conn) . "');</script>";
  }
}

$main_project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Get main_projects_id from URL


// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Fetch total number of records
$total_sql = "SELECT COUNT(*) as count FROM workers_details";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['count'];
$total_pages = ceil($total_records / $records_per_page);

// Modify the existing query to include pagination
$sql = "SELECT id, name, address, contact, worker_title, date, created_at FROM workers_details 
      WHERE main_project_id = '$main_project_id' 
      ORDER BY workers_details.created_at DESC 
      LIMIT $offset, $records_per_page";
$result = mysqli_query($conn, $sql);
$workers_detailss = mysqli_fetch_all($result, MYSQLI_ASSOC);


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
                <p>Are you sure you want to add this workers_details?</p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="width: 100px;">No</button>
                <button type="button" class="btn btn-primary" id="confirmAdd" style="width: 100px;">Yes</button>
            </div>
        </div>
    </div>
</div>


    <div class="container-fluid p-0">
        <div class="row justify-content-start align-items-start">
            <div class="col-12 col-md-8 col-lg-7">
                <div class="">
                    <div class="card-header">
                        <h5 class="card-title mb-0" style="font-size: 1.2rem; ">ADD WORKER DETAILS</h5>
                    </div>
                    <div class="card-body" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <form action="" method="post" onsubmit="return confirmSubmission()">
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Worker Name</label>
                                <input type="text" class="form-control form-control" name="worker_name" placeholder="Enter worker name" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Address</label>
                                <input type="text" class="form-control form-control" name="address" placeholder="Enter address" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Contact Number</label>
                                <input type="text" class="form-control form-control" name="contact" placeholder="Enter contact number" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Worker Title</label>
                                <input type="text" class="form-control form-control" name="worker_title" placeholder="Enter worker title" required>
                            </div>
                            
                             <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Date</label>
                                <input type="date" class="form-control form-control" name="date" required>
                            </div>


                            <button type="submit" class="btn btn-primary ">
                                <i class="align-middle" data-feather="plus"></i>
                                Add Worker Details
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Display the data in a table -->

            </div>
        </div>
        <div class=" mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0" style="font-size: 1.5rem; ">Payment History</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Worker Name</th>
                            <th>Address</th>
                            <th>Contact Number</th>
                            <th>Worker Title</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($workers_detailss as $workers_details): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($workers_details['name']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($workers_details['date']))); ?></td>
                              
                                <td><?php echo htmlspecialchars($workers_details['address']); ?></td>
                                <td><?php echo htmlspecialchars($workers_details['contact']); ?></td>
                                <td><?php echo htmlspecialchars($workers_details['worker_title']); ?></td>
                               
                                <td>
                                <a href="javascript:void(0);" onclick="loadEditForm(<?php echo htmlspecialchars($workers_details['id']); ?>);" class="btn btn-sm btn-warning" style="display: none;">Edit</a>

                                <a href="javascript:void(0);" onclick="openDeleteModal('delete_add_payment.php?table=workers_details&id=<?php echo htmlspecialchars($workers_details['id']); ?>');" class="btn btn-sm btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Professional Pagination -->
                <div class="d-flex justify-content-center align-items-center mt-4">
                    <div class="d-flex gap-3">
                        <a href="?page=<?php echo $page - 1; ?>"
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

                        for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <a href="?page=<?php echo $i; ?>"
                                onclick="return handlePageChange(this, <?php echo $i; ?>)"
                                class="btn btn-outline-secondary px-2 mx-1 py-1 <?php echo ($i == $page) ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <a href="?page=<?php echo $page + 1; ?>"
                            onclick="return handlePageChange(this, <?php echo $page + 1; ?>)"
                            class="btn btn-outline-primary px-4 <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                            Next
                        </a>
                    </div>
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
    // Function to load edit form into the modal
    function loadEditForm(id) {
        $.ajax({
            url: 'edit_worker_details.php',
            type: 'GET',
            data: { id: id, table: 'workers_details' },
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

        // Store current scroll position in session storage
        sessionStorage.setItem('scrollPosition', window.scrollY);

        // Prevent default behavior and handle navigation manually
        event.preventDefault();
        window.location.href = '?page=' + pageNum;
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
<?php include('footer.php') ?>