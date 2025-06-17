<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<?php
  // 1) Determine the first day of the previous month
  $prevDate      = strtotime("first day of previous month");
  $previousMonth = strtolower(date('F',  $prevDate));  // e.g. "april"
  $previousYear  = date('Y',    $prevDate);            // e.g. "2024" or "2023"

  // 2) Fetch all flatIds that already have a bill for that previous month
  $sqlBilled = "
    SELECT DISTINCT f_flatId
      FROM flat_bill
     WHERE f_month = '{$previousMonth}'
       AND f_year  = '{$previousYear}'
  ";
  $resBilled = mysqli_query($conn, $sqlBilled);
  $billedIds = [];
  while ($row = mysqli_fetch_assoc($resBilled)) {
    $billedIds[] = (int)$row['f_flatId'];
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

        <div class="card-container">
          <?php

          $sqlSelect = "
            SELECT id, owner_name, mobile_number, optional_number, flatname 
              FROM flats
          ";
          $result = mysqli_query($conn, $sqlSelect);

          $flat = mysqli_fetch_assoc($result);
          if ($flat) {
            $ownerName = htmlspecialchars($flat['owner_name']);
            $flatName = htmlspecialchars($flat['flatname']);
            // $flatNumber = htmlspecialchars($flat['flat_number']);
            $mobileNumber = htmlspecialchars($flat['mobile_number']);
            $optionalNumber = htmlspecialchars($flat['optional_number']);
            // $rent = htmlspecialchars($flat['rent']);
            // $advance = htmlspecialchars($flat['advance']);
          } else {
            // Handle the case where the flat is not found
            $ownerName = 'Not Found';
            $flatName = 'Not Found';
        
            $mobileNumber = 'Not Found';
            $optionalNumber = 'Not Found';
         
            
          }


          while ($data = mysqli_fetch_assoc($result)):

            $flatId   = (int)$data['id'];
            // If this flatId was billed last month → green; otherwise → blue
            $btnClass = in_array($flatId, $billedIds)
                        ? 'btn-success'
                        : 'btn-info';

          ?>

            <div class="card">
              <div class="card-body p-4 shadow-lg rounded" style="background-color: #f8f9fa;">
                <h5 class="card-title">
                  <span class="fw-bold" style="color: #3498db;">Name:</span>
                  <span class="text-muted"><?php echo $data["owner_name"]; ?></span>
                </h5>
                <hr>
                <p class="card-text">
                  <span class="fw-bold" style="color: #e74c3c;">Building Name:</span>
                  <span class="text-muted"><?php echo $data["flatname"]; ?></span>
                </p>


                <p class="card-text">
                  <span class="fw-bold" style="color: #2ecc71;">Mobile Number:</span>
                  <span class="text-muted"><?php echo $data["mobile_number"]; ?></span>
                </p>
               
                <p class="card-text">
                  <span class="fw-bold" style="color: #2ecc71;">Optional Number:</span>
                  <span class="text-muted"><?php echo $data["optional_number"]; ?></span>
                </p>
                

                <!-- edit and delete button  -->
                <a href="edit_manager.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <a href="delete_manager.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fas fa-trash-alt"></i></a>
                <a href="add_flat.php?id=<?php echo $data['id']; ?>" class="btn btn-warning btn-sm"><i class="fas fa-building"></i></a>
                <a href="flat-details.php?id=<?php echo $flatId; ?>"
                   class="btn <?php echo $btnClass; ?> btn-sm">
                  Add Bill
                </a>

              </div>
            </div>

          <?php endwhile; ?>
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