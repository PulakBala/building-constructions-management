<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>


<main class="page-content">

  <div class="container-fluid">

    <div class="row p-5">

    
    <div class="col-12">
        <?php

        if (isset($_GET['dlconfrm'])) {
            $exp =    $_GET['dlconfrm'];
            $qrt = "DELETE FROM expenses WHERE ex_id = '" . $exp . "'";
            if (mysqli_query($conn, $qrt)) {
                //echo ntf('Deleteed!',1000);
               // echo set_sw_al('', 'Deleted successfully!', '3000', 'info');
                //echo reloader('' . $site_link . '/dashboard/add-expense.php', 3000);
            }
            //echo reloader(''.$site_link.'/dashboard/add-expense.php',0);
        }


        if (isset($_GET['dl'])) {
            echo '<a  class="btn btn-success btn-midium"  href="add-expense.php?dlconfrm=' . $_GET['dl'] . '">Do you want to delete this expense ?</a>';
            echo '&nbsp;&nbsp;<a  class="btn btn-danger btn-midium"  href="add-expense.php">Cancel</a>';
            exit();
        }

        if (isset($_POST['submitExpense'])) {


            // Retrieve form data
            $expenseDate = $_POST['expenseDate'];
            $expenseName = $_POST['expenseName'];
            $expenseAmount = $_POST['expenseAmount'];
            $expenseType = $_POST['expenseType'];
            $expenseNote = $_POST['expenseNote'];
            $expenseTo = $_POST['expenseTo'];

            list($year, $month, $day) = explode("-", $expenseDate);

            $user_id = 1;
          //  notifier($expenseTo, $expenseName);
           // notifier($user_id, $expenseName);
            $sql = "INSERT INTO expenses (`ex_name`, `ex_amount`, `ex_type`, `ex_date`, `ex_update_date`,`ex_day`, `ex_month`, `ex_year`, `ex_description`, `ex_for`,`ex_leader`)
						VALUES ('" . $expenseName . "','" . $expenseAmount . "','" . $expenseType . "','" . $expenseDate . "','" . $expenseDate . "','" . $day . "','" . $month . "','" . $year . "','" . $expenseNote . "','" . $expenseTo . "',$user_id)";
            if ($conn->query($sql) === TRUE) {
               // echo gen_notifi('Expense Added!');
               // echo set_sw_al('', 'Expense Added successfully!', '3000', 'success');
                //echo reloader('add-expense.php');
            }
        }

        ?>


        <form method="post">

            <div class="mb-3">
                Expense Title
                <input type="text" class="form-control" id="expenseName" name="expenseName" placeholder="" required>
            </div>
            <div class="mb-3">
                Expense Amount
                <input type="text" class="form-control" id="expenseAmount" name="expenseAmount" placeholder="" required>
            </div>
            <div class="mb-3">
                Date
                <input type="date" class="form-control" id="expenseDate" name="expenseDate" placeholder="" value="<?= date("Y-m-d") ?>" required>
            </div>


            <div class="mb-3">
                Expense Type
                <select class="form-select form-control" id="expenseType" name="expenseType" >
                    <option selected="">Select Expense Type</option>
                    <?php
                    foreach ($fnc_expense_type as $key => $value) {
                        echo '<option value="' . $key . '">' . $value . '</option>';
                    }
                    ?>
                </select>
            </div>


            <div class="mb-3" style="display:none;">
                Expense To
                <select class="form-select form-control" id="expenseTo" name="expenseTo" >
                    <option selected="" value="">User</option>
                    <?php
            //         $queryv = mysqli_query($conn, "
            //   SELECT * FROM agency where ref = '{$user_id}'");
                    
            //         echo '<option value="' . $user_id . '">' . $user_name . '</option>';
            //         while ($rowv = mysqli_fetch_array($queryv)) {

            //             echo '<option value="' . $rowv['id'] . '">' . $rowv['username'] . '</option>';
            //         }
                    ?>
                </select>
            </div>


            <div class="mb-3">
                Note
                <textarea type="text" class="form-control" id="expenseNote" name="expenseNote" placeholder=""></textarea>
            </div>
            <button name="submitExpense" class="btn btn-danger full_width text-center">Add Expense</button>
        </form>
		
		<br>
		<br>
		<br>
		
		<h2>Expense History</h2>
        <?php
        // SQL query to select all data from the 'expense' table
        $sql = "
        SELECT * FROM expenses
        
        ";
        $result = $conn->query($sql);

        // Check if there are rows in the result set
        if ($result->num_rows > 0) {
            echo '<table class="table table-bordered table-hover" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info" style="width: 1593px;">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>Title</th>';
            echo '<th>Amount</th>';
            echo '<th>Type</th>';
            echo '<th>Date</th>';
            // echo '<th>Day</th>';
            //  echo '<th>Month</th>';
            //  echo '<th>Year</th>';
            echo '<th>Description</th>';
            echo '<th>Option</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row['ex_id'] . '</td>';
                echo '<td>' . $row['ex_name'] . '</td>';
                echo '<td>' . $row['ex_amount'] . '</td>';
                echo '<td>' . $row['ex_type'] . '</td>';
                echo '<td>' . $row['ex_date'] . '</td>';
                // echo '<td>' . $row['ex_day'] . '</td>';
                //  echo '<td>' . $row['ex_month'] . '</td>';
                // echo '<td>' . $row['ex_year'] . '</td>';
                echo '<td>' . $row['ex_description'] . '</td>';
                echo '<td>
        <a style="display:none;" href="edit-expense.php?exp=' . $row['ex_id'] . '" class="btn btn-primary  btn-sm">Edit</a>
        <a href="add-expense.php?dl=' . $row['ex_id'] . '"  class="btn btn-danger btn-sm" style="background:#c80202;">Delete</a>
        </td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
        } else {
            echo 'No records found';
        }
        ?>
    </div>


    </div>

  </div>
</main>
<?php include('footer.php') ?>