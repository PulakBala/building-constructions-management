<?php 
  include('connection.php');
  include('header.php');
  include('sidebar.php');

  if (isset($_GET['name'])) {
      $building_name = htmlspecialchars($_GET['name']); // Get the building name from the URL
  } else {
      $building_name = ''; // Default value if name is not set
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
              <input type="text" id="searchInput" class="form-control" placeholder="Search Flat Owner...">
              <div class="input-group-append">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Results will appear here -->
        <div id="searchResults" class="my-4"></div>

        <div class="">
          <?php
          // Fetch data based on the building_name
          $sqlSelect = "SELECT id, bulding_name, name, mobile_number, nid_number, nid_img, rent, advance, created_at 
                        FROM flat_info 
                        WHERE bulding_name = ?";
          $stmt = $conn->prepare($sqlSelect);
          $stmt->bind_param("s", $building_name);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
            $all_data = []; // Create an array to store all data
            while ($row = $result->fetch_assoc()) {
                $all_data[] = $row; // Store each row in the array
            
          ?>
                    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Rent</th>
                    <th>Advance</th>
                    <th>Mobile Number</th>
                    <th>NID Number</th>
                    <th>NID Image</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1; // To display serial numbers
                foreach ($all_data as $data) {
                ?>
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo htmlspecialchars($data["name"]); ?></td>
                        <td><?php echo htmlspecialchars($data["rent"]); ?></td>
                        <td><?php echo htmlspecialchars($data["advance"]); ?></td>
                        <td><?php echo htmlspecialchars($data["mobile_number"]); ?></td>
                        <td><?php echo htmlspecialchars($data["nid_number"]); ?></td>
                        <td>
                            <a href="<?php echo htmlspecialchars($data["nid_img"]); ?>" target="_blank">View Image</a>
                        </td>
                        <td><?php echo htmlspecialchars($data["created_at"]); ?></td>
                        <td>
                            <!-- Action buttons -->
                            <a href="edit-flat.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_flat_info.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="mt-3">
                      <strong>Total Amount: </strong>
                      <span><?php echo htmlspecialchars(number_format(array_sum(array_column($all_data, 'rent')), 0)); ?> ৳</span>
                  </div> 
    </div>
          <?php
              }
          } else {
              echo "<p class='text-center text-muted'>No data found for the selected building!</p>";
          }
          ?>
        </div>
      </section>
    </div>
  </div>
</main>

<?php include('footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('#searchInput').on('keyup', function() {
      var searchQuery = $(this).val();

      if (searchQuery !== '') {
        $.ajax({
          url: 'search_all_flat.php',
          method: 'POST',
          data: {
            query: searchQuery
          },
          success: function(response) {
            $('#searchResults').html(response);
          }
        });
      } else {
        $('#searchResults').html(''); // Clear the results when the search field is empty
      }
    });
  });
</script>
