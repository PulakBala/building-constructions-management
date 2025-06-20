<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

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
          // Fetch data from the database
          $sqlSelect = "SELECT id, name, address, manager_name, manager_number, guard_name, guard_number, created_at FROM building_info"; // Table name should match your database table
          $result = mysqli_query($conn, $sqlSelect);

          if (mysqli_num_rows($result) > 0) {
            // Loop through each row of the result set
            while ($data = mysqli_fetch_assoc($result)) {
          ?>
              <div class="col-md-4 mb-4">
                <div class="card">
                  <div class="card-body p-4 shadow-lg rounded" style="background-color: #f8f9fa;">
                    <h5 class="card-title">
                      <span class="fw-bold" style="color: #3498db;">Building Name:</span>
                      <span class="text-muted"><?php echo htmlspecialchars($data["name"]); ?></span>
                    </h5>
                    <hr>
                    <p class="card-text">
                      <span class="fw-bold" style="color: #2ecc71;">Address:</span>
                      <span class="text-muted"><?php echo htmlspecialchars($data["address"]); ?></span>
                    </p>

                    <p class="card-text">
                      <span class="fw-bold" style="color: #e74c3c;">Manager Name:</span>
                      <span class="text-muted"><?php echo htmlspecialchars($data["manager_name"]); ?></span>
                    </p>

                    <p class="card-text">
                      <span class="fw-bold" style="color: #e74c3c;">Manager Number:</span>
                      <span class="text-muted"><?php echo htmlspecialchars($data["manager_number"]); ?></span>
                    </p>

                    <p class="card-text">
                      <span class="fw-bold" style="color: #e67e22;">Guard Name:</span>
                      <span class="text-muted"><?php echo htmlspecialchars($data["guard_name"]); ?></span>
                    </p>

                    <p class="card-text">
                      <span class="fw-bold" style="color: #e67e22;">Guard Number:</span>
                      <span class="text-muted"><?php echo htmlspecialchars($data["guard_number"]); ?></span>
                    </p>

                    <p class="card-text">
                      <span class="fw-bold" style="color: #e67e22;">Total Amount:</span>
                      <span class="text-muted" id="totalAmount_<?php echo $data['id']; ?>">
                        <?php
                          // Fetch total amount for the building
                          $sqlTotal = "SELECT SUM(rent) as total_rent FROM flat_info WHERE bulding_name = ?";
                          $stmtTotal = $conn->prepare($sqlTotal);
                          $stmtTotal->bind_param("s", $data['name']);
                          $stmtTotal->execute();
                          $resultTotal = $stmtTotal->get_result();
                          $totalRow = $resultTotal->fetch_assoc();
                          echo htmlspecialchars(number_format($totalRow['total_rent'], 0));
                        ?>
                      </span>
                    </p>

                    <!-- Edit and Delete buttons -->
                    <a href="#" class="btn btn-warning btn-sm">Edit</a>
                     <a href="delete_new_building.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm" onclick='return confirm("Are you sure you want to delete this record?");'>Delete</a>
                    <a href="add_new_flat.php?name=<?php echo urlencode($data['name']); ?>" class="btn btn-danger btn-sm">Add Flat</a>
                    <a href="all_flat_information.php?name=<?php echo urldecode($data['name']); ?>" class="btn btn-danger btn-sm">Details</a>

                  </div>
                </div>
              </div>
          <?php
            }
          } else {
            echo "<p class='text-center text-muted'>No data found!</p>";
          }
          ?>
        </div>
      </section>
    </div>
  </div>
</main>

<?php include('footer.php'); ?>
