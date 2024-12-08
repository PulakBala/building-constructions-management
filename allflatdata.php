<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>


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

        <div class="card-container">
          <?php

          $sqlSelect = "SELECT * FROM flats";
          $result = mysqli_query($conn, $sqlSelect);

          $flat = mysqli_fetch_assoc($result);
          if ($flat) {
            $ownerName = htmlspecialchars($flat['owner_name']);
            $mobileNumber = htmlspecialchars($flat['mobile_number']);
            $optionalNumber = htmlspecialchars($flat['optional_number']);
          } else {
            // Handle the case where the flat is not found
            $ownerName = 'Not Found';
            $mobileNumber = 'Not Found';
          }


          while ($data = mysqli_fetch_array($result)) {
          ?>

            <div class="card">
              <div class="card-body p-4 shadow-lg rounded" style="background-color: #f8f9fa;">
                <h5 class="card-title">
                  <span class="fw-bold" style="color: #3498db;">Name:</span>
                  <span class="text-muted"><?php echo $data["owner_name"]; ?></span>
                </h5>
                <hr>
                <p class="card-text">
                  <span class="fw-bold" style="color: #2ecc71;">Mobile Number:</span>
                  <span class="text-muted"><?php echo $data["mobile_number"]; ?></span>
                </p>
                <p class="card-text">
                  <span class="fw-bold" style="color: #2ecc71;">Optional Number:</span>
                  <span class="text-muted"><?php echo $data["optional_number"]; ?></span>
                </p>
                <p class="card-text">
                  <span class="fw-bold" style="color: #e74c3c;">Flat Name:</span>
                  <span class="text-muted"><?php echo $data["flatname"]; ?></span>
                </p>
                <p class="card-text">
                  <span class="fw-bold" style="color: #e74c3c;">Flat No:</span>
                  <span class="text-muted"><?php echo $data["flat_number"]; ?></span>
                </p>
                <!-- edit and delete button  -->
                <a href="edit-flat.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="flat-details.php?id=<?php echo $data["id"]; ?>" class="btn btn-info btn-sm">Add Bill</a>

              </div>
            </div>

          <?php
          }
          ?>
        </div>
      </section>
    </div>

  </div>
</main>
<?php include('footer.php') ?>

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