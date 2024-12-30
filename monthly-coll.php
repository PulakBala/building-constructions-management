<?php ob_start(); ?>
<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<main class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="form-group col-md-12">
        <div class="container-fluid m-0">

          <?php
          if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $typ = $_POST['typ'];
            $paid_amount = isset($_POST['paid_amount']) ? floatval($_POST['paid_amount']) : 0;
            // if (isset($_POST['mobile_number'])) {
            //   $mobile_number = $_POST['mobile_number'];
            //   $f_total = $_POST['f_total'];
            //   $f_month = $_POST['f_month'];
            //   $f_year = $_POST['f_year'];
            //   $f_due = $f_total - $paid_amount;

            //   $msg = 'Bill Paid ৳' . number_format($paid_amount, 2) . ' for ' . $f_month . '-' . $f_year .
            //     '. Due amount: ৳' . number_format($f_due, 2);

            //   sendGSMS('8809617620596', $mobile_number, $msg, 'C200022562c68264972b36.87730554', 'text&contacts');
            // }
            $updateQuery = "UPDATE flat_bill 
                           SET f_status = ?, 
                               f_paid_amount = ?, 
                               f_due = (f_total - ?) 
                           WHERE f_id = ?";

            $stmt = $conn->prepare($updateQuery);
            $stmt->bind_param("sddi", $typ, $paid_amount, $paid_amount, $id);

            if ($stmt->execute()) {
              header('Location: ' . $_SERVER['PHP_SELF']);
              exit();
            } else {
              echo "Error updating status: " . $conn->error;
            }

            $stmt->close();
          }
          //sendGSMS('8809617620596',$mobileNumber,$msg,'C200022562c68264972b36.87730554','text&contacts');
          ?>

          <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            <div class="alert alert-success">Status updated successfully!</div>
          <?php endif; ?>

          <?php
          // Fetch all billing records
          $fetchQuery = "SELECT * FROM flat_bill LEFT JOIN flats
		  on flats.id = flat_bill.f_flatId";



          // {{ edit_2 }}: Modify the query to filter by month and year if provided
          if (isset($_GET['month']) && isset($_GET['year'])) {
            $month = $_GET['month'];
            $year = $_GET['year'];
            $fetchQuery .= " WHERE f_month = '$month' AND f_year = '$year'";
          }
          // {{ edit_2 }} end

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
          echo "<thead  class='thead-dark'>
            <tr>
                <th>Manager</th>
                <th>Building</th>
                <th>Month</th>
                <th>Year</th>


                <th>Flat&nbsp;Rent</th>
               
                <th>Current&nbsp;Bill</th>
                <th>Guard&nbsp;Sallary</th>
                <th>Other&nbsp;Expense</th>
                <th>Empty&nbsp;Flat</th>

                
                <th>Total</th>
                <th>Status</th>
                <th class='text-center'>Action</th> <!-- Added Action column -->
            </tr>
          </thead>";
          echo "<tbody>";

          while ($row = mysqli_fetch_assoc($result)) {
            if ($row['f_status'] == 'Pending') {
              $f_status =  '<span class="badge badge-danger">' . htmlspecialchars($row['f_status']) . '</span>';
            } else {
              $f_status =  '<span class="badge badge-success">' . htmlspecialchars($row['f_status']) . '</span>';
            }
            echo "<tr>
              
              <td>" . htmlspecialchars($row['owner_name']) . "</td>
              <td>" . htmlspecialchars($row['flatname']) . "</td>
              <td>" . htmlspecialchars($row['f_month']) . "</td>
              <td>" . htmlspecialchars($row['f_year']) . "</td>
              <td>৳" . number_format($row['f_flat_rent'], 0,) . "</td>
              <td>৳" . number_format($row['f_c_current_bill'], 0,) . "</td>
             <td>৳" . number_format($row['f_guard_slry'], 0,) . "</td>
             <td>৳" . number_format($row['f_c_center_various'], 0,) . "</td>
             <td>৳" . number_format($row['f_empty_flat'], 0,) . "</td>

              <td>৳" . number_format($row['f_total'], 0,) . "</td>
              <td>" . $f_status . "</td>
              <td>";

            if ($row['f_status'] == 'Pending') {
              echo "<form method='POST' action=''>
                      <input type='hidden' name='id' value='" . $row['f_id'] . "'>
                      <input type='hidden' name='typ' value='Received'>
                      <input type='hidden' name='mobile_number' value='" . $row['mobile_number'] . "'>
                      <input type='hidden' name='f_total' value='" . $row['f_total'] . "'>
                      <input type='hidden' name='f_month' value='" . $row['f_month'] . "'>
                      <input type='hidden' name='f_year' value='" . $row['f_year'] . "'>
                      <div class='d-flex '>
                      <input type='number' name='paid_amount' placeholder='paid amount' required class='p-1 rounded mr-2'>
                      <button type='submit' class='btn btn-success '>Received</button>
                      </div>
                    </form>";
            } else {
              echo "<form method='POST' action=''>
                      <input type='hidden' name='id' value='" . $row['f_id'] . "'>
					  <input type='hidden' name='typ' value='Pending'>
                      <button type='submit' class='btn btn-warning text-white'>Unpaid</button>
                    </form>";
            }
            echo "</td>
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