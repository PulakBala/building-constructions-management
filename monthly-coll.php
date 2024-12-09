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
            if (isset($_POST['mobile_number'])) {
              $mobile_number = $_POST['mobile_number'];
              $f_total = $_POST['f_total'];
              $f_month = $_POST['f_month'];
              $f_year = $_POST['f_year'];
              $msg = 'Bill Paid ' . $f_total . '.TK for ' . $f_month . '-' . $f_year . '';
              sendGSMS('8809617620596', $mobile_number, $msg, 'C200022562c68264972b36.87730554', 'text&contacts');
            }
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
          $result = mysqli_query($conn, $fetchQuery);

          echo "<h2>Bill Records</h2>";
          echo "<table class='table table-bordered table-hover'>";
          echo "<thead  class='thead-dark'>
            <tr>
                <th>Owner</th>
                <th>Flat</th>
                <th>Month</th>
                <th>Year</th>
                <th>Service&nbsp;Charge</th>
                <th>Net&nbsp;Bill</th>
                <th>Dish&nbsp;Bill</th>
                <th>Flat&nbsp;Rent</th>
                <th>Common&nbsp;Bill</th>
                <th>Center&nbsp;Rent</th>
                <th>Various</th>
                <th>Attic&nbsp;Rent</th>
                <th>Donation</th>
                <th>Development&nbsp;</th>
                <th>Total</th>
                <th>Status</th>
                <th>Action</th> <!-- Added Action column -->
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
              <td>" . htmlspecialchars($row['flat_number']) . "</td>
              <td>" . htmlspecialchars($row['f_month']) . "</td>
              <td>" . htmlspecialchars($row['f_year']) . "</td>
              <td>৳" . number_format($row['f_service_charge'], 0) . "</td>
              <td>৳" . number_format($row['f_int_bill'], 0,) . "</td>
              <td>৳" . number_format($row['f_dish_bill'], 0,) . "</td>
              <td>৳" . number_format($row['f_flat_rent'], 0,) . "</td>
              <td>৳" . number_format($row['f_c_current_bill'], 0,) . "</td>
              <td>৳" . number_format($row['f_c_center_rent'], 0,) . "</td>
              <td>৳" . number_format($row['f_c_center_various'], 0,) . "</td>
              <td>৳" . number_format($row['f_atic_rent'], 0,) . "</td>
              <td>৳" . number_format($row['f_d_donation'], 0,) . "</td>
              <td>৳" . number_format($row['f_d_various_charge'], 0,) . "</td>
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
                      <input type='number' name='paid_amount' placeholder='পরিশোধি��� টাকা' required>
                      <button type='submit' class='btn btn-success'>Received</button>
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