<?php ob_start(); ?>
<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<style>
  .amount {
    color: red;
    /* Change to your desired color */
  }
  tr.marked td{
    background-color: #00cf30 !important; /* Light green background */
    transition: background-color 0.3s ease-in-out;

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
            $due_note = isset($_POST['due_note']) ? $_POST['due_note'] : ''; // Get the Flat Due Note

            // Update query modified to include month and year checks
            $month = isset($_GET['month']) ? $_GET['month'] : date('F', strtotime('-1 month')); // Get the previous month
            $year = isset($_GET['year']) ? $_GET['year'] : date('Y', strtotime('-1 month')); // Get the year of the previous month

            // Check the due amount for the provided due note
            $checkDueAmountQuery = "SELECT f_due_flat FROM flat_bill WHERE f_flatId = ? AND f_month = ? AND f_year = ? AND f_due_note = ?";
            $stmtCheck = $conn->prepare($checkDueAmountQuery);
            $stmtCheck->bind_param("isss", $id, $month, $year, $due_note);
            $stmtCheck->execute();
            $stmtCheck->bind_result($due_flat_amount);
            $stmtCheck->fetch();
            $stmtCheck->close();

            if ($due_flat_amount === null) {
              echo "<script>alert('Error: The specified Flat Due Note does not exist.');</script>";
            } elseif ($new_paid_amount > $due_flat_amount) {
              echo "<script>alert('Error: The payment amount exceeds the due amount for this note.');</script>";
            } else {
              // Update f_paid_amount in payments table
              $updateQuery = "UPDATE payments
                            SET f_paid_amount = f_paid_amount + ?, 
                                f_due = (total_amount - (f_paid_amount + ?)) 
                            WHERE f_flatId = ? AND f_month = ? AND f_year = ?";
              $stmt = $conn->prepare($updateQuery);
              $stmt->bind_param("ddiss", $new_paid_amount, $new_paid_amount, $id, $month, $year);
              $stmt->execute();

              // Remove the corresponding amount from flat_bill table
              $removeDueFlatQuery = "UPDATE flat_bill
                                    SET f_due_flat = f_due_flat - ?
                                    WHERE f_flatId = ? AND f_month = ? AND f_year = ? AND f_due_note = ?";
              $stmtRemove = $conn->prepare($removeDueFlatQuery);
              $stmtRemove->bind_param("issss", $new_paid_amount, $id, $month, $year, $due_note);
              $stmtRemove->execute();

              $stmt->close();
              $stmtRemove->close();

              header('Location: ' . $_SERVER['PHP_SELF']);
              exit();
            }
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
                                SUM(DISTINCT p.f_paid_amount) AS f_paid_amount,
                                SUM(p.f_due) AS f_due, 
                                GROUP_CONCAT(DISTINCT CONCAT(f_flat_rent, ' (Date: ', f_date, ')') SEPARATOR ', ') AS flat_rents,
                                GROUP_CONCAT(DISTINCT f_date SEPARATOR ', ') AS dates,
                               GROUP_CONCAT(DISTINCT CONCAT(f_c_current_bill, ' (Date: ', f_date, ')') ORDER BY f_date DESC SEPARATOR ', ') AS current_bills,
GROUP_CONCAT(DISTINCT CONCAT(f_guard_slry, ' (Date: ', f_date, ')') ORDER BY f_date DESC SEPARATOR ', ') AS guard_salaries,
GROUP_CONCAT(DISTINCT CONCAT(f_c_center_various, '(Date: ', f_date, ')') ORDER BY f_date DESC SEPARATOR ', ') AS other_expenses,
GROUP_CONCAT(DISTINCT CONCAT(f_empty_flat, '(Date: ', f_date, ')') ORDER BY f_date DESC SEPARATOR ', ') AS empty_flats,
GROUP_CONCAT(DISTINCT CONCAT(f_due_note, '(Date: ', f_date, ')') ORDER BY f_date DESC SEPARATOR ', ') AS due_notes,
GROUP_CONCAT(DISTINCT CONCAT(f_due_flat, '(Date: ', f_date, ')') ORDER BY f_date DESC SEPARATOR ', ') AS due_flats,
                                f_status,
                                total_amount,
                                SUM(fi.rent) AS total_collected,
                                flat_bill.marked
                          FROM flat_bill 
                          LEFT JOIN flats ON flats.id = flat_bill.f_flatId 
                          LEFT JOIN payments p ON p.f_flatId = flat_bill.f_flatId AND p.f_month = flat_bill.f_month AND p.f_year = flat_bill.f_year
                          LEFT JOIN flat_info fi ON fi.bulding_name = owner_name 
                          WHERE flat_bill.f_month = '$month' AND flat_bill.f_year = '$year'
                          GROUP BY flat_bill.f_flatId, owner_name, flatname, flat_bill.f_month, flat_bill.f_year, f_status
                          ORDER BY MAX(f_date) DESC";

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
                      
                      <th>Total Collected</th>
                      <th>Total</th>
                      <th>Paid</th>
                            <th>Flat Due Note</th>
            <th>Flat Due Amount</th>
                      <th>Status</th>
                      
                      <th class='text-center'>Action</th>
                  </tr>
                </thead>";
          echo "<tbody>";

          
          while ($row = mysqli_fetch_assoc($result)) {
            $all_due_flat_zero = true; // Initialize the flag to true
            $rowClass = $row['marked'] ? 'marked' : '';
            // Calculate individual amounts
            $flat_rents = explode(', ', $row['flat_rents']);
            $current_bills = explode(', ', $row['current_bills']);
            $guard_salaries = explode(', ', $row['guard_salaries']);
            $other_expenses = explode(', ', $row['other_expenses']);
            $empty_flats = explode(', ', $row['empty_flats']);
            // Display Due Notes
            $due_notes = explode(', ', $row['due_notes']);
            $due_flats = explode(', ', $row['due_flats']); // Ensure this is defined


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

            // Add the f_paid_amount to the final_total
            $final_total += $row['f_paid_amount']; // Add the paid amount to the final total


            // Check if any of the due_flats amounts are not zero
            foreach ($due_flats as $due) {
                if (preg_match('/\d+/', $due, $matches)) {
                    $numeric_due = (float)$matches[0];
                    if ($numeric_due != 0) {
                        $all_due_flat_zero = false; // Set the flag to false if any amount is not zero
                        break; // Exit the loop early if a non-zero amount is found
                    }
                }
            }

            // Update status based on all_due_flat_zero flag
            if ($all_due_flat_zero) {
                $f_status = 'Received'; // All due amounts are zero
            } else {
                $f_status = 'Pending'; // At least one due amount is not zero
            }

            




            echo "<tr id='row_" . htmlspecialchars($row['f_flatId']) . "_" . strtolower(htmlspecialchars($row['f_month'])) . "_" . htmlspecialchars($row['f_year']) . "' class='" . $rowClass . "'>";
            echo "<td>" . htmlspecialchars($row['owner_name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['flatname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['f_month']) . "</td>";
            echo "<td>" . htmlspecialchars($row['f_year']) . "</td>";
            echo "<td class='amount'>";


      // Display Flat Rent with corresponding dates
foreach ($flat_rents as $index => $rent) {
  // Extract the numeric part from the string
  if (preg_match('/\d+/', $rent, $matches)) {
      $numeric_rent = (float)$matches[0];
      echo "৳<span style='color: red; margin-right: 20px;'>" . number_format($numeric_rent, 0) . "</span> (Date: " . htmlspecialchars($dates[$index]) . ")<br>";
  }
}

echo "</td>";

            echo "</td>
                <td>";

            // Display Current Bill with corresponding dates
            foreach ($current_bills as $index => $bill) {
              // Extract the numeric part from the string
              if (preg_match('/\d+/', $bill, $matches)) {
                  $numeric_bill = (float)$matches[0];
                  echo "৳" . number_format($numeric_bill, 0) . " <br>";
              }
          }

            echo "</td>
                  <td class='amount'>";

            // Display Guard Salary with corresponding dates
            foreach ($guard_salaries as $salary) {
              // Extract the numeric part and date from the string
              if (preg_match('/\d+/', $salary, $matches)) {
                $numeric_salary = (float)$matches[0]; // Change to $matches[0]
                echo "৳" . number_format($numeric_salary, 0) . " <br>";
              }
          }
          
            echo "</td>
                  <td class='amount'>";

            // Display Other Expense with corresponding dates
            foreach ($other_expenses as $index => $expense) {
              if (preg_match('/\d+/', $salary, $matches)) {
                $numeric_expense = (float)$matches[0]; // Change to $matches[0]
                echo "৳" . number_format($numeric_expense, 0) . " <br>";
              }
             
            }

            echo "</td>
            <td class='amount'>";

            // Display Other Expense with corresponding dates
            foreach ($empty_flats as $index => $flat) {
              if (preg_match('/\d+/', $flat, $matches)) {
                $numeric_flat = (float)$matches[0]; // Change to $matches[0]
                echo "৳" . number_format($numeric_flat, 0) . " <br>";
              }
             
            }
            echo "</td>";




            echo "<td>৳" . number_format($row['total_collected'], 0) . "</td>";

            echo "<td>৳" . number_format($final_total, 0) . "</td>";
            echo "<td>৳" . number_format($row['f_paid_amount'], 0) . "</td>";


            echo "<td class='amount'>";
            foreach ($due_notes as $note) {
              // Remove the date part from the note
              $clean_note = preg_replace('/\(Date: [^)]+\)/', '', $note);
              echo htmlspecialchars(trim($clean_note)) . "<br>"; // Display the cleaned note
          }

            // ... existing code ...
            echo "<td class='amount'>";
            // $total_due_flats = array_sum($due_flats);
            foreach ($due_flats as $due) {
              if (preg_match('/\d+/', $due, $matches)) {
                $numeric_due = (float)$matches[0]; // Change to $matches[0]
                echo "৳" . number_format($numeric_due, 0) . " <br>";
              }
            }
            echo "</td>";

            // Display total collected amount

            echo "</td>
               


                  
                 
                  <td>" . htmlspecialchars($f_status) . "</td>
                 
                  <td>
                      <form method='POST' action=''>
                          <input type='hidden' name='id' value='" . $row['f_flatId'] . "'>
                          <input type='number' name='paid_amount' placeholder='Enter amount' required>
                          <input type='text' name='due_note' placeholder='Enter Flat Due Note' required>
                          <button type='submit' class='btn btn-success mt-2'>Received</button>
                           
                        <a href='javascript:void(0);' onclick='loadEditForm(" . $row['f_flatId'] . ", \"" . $month . "\", \"" . $year . "\")' class='btn btn-primary mt-2'>Edit</a>
                         <a href='delete_reports.php?id=" . $row['f_flatId'] . "&month=" . $month . "&year=" . $year . "' class='btn btn-danger mt-2' onclick='return confirm(\"Are you sure you want to delete all records for Flat ID " . $row['f_flatId'] . " in " . $month . " " . $year . "?\");'>Delete</a>
                        <!-- Tick Button -->
                          <button type='button' class='btn btn-secondary mt-2' onclick='markRow(" . $row['f_flatId'] . ", \"" . $month . "\", \"" . $year . "\")'>
    ✅
</button>
                      </form>
                  </td>
                  </tr>";
          }

          echo "</tbody></table>";

          $conn->close();
          ?>
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
                <!-- Content will be loaded here from "edit_addData.php" -->
              </div>
            </div>
          </div>
        </div>

        <script>
          function loadEditForm(id, month, year) {
            $.ajax({
              url: 'edit_monthly_coll.php',
              type: 'GET',
              data: {
                f_flatId: id,
                month: month,
                year: year
              },
              success: function(response) {
                $('#editModal .modal-body').html(response);
                $('#editModal').modal('show');

                // Attach submit event handler to all forms
                $('#editModal form').on('submit', function(event) {
                  event.preventDefault();

                  $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                      $('#editModal').modal('hide');
                      toastr.success('Record updated successfully!');
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

          function markRow(flatId, month, year) {
    let rowId = "row_" + flatId + "_" + month.toLowerCase() + "_" + year;
    let row = document.getElementById(rowId);
    if (row) {
        let isMarked = row.classList.contains("marked");
        let newMarkedStatus = isMarked ? 0 : 1;

        // Toggle the marked class
        row.classList.toggle("marked");

        // Send AJAX request to update the database
        $.ajax({
            url: 'update_mark_status.php',
            type: 'POST',
            data: {
                f_flatId: flatId,
                month: month,
                year: year,
                marked: newMarkedStatus
            },
            success: function(response) {
                console.log('Marked status updated successfully.');
            },
            error: function(xhr, status, error) {
                console.error('Error updating marked status:', status, error);
            }
        });
    } else {
        console.error("Row not found for flatId:", flatId, ", month:", month, ", year:", year);
    }
}
        </script>


        <!-- Include Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
      </div>
    </div>
  </div>
</main>
<?php include('footer.php'); ?>