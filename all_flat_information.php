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

          $sqlSelect = "SELECT * FROM flat_info";
          $result = mysqli_query($conn, $sqlSelect);

          $flat = mysqli_fetch_assoc($result);
          if ($flat) {
            // $ownerName = htmlspecialchars($flat['owner_name']);
            // $flatName = htmlspecialchars($flat['flatname']);
            // $flatNumber = htmlspecialchars($flat['flat_number']);
            $mobileNumber = htmlspecialchars($flat['mobile_number']);
            // $optionalNumber = htmlspecialchars($flat['optional_number']);
            $rent = htmlspecialchars($flat['rent']);
            $advance = htmlspecialchars($flat['advance']);
          } else {
            // Handle the case where the flat is not found
            // $ownerName = 'Not Found';
            // $flatName = 'Not Found';
            // $flatNumber = 'Not Found';
            $mobileNumber = 'Not Found';
            // $optionalNumber = 'Not Found';
            $rent = 'Not Found';
            $advance = 'Not Found';
          }


          while ($data = mysqli_fetch_array($result)) {
          ?>

            <div class="card">
              <div class="card-body p-4 shadow-lg rounded" style="background-color: #f8f9fa;">
                <h5 class="card-title">
                  <span class="fw-bold" style="color: #3498db;">Name:</span>
                  <span class="text-muted"><?php echo $data["name"]; ?></span>
                </h5>
                <hr>


                <p class="card-text">
                  <span class="fw-bold" style="color: #e74c3c;">Rent:</span>
                  <span class="text-muted"><?php echo $data["rent"]; ?></span>
                </p>

                <p class="card-text">
                  <span class="fw-bold" style="color: #e74c3c;">Advance:</span>
                  <span class="text-muted"><?php echo $data["advance"]; ?></span>
                </p>

                <p class="card-text">
                  <span class="fw-bold" style="color: #2ecc71;">Mobile Number:</span>
                  <span class="text-muted"><?php echo $data["mobile_number"]; ?></span>
                </p>


                <p class="card-text">
                  <span class="fw-bold" style="color: #e67e22;">NID Number:</span>
                  <span class="text-muted"><?php echo $data["nid_number"]; ?></span>
                </p>

                <p class="card-text">
                  <span class="fw-bold" style="color: #e67e22;">NID Image:</span>
                  <span class="text-muted"><a href="<?php echo $data["nid_img"]; ?>" target="_blank">View Image</a></span>
                </p>

                <p class="card-text">
                  <span class="fw-bold" style="color: #e67e22;">Date:</span>
                  <span class="text-muted"><?php echo $data["created_at"]; ?></span>
                </p>
                <!-- edit and delete button  -->
                <a href="edit-flat.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

                <a href="delete_flat_info.php?id=<?php echo $data['id']; ?>" class="btn btn-info btn-sm">Delete</a>

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