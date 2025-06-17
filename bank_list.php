<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<main class="page-content">
  <div class="container-fluid px-4">

    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card shadow-lg border-0 mt-4">
          <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">All Bank Account Records</h4>
          </div>

          <div class="card-body">

            <!-- Add custom CSS for table -->
            <style>
              .table {
                font-size: 0.9rem;
              }
              .table th, .table td {
                padding: 0.5rem;
                vertical-align: middle;
              }
              .table .btn-sm {
                padding: 0.2rem 0.5rem;
                font-size: 0.8rem;
              }
              .table-info {
                font-size: 0.9rem;
              }
              /* Add styles for fixed header and scrollable content */
              .fixed-header {
                position: sticky;
                top: 0;
                z-index: 1;
                background: white;
                padding-bottom: 1rem;
              }
              .scrollable-table {
                max-height: calc(100vh - 300px);
                overflow-y: auto;
              }
              .scrollable-table thead th {
                position: sticky;
                top: 0;
                background: #212529;
                z-index: 2;
              }
            </style>

            <!-- Add Filter Section with fixed-header class -->
            <div class="row mb-4 fixed-header">
              <div class="col-md-12">
                <form method="GET" class="row g-3">
                  <div class="col-md-4">
                    <input type="text" name="search" class="form-control" placeholder="Search by Bank Name, Account Holder or Branch..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                  </div>
                  <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <?php if(isset($_GET['search'])): ?>
                      <a href="bank_list.php" class="btn btn-secondary">Clear</a>
                    <?php endif; ?>
                  </div>
                  <div class="col-md-6 text-end">
                    <div class="d-inline-block bg-info text-white p-2 rounded">
                      <strong>Total Amount: </strong>
                      <?php
                      $total_query = "SELECT SUM(amount) as total FROM bank_accounts";
                      if (!empty($where_conditions)) {
                          $total_query .= " WHERE " . implode(" AND ", $where_conditions);
                      }
                      $total_stmt = mysqli_prepare($conn, $total_query);
                      if (!empty($params)) {
                          mysqli_stmt_bind_param($total_stmt, $types, ...$params);
                      }
                      mysqli_stmt_execute($total_stmt);
                      $total_result = mysqli_stmt_get_result($total_stmt);
                      $total_row = mysqli_fetch_assoc($total_result);
                      echo number_format($total_row['total'], 2) . ' ৳';
                      ?>
                    </div>
                  </div>
                </form>
              </div>
            </div>

<?php
// Modify the query to include filters
$where_conditions = array();
$params = array();
$types = "";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where_conditions[] = "(bank_name LIKE ? OR account_name LIKE ? OR branch LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

$query = "SELECT id, bank_name, account_number, branch, account_name, contact, signature_path, amount, date FROM bank_accounts";

if (!empty($where_conditions)) {
    $query .= " WHERE " . implode(" AND ", $where_conditions);
}

$query .= " ORDER BY date DESC";

$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="scrollable-table">';
            echo '<table class="table table-bordered table-hover align-middle">';
            echo '<thead class="table-dark text-center">';
            echo '<tr>
                    <th>#</th>
                    <th>Bank Name</th>
                    <th>Account Holder</th>
                    <th>Account No.</th>
                    <th>Branch</th>
                    <th>Contact</th>
                    <th>Signature</th>
                    <th>Amount (৳)</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>';
            echo '</thead>';
            echo '<tbody>';

            $total_amount = 0; // Initialize total amount variable

            while ($row = mysqli_fetch_assoc($result)) {
                $total_amount += $row['amount']; // Add to total
                echo '<tr>';
                echo '<td class="text-center">' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['bank_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['account_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['account_number']) . '</td>';
                echo '<td>' . htmlspecialchars($row['branch']) . '</td>';
                echo '<td>' . htmlspecialchars($row['contact']) . '</td>';

                echo '<td class="text-center">';
                if (!empty($row['signature_path']) && file_exists($row['signature_path'])) {
                   
                    echo '<button class="btn btn-sm btn-info ms-2" onclick="viewSignature(\'' . $row['signature_path'] . '\')">View</button>';
                } else {
                    echo '<span class="text-muted">No image</span>';
                }
                echo '</td>';

                echo '<td class="text-end">' . number_format($row['amount'], 2) . '</td>';
                echo '<td class="text-center">' . $row['date'] . '</td>';

                // Action Buttons
                echo '<td class="text-center">';
                echo '<a href="edit_bank.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning me-1">Edit</a>';
                echo '<a href="delete_bank.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete</a>';
                echo '</td>';

                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-info">No bank account records found.</div>';
        }
        ?>

          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<!-- Add Signature View Modal -->
<div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="signatureModalLabel">Signature View</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="signatureImage" src="" alt="Signature" style="max-width: 100%; height: auto;">
      </div>
    </div>
  </div>
</div>

<script>
function viewSignature(imagePath) {
    document.getElementById('signatureImage').src = imagePath;
    var modal = new bootstrap.Modal(document.getElementById('signatureModal'));
    modal.show();
}
</script>

<?php include('footer.php') ?>
