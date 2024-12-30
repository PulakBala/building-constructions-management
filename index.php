<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<?php
// Fetch current month and year
$currentMonth = date('F');  // e.g., "December"
$currentYear = date('Y');   // e.g., "2024"

// Check if a search query is provided
$searchQuery = isset($_POST['query']) ? $_POST['query'] : '';

// Fetch data
$flatData = getFlatBillSummary($currentMonth, $currentYear);
// print_r($flatData);

// Query to calculate the total f_due
$query = "SELECT SUM(f_due) AS total_due FROM flat_bill";
$result = mysqli_query($conn, $query);

// Check if the query ran successfully
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $totalDue = $row['total_due'];
   
} else {
    echo "Error: " . mysqli_error($conn);
}


// Query to calculate the total f_due
$query = "SELECT SUM(amount) AS total_due FROM expense";
$result = mysqli_query($conn, $query);

// Check if the query ran successfully
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $personal_expense = $row['total_due'];
   
} else {
    echo "Error: " . mysqli_error($conn);
}

?>

<main class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="form-group col-md-12">
                <div class=" mt-4">
                    <div class="row">
                        <!-- Bill Paid Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title">Bill Paid</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-success"><?= get_acc('0', date('F'), date('Y'), 'COUNT-TR-MONTH') ?>/<span class="text-info">131</span></h2>
                                    <p class="card-text text-center">Amount paid on <?= date('F') ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Expense Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="card-title">Total Expense</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-danger"><?= get_expense_sum('THIS-MONTH') ?> .TK</h2>
                                    <p class="card-text text-center">Total expenses in <?= date('F') ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Collection Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title">Collection</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-info"><?= get_acc('0', date('F'), date('Y'), 'MONTH') ?> .TK</h2>
                                    <p class="card-text text-center">Total collections in <?= date('F') ?></p>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="card-title">Total Due</h5>
                                </div>
                                <div class="card-body">
                                <h2 class="card-text text-success"><?= htmlspecialchars($totalDue) ?></h2>
                                <p class="card-text text-center">Total due amount</p>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="card-title">Personal Expenses</h5>
                                </div>
                                <div class="card-body">
                                <h2 class="card-text text-success"><?= htmlspecialchars($personal_expense) ?></h2>
                                <p class="card-text text-center">Total Personal Expense</p>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>

                <div>
                    <input type="search" id="form1" class="form-control" placeholder="Search : Name" />
                </div>

                <div class="table-responsive mt-4">

                    <h2>BILL SUMMARY FOR <?php echo $currentMonth . " " . $currentYear; ?></h2>
                    <table class="table table-bordered  table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Manager Name</th>
                                <th>Building Name</th>
                                <th>Total Collected (৳)</th>
                                <th>Total Due (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($flatData as $flat): ?>
                                <tr>
                                    <td><?php echo $flat['f_flatId']; ?></td>
                                    <td><?php echo $flat['owner_name']; ?></td>
                                    <td><?php echo $flat['flatname']; ?></td>


                                    <td><?php echo number_format($flat['total_collected'], 2); ?></td>
                                    <td><?php echo number_format($flat['f_due'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Include Font Awesome for icons -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        $("#form1").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $.ajax({
                url: "search_students.php", // This is the PHP file that processes the search and returns results
                type: "POST",
                data: {
                    query: value
                },
                success: function(data) {
                    console.log(data);
                    $("tbody").html(data); // Update the table body with the search results
                }
            });
        });
    });
</script>

<?php include('footer.php') ?>