<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<?php
// Set the default month and year to the current month and year
$currentMonth = date('F'); // e.g., December
$currentYear = date('Y'); // e.g., 2024

// Get selected month and year from the filter form
$selectedMonth = $_GET['month'] ?? $currentMonth;
$selectedYear = $_GET['year'] ?? $currentYear;
?>

<main class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="form-group col-md-12">
        <div class="container-fluid m-0">
          <h2>Total Due Amounts by Flat</h2>

          <!-- Filter Form -->
          <form method="GET" class="mb-4 d-flex align-items-center gap-3">
            <div class="mr-3">
            
              <select name="month" id="month" class="form-select form-control">
                <?php
                // Generate month options
                $months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
                foreach ($months as $month) {
                  $selected = ($month === $selectedMonth) ? 'selected' : '';
                  echo "<option value='$month' $selected>$month</option>";
                }
                ?>
              </select>
            </div>

            <div class="mr-3">
            
              <select name="year" id="year" class="form-select form-control">
                <?php
                // Generate year options from 2020 to the current year + 5
                for ($year = 2020; $year <= date('Y') + 5; $year++) {
                  $selected = ($year == $selectedYear) ? 'selected' : '';
                  echo "<option value='$year' $selected>$year</option>";
                }
                ?>
              </select>
            </div>

            <div class="d-flex align-items-end">
              <button type="submit" class="btn btn-primary">Filter</button>
            </div>
          </form>


          <!-- Table Displaying Dues -->
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Flat Name</th>
                <th>Month</th>
                <th>Mobile Number</th>
                <th>Total Due</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Query to get total dues for the selected month and year
              $query = "
                SELECT f.flatname, f.mobile_number, p.f_month, SUM(p.f_due) AS total_due
                FROM flats f
                JOIN payments p ON f.id = p.f_flatId
                WHERE p.f_month = ? AND p.f_year = ?
                GROUP BY f.id
              ";

              // Prepare and execute the query
              $stmt = $conn->prepare($query);
              $stmt->bind_param('ss', $selectedMonth, $selectedYear);
              $stmt->execute();
              $result = $stmt->get_result();

              // Display the results in the table
              while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['flatname']}</td>
                        <td>{$row['f_month']}</td>
                        <td>{$row['mobile_number']}</td>
                        <td>{$row['total_due']}</td>
                      </tr>";
              }

              $stmt->close();
              ?>
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
</main>

<?php include('footer.php'); ?>