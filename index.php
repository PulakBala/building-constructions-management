<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php')?>
<?php
// Fetch current month and year
// $currentMonth = date('F');  
// $currentYear = date('Y');  

$currentMonth = date('F', strtotime('last month')); // Previous month er naam
$currentYear = date('Y', strtotime('last month'));  // Previous month er year

var_dump($currentMonth);

// Check if a search query is provided
$searchQuery = isset($_POST['query']) ? $_POST['query'] : '';

// Fetch data
$flatData = getFlatBillSummary($currentMonth, $currentYear);
// print_r($flatData);

// Fetch total expense amount for the current month and year
$totalExpense = getTotalExpense($currentMonth, $currentYear);

// Function to fetch total expense amount
function getTotalExpense($month, $year) {
    global $conn; // Assuming $conn is your database connection
    $query = "SELECT SUM(amount) as total_amount FROM expense WHERE MONTHNAME(date) = ? AND YEAR(date) = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_amount'];
}

?>

<main class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="form-group col-md-12">
                <div class=" mt-4">
                    <div class="row">
                       

                        <!-- Expense Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="card-title">Manager Expense</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-danger"><?= get_expense_sum('THIS-MONTH') ?> .TK</h2>
                                    <p class="card-text text-center">Total expenses in <?= $currentMonth ?></p>
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
                                <h2 class="card-text text-info"><?= get_acc('0', $currentMonth, $currentYear, 'MONTH') ?> .TK</h2>
                                <p class="card-text text-center">Total collections in <?= $currentMonth ?></p>
                                </div>
                            </div>
                        </div>


                       <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="card-title">Total Due</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-success"><?= get_acc('0', $currentMonth, $currentYear, 'DUE-MONTH') ?> .TK</h2>
                                    <p class="card-text text-center">Total due amount <?= $currentMonth ?></p>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="card-title">Total Personal Expense</h5>
                                </div>
                                <div class="card-body">
                                <h2> ৳<?php echo number_format($totalExpense, 2); ?></h2>
                                    <p class="card-text text-center">Total expense amount <?= $currentMonth ?></p>
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