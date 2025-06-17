<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<main class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-5 d-flex justify-content-center">

        <?php
        // Step 1: Get building_id from URL
        if (isset($_GET['id'])) {
            $building_id = $_GET['id'];
        } else {
            echo "<p style='color: red;'>Building ID not found in URL.</p>";
            exit;
        }

        // Step 2: Handle form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['name'];
            $amount = $_POST['amount'];
            $date = $_POST['date'];
            $note = $_POST['note'];

            // Insert into database
            $sql = "INSERT INTO flat_details (building_id, name, amount, date, note)
                    VALUES ('$building_id', '$name', '$amount', '$date', '$note')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Data inserted successfully!');</script>";
            } else {
                echo "<p style='color: red;'>Error: " . mysqli_error($conn) . "</p>";
            }
        }
        ?>

        <!-- Step 3: Create the form -->
             <div class="card shadow p-4" style="width: 100%; max-width: 500px; border-radius: 10px; background-color: #f8f9fa; height: fit-content;">
                        <h4 class="mb-4 text-center">Add Payment</h4>

                        <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Note</label>
                            <textarea name="note" class="form-control" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                        </form>
             </div>

      </div>
      <div class="col-md-6">
    <h4 class="mb-3">Previous Entries</h4>

    <?php


        // Get building_id from URL
        $building_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        // Pagination settings
        $limit = 10; // Records per page
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
        $offset = ($page - 1) * $limit;

        // Count total records
        $totalQuery = "SELECT COUNT(*) AS total FROM flat_details WHERE building_id = '$building_id'";
        $totalResult = mysqli_query($conn, $totalQuery);
        $totalRow = mysqli_fetch_assoc($totalResult);
        $totalRecords = $totalRow['total'];
        $totalPages = ceil($totalRecords / $limit);

        // Fetch paginated records
        $query = "SELECT * FROM flat_details WHERE building_id = '$building_id' ORDER BY id DESC LIMIT $limit OFFSET $offset";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            echo '<div class="table-responsive">';
            echo '<table class="table table-bordered table-striped">';
            echo '<thead class="table-dark">';
            echo '<tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Note</th>
                    <th>Action</th>
                  </tr>';
            echo '</thead>';
            echo '<tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['id'] . '</td>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['amount']) . '</td>';
                echo '<td>' . htmlspecialchars($row['date']) . '</td>';
                echo '<td>' . nl2br(htmlspecialchars($row['note'])) . '</td>';
                echo '<td>
                        <a href="edit_flat_details.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning me-1">Edit</a>
                        <a href="delete_flat_details.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this entry?\');">Delete</a>
                      </td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';

            // Pagination
            if ($totalPages > 1) {
                echo '<nav><ul class="pagination justify-content-end">';
                if ($page > 1) {
                    echo '<li class="page-item"><a class="page-link" href="?id=' . $building_id . '&page=' . ($page - 1) . '">Previous</a></li>';
                }

                for ($i = 1; $i <= $totalPages; $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="?id=' . $building_id . '&page=' . $i . '">' . $i . '</a></li>';
                }

                if ($page < $totalPages) {
                    echo '<li class="page-item"><a class="page-link" href="?id=' . $building_id . '&page=' . ($page + 1) . '">Next</a></li>';
                }

                echo '</ul></nav>';
            }

        } else {
            echo '<p>No entries found for this building.</p>';
        }
    ?>
</div>


      </div>
  </div>
</main>

<?php include('footer.php'); ?>
