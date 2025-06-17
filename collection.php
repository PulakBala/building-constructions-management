<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>


<main class="page-content">

  <div class="container-fluid">

    <div class="row">

<?php


            // Fetch all billing records
            $fetchQuery = "SELECT * FROM flat_bill";
            $result = mysqli_query($conn, $fetchQuery);

            echo "<h2>Bill Records</h2>";
            echo "<table class='table table-bordered'>";
            echo "<thead>
        <tr>
            <th>Date</th>
            <th>Month</th>
            <th>Year</th>
            <th>Service Charge</th>
            <th>Internet Bill</th>
            <th>Dish Bill</th>
            <th>Flat Rent</th>
            <th>Common Bill</th>
            <th>Center Rent</th>
            <th>Center Various</th>
            <th>Attic Rent</th>
            <th>Donation</th>
            <th>Development Various</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
      </thead>";
            echo "<tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
            <td>" . htmlspecialchars($row['f_date']) . "</td>
            <td>" . htmlspecialchars($row['f_month']) . "</td>
            <td>" . htmlspecialchars($row['f_year']) . "</td>
            <td>৳" . number_format($row['f_service_charge'], 2) . "</td>
            <td>৳" . number_format($row['f_int_bill'], 2) . "</td>
            <td>৳" . number_format($row['f_dish_bill'], 2) . "</td>
            <td>৳" . number_format($row['f_flat_rent'], 2) . "</td>
            <td>৳" . number_format($row['f_c_current_bill'], 2) . "</td>
            <td>৳" . number_format($row['f_c_center_rent'], 2) . "</td>
            <td>৳" . number_format($row['f_c_center_various'], 2) . "</td>
            <td>৳" . number_format($row['f_atic_rent'], 2) . "</td>
            <td>৳" . number_format($row['f_d_donation'], 2) . "</td>
            <td>৳" . number_format($row['f_d_various_charge'], 2) . "</td>
            <td>৳" . number_format($row['f_total'], 2) . "</td>
            <td>" . htmlspecialchars($row['f_status']) . "</td>
          </tr>";
            }

            echo "</tbody></table>";


?>


    </div>

  </div>
</main>
<?php include('footer.php') ?>