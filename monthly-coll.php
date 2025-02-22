<?php ob_start(); ?>
<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<style>
    .amount {
        color: red; /* Change to your desired color */
    }
</style>

<main class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="form-group col-md-12">
        <div class="container-fluid m-0">

          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id']; // f_flatId
            $new_paid_amount = isset($_POST['paid_amount']) ? floatval($_POST['paid_amount']) : 0;
            
            // Update query modified to include month and year checks
          $month = isset($_GET['month']) ? $_GET['month'] : date('F', strtotime('-1 month')); // Get the previous month
          $year = isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('-1 month')); // Get the year of the previous month
        
            // Update f_paid_amount and f_due in one query
            $updateQuery = "UPDATE payments
                            SET f_paid_amount = f_paid_amount + ?, 
                                f_due = (total_amount - (f_paid_amount + ?)) 
                            WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("ddiss", $new_paid_amount, $new_paid_amount, $id, $month, $year);
            $stmt->execute();
        
            // Update f_due separately if needed
            $updateDueQuery = "UPDATE payments
                               SET f_due = (total_amount - f_paid_amount)
                               WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
            $stmtDue = $conn->prepare($updateDueQuery);
            $stmtDue->bind_param("iss", $id, $month, $year);
            $stmtDue->execute();
        
            $stmt->close();
            $stmtDue->close();
        
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
          //sendGSMS('8809617620596',$mobileNumber,$msg,'C200022562c68264972b36.87730554','text&contacts');
          ?>

          <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success">Status updated successfully!</div>
          <?php endif; ?>

          <?php

          // Ensure month and year are set from GET parameters
          $month = isset($_GET['month']) ? $_GET['month'] : date('F', strtotime('-1 month')); // Get the previous month
          $year = isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('-1 month')); // Get the year of the previous month
          // Fetch all billing records grouped by f_flatId
          $fetchQuery = "SELECT flat_bill.f_flatId, owner_name, flatname, flat_bill.f_month, flat_bill.f_year, 
                                p.f_paid_amount, p.f_due, 
                                GROUP_CONCAT(f_flat_rent SEPARATOR ', ') AS flat_rents,
                                GROUP_CONCAT(f_c_current_bill SEPARATOR ', ') AS current_bills,
                                GROUP_CONCAT(f_guard_slry SEPARATOR ', ') AS guard_salaries,
                                GROUP_CONCAT(f_c_center_various SEPARATOR ', ') AS other_expenses,
                                GROUP_CONCAT(f_empty_flat SEPARATOR ', ') AS empty_flats,
                                GROUP_CONCAT(f_date SEPARATOR ', ') AS dates,
                                f_status,
                                total_amount
                          FROM flat_bill 
                          LEFT JOIN flats ON flats.id = flat_bill.f_flatId 
                          LEFT JOIN payments p ON p.f_flatId = flat_bill.f_flatId AND p.f_month = flat_bill.f_month AND p.f_year = flat_bill.f_year
                          WHERE flat_bill.f_month = '$month' AND flat_bill.f_year = '$year'
                          GROUP BY flat_bill.f_flatId, owner_name, flatname, flat_bill.f_month, flat_bill.f_year, f_status";

          $result = mysqli_query($conn, $fetchQuery);

        

          echo "
                    
                 <div class='row mb-3 d-flex justify-content-center align-items-center'>
                 <h2 calss='text-center mr-2'>BILL RECORDS</h2>
                            <div class='ml-2 p-1'>
                              <form  method='GET'>
                                <select class='p-1' name='month' required>
                                  <option value=''>Select Month</option>
                                  <option value='January'>January</option>
                                  <option value='February'>February</option>
                                  <option value='March'>March</option>
                                  <option value='April'>April</option>
                                  <option value='May'>May</option>
                                  <option value='June'>June</option>
                                  <option value='July'>July</option>
                                  <option value='August'>August</option>
                                  <option value='September'>September</option>
                                  <option value='October'>October</option>
                                  <option value='November'>November</option>
                                  <option value='December'>December</option>
                                </select>
                                <select class='p-1' name='year' required>
                                  <option value=''>Select Year</option>
                                  <option value='2025'>2025</option>
                                  <option value='2024'>2024</option>
                                  <option value='2023'>2023</option>
                                  <option value='2022'>2022</option>
                                  <option value='2021'>2021</option>
                                  <!-- Add more years as needed -->
                                </select>
                                <button type='submit' class='btn btn-primary p-1 px-3 pb-1 mb-1'>Filter</button>
                              </form>
                            </div>
                          </div>";
          // {{ edit_1 }} end
          echo "<table class='table table-bordered table-hover'>";
          echo "<thead class='thead-dark'>
                  <tr>
                      <th>Manager</th>
                      <th>Building</th>
                      <th>Month</th>
                      <th>Year</th>
                      <th>Flat Rent</th>
                      <th>Current Bill</th>
                      <th>Guard Salary</th>
                      <th>Other Expense</th>
                      <th>Empty Flat </th>
                      <th>Total</th>
                      <th>Paid</th>
                      <th>Due</th>
                      <th>Status</th>
                      
                      <th class='text-center'>Action</th>
                  </tr>
                </thead>";
          echo "<tbody>";

          while ($row = mysqli_fetch_assoc($result)) {
            // Calculate individual amounts
            $flat_rents = explode(', ', $row['flat_rents']);
            $current_bills = explode(', ', $row['current_bills']);
            $guard_salaries = explode(', ', $row['guard_salaries']);
            $other_expenses = explode(', ', $row['other_expenses']);
            $empty_flats = explode(', ', $row['empty_flats']);
            $dates = explode(', ', $row['dates']);
            
            // Calculate total amounts
            $total_flat_rent = array_sum($flat_rents);
            $total_current_bill = array_sum($current_bills);
            $total_guard_salary = array_sum($guard_salaries);
            $total_other_expense = array_sum($other_expenses);
            $total_empty_flat = array_sum($empty_flats);
            
            // Calculate the final total as Flat Rent minus the sum of other expenses
            $final_total = $total_flat_rent - ($total_current_bill + $total_guard_salary + $total_other_expense);
            // print_r($final_total);
            
           // Update status based on final_total and f_paid_amount
    if ($final_total == $row['f_paid_amount']) {
      $f_status = 'Received'; // Changed to plain text
    } else {
        $f_status = 'Pending'; // Changed to plain text
  }
            echo "<tr>
                  <td>" . htmlspecialchars($row['owner_name']) . "</td>
                  <td>" . htmlspecialchars($row['flatname']) . "</td>
                  <td>" . htmlspecialchars($row['f_month']) . "</td>
                  <td>" . htmlspecialchars($row['f_year']) . "</td>
                  <td class='amount'>";

                  
            
            // Display Flat Rent with corresponding dates
            foreach ($flat_rents as $index => $rent) {
              echo "৳<span style='color: red; margin-right: 20px;'>" . number_format($rent, 0) . "</span> (Date: " . htmlspecialchars($dates[$index]) . ")<br>";
          }
          
          echo "</td>
                <td>"; 
            
            // Display Current Bill with corresponding dates
            foreach ($current_bills as $index => $bill) {
                echo "৳" . number_format($bill, 0) . " <br>";
            }
            
            echo "</td>
                  <td class='amount'>";
            
            // Display Guard Salary with corresponding dates
            foreach ($guard_salaries as $index => $salary) {
                echo "৳" . number_format($salary, 0) . " <br>";
            }
            
            echo "</td>
                  <td class='amount'>";
            
            // Display Other Expense with corresponding dates
            foreach ($other_expenses as $index => $expense) {
                echo "৳" . number_format($expense, 0) . " <br>";
            }

            echo "</td>
            <td class='amount'>";
      
      // Display Other Expense with corresponding dates
      foreach ($empty_flats as $index => $flat) {
          echo "৳" . number_format($flat, 0) . " <br>";
      }
      
            
            echo "</td>
                  <td>৳" . number_format($final_total, 0) . "</td>
                  <td>৳" . number_format($row['f_paid_amount'], 0) . "</td>
                 <td>৳" . number_format($row['total_amount'] - $row['f_paid_amount'], 0) . "</td>
                  <td>" . htmlspecialchars($f_status) . "</td>
                 
                  <td>
                      <form method='POST' action=''>
                          <input type='hidden' name='id' value='" . $row['f_flatId'] . "'>
                          <input type='number' name='paid_amount' placeholder='Enter amount' required>
                          <button type='submit' class='btn btn-success'>Received</button>
                         <a href='delete_reports.php?id=" . $row['f_flatId'] . "&month=" . $month . "&year=" . $year . "' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete all records for Flat ID " . $row['f_flatId'] . " in " . $month . " " . $year . "?\");'>Delete</a>
                      </form>
                  </td>
                  </tr>";
          }

          echo "</tbody></table>";

          $conn->close();
          ?>
        </div>

        <!-- Include Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
      </div>
    </div>
  </div>
</main>
<?php include('footer.php'); ?>