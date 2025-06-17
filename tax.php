<?php include('connection.php');?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); 
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Retrieve form data
  $main_tax_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Get main_projects_id from URL
  $amount = mysqli_real_escape_string($conn, $_POST['amount']);
  $date = mysqli_real_escape_string($conn, $_POST['date']);

  $user_id = $_SESSION['user_id']; // Assuming user ID is stored in session
  $full_name = mysqli_real_escape_string($conn, $_POST['full_name']); // Retrieve full_name from form data
  // Prepare and execute the SQL query to insert data using MySQLi
  $sql = "INSERT INTO tax (main_tax_id, full_name, amount, date) VALUES ('$main_tax_id', '$full_name', '$amount', '$date')";

  if (mysqli_query($conn, $sql)) {
    echo "<script>toastr.success('Tax added successfully!');</script>";
    echo "<script>window.location.href = window.location.href;</script>";
  } else {
    echo "<script>toastr.error('Error: " . mysqli_error($conn) . "');</script>";
  }
}
$main_tax_id = isset($_GET['id']) ? (int)$_GET['id'] : 0; // Get main_projects_id from URL

// Pagination settings
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Fetch total number of records
$total_sql = "SELECT COUNT(*) as count FROM tax WHERE main_tax_id = '$main_tax_id'";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['count'];
$total_pages = ceil($total_records / $records_per_page);

// Modify the existing query to include pagination and filter by main_tax_id
$sql = "SELECT tax.id, tax.amount, tax.date, tax.full_name
      FROM tax 
      WHERE main_tax_id = '$main_tax_id' 
      ORDER BY tax.created_at DESC 
      LIMIT $offset, $records_per_page";
$result = mysqli_query($conn, $sql);
$taxs = mysqli_fetch_all($result, MYSQLI_ASSOC);


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
                <p>Are you sure you want to add this expense?</p>
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
                        <h5 class="card-title mb-0" style="font-size: 1.2rem; ">ADD TAX</h5>
                    </div>
                    <div class="card-body" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                        <form action="" method="post" onsubmit="return confirmSubmission()">
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Name</label>
                                <input type="text" class="form-control form-control" name="full_name" placeholder="Enter amount" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Amount (৳)</label>
                                <input type="number" class="form-control form-control" name="amount" placeholder="Enter amount" required>
                            </div>
                            
                             <div class="mb-3">
                                <label class="form-label" style="font-size: 1rem; font-weight: bold;">Date</label>
                                <input type="date" class="form-control form-control" name="date" required>
                            </div>


                           


                        

                            <button type="submit" class="btn btn-primary ">
                                <i class="align-middle" data-feather="plus"></i>
                                Add Expense
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Display the data in a table -->

            </div>
        </div>
        <div class=" mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0" style="font-size: 1.5rem; ">Tax History</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Date</th>
                            <th>Amount (৳)</th>
                          
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($taxs as $tax): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($tax['full_name']); ?></td>
                                <td><?php echo htmlspecialchars(date('Y-m-d', strtotime($tax['date']))); ?></td>
                                <td><?php echo htmlspecialchars(number_format($tax['amount'], 0)); ?></td>
                               
                                <td>
                                    <a href="javascript:void(0);" onclick="loadEditForm(<?php echo htmlspecialchars($tax['id']); ?>);" class="btn btn-sm btn-warning">Edit</a>
                                    <a href="javascript:void(0);" onclick="openDeleteModal('delete_tax.php?table=tax&id=<?php echo htmlspecialchars($tax['id']); ?>');" class="btn btn-sm btn-danger">Delete</a>
                                    
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Display total amount -->
                <div class="mt-3">
                    <strong>Total Amount: </strong>
                    <span><?php echo htmlspecialchars(number_format(array_sum(array_column($taxs, 'amount')), 0)); ?> ৳</span>
                </div>
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
                <!-- Content will be loaded here from " .php" -->
            </div>
        </div>
    </div>
</div>

<script>
    // Function to load edit form into the modal
    function loadEditForm(id) {
        $.ajax({
            url: 'edit_tax.php',
            type: 'GET',
            data: { id: id, table: 'tax' },
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
        // Set the delete URL
        deleteUrl = url; 
        $('#deleteConfirmModal').modal('show'); // Show the confirmation modal
    }

    // Handle the confirmation within the modal
    document.getElementById('confirmDelete').addEventListener('click', function() {
        $('#deleteConfirmModal').modal('hide'); // Hide the modal

        // AJAX call to delete the record
        $.ajax({
            url: deleteUrl,
            type: 'GET', // or 'POST' depending on your delete method
            success: function(response) {
                // Handle success (e.g., show success message, refresh data)
                toastr.success('Record deleted successfully!');
                location.reload(); // Refresh the page to see the updated data
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                toastr.error('Error deleting record.');
            }
        });
    });
</script>


    </div>

  </div>
</main>
<?php include('footer.php') ?>