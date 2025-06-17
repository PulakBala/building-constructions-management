<?php include ('connection.php') ?>
<?php include ('header.php') ?>
<?php include ('sidebar.php') ?>

<?php
// Fetch current month and year
// $currentMonth = date('F');
// $currentYear = date('Y');
// $currentMonth = isset($_POST['month']) ? $_POST['month'] : date('F', strtotime('last month'));
// $currentYear = isset($_POST['year']) ? $_POST['year'] : date('Y', strtotime('last month'));

// বর্তমান মাস এবং বছর নির্ণয় করা
$currentMonth = date('F');  // বর্তমান মাস
$currentYear = date('Y');  // বর্তমান বছর

// যদি সেশন ভ্যালু সেট না থাকে, তাহলে সেট করা হবে
if (!isset($_SESSION['savedMonth'])) {
    $_SESSION['savedMonth'] = $currentMonth;
    $_SESSION['previousMonth'] = date('F', strtotime('first day of -1 month'));  // আগের মাস সেট করা
}

// চেক করা হবে যে currentMonth পরিবর্তিত হয়েছে কিনা
if ($_SESSION['savedMonth'] !== $currentMonth) {
    $_SESSION['previousMonth'] = $_SESSION['savedMonth'];  // আগের মাস ধরে রাখা হবে
    $_SESSION['savedMonth'] = $currentMonth;  // নতুন মাস আপডেট করা হবে
}

// আগের মাস পাওয়া
$previousMonth = $_SESSION['previousMonth'];

// Check if a search query is provided
$searchQuery = isset($_POST['query']) ? $_POST['query'] : '';

// Fetch data
$flatData = getFlatBillSummary($currentMonth, $currentYear);
// print_r($flatData);

// New variables for totalExpense
$expenseMonth = date('F', strtotime($currentMonth));
$expenseYear = $currentYear;
if ($currentMonth == 'December') {
    $expenseYear = $currentYear + 1;
}

// Fetch total expense amount for the current month and year
$totalExpense = getTotalExpense($expenseMonth, $expenseYear);

// Function to fetch total expense amount
function getTotalExpense($month, $year)
{
    global $conn;  // Assuming $conn is your database connection
    $query = 'SELECT SUM(amount) as total_amount FROM expense WHERE MONTHNAME(date) = ? AND YEAR(date) = ?';
    $stmt = $conn->prepare($query);
    $stmt->bind_param('si', $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['total_amount'];
}

// Calculate previous month and year

?>

<style>
  .amount {
    color: red;
    /* Change to your desired color */
  }
  tr.marked .color{
    background-color: #012f6a !important; /* Set background color to blue */
    color: white !important; /* Set font color to white */
    border-radius: 5px; /* Add border radius */
    font-weight: bold; /* Make font bold */
    transition: background-color 0.3s ease-in-out; /* Smooth transition */

}
</style>

<main class="page-content ">
    <div class="container-fluid overflow-auto">
        <div class="row">
      
            <!-- card  -->
            <div class="form-group col-md-12">

                <div class="border">
                    <div class="row">
                         <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title">Last Month's Collection</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-info"><?= get_acc('0', $previousMonth, $currentYear, 'MONTH') ?></h2>
                                    <p class="card-text text-center">Total collections in <?= $previousMonth ?></p>
                                </div>
                            </div>
                        </div>
                        
                        
                           <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title">Total Personal Expense</h5>
                                </div>
                                <div class="card-body">
                                <h2> ৳<?php echo number_format($totalExpense ?? 0, 2); ?></h2>
                                    <p class="card-text text-center">Total expense amount <?= $expenseMonth ?>, <?= $expenseYear ?></p>
                                </div>
                            </div>
                        </div>
                        
                          <div class="col-md-4 mb-4">
                            <div class="card shadow border-0"  onclick="openDueModal()" style="cursor: pointer;">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="card-title">Last Month's Due</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-success"><?= get_acc('0', $previousMonth, $currentYear, 'DUE-MONTH') ?> .TK</h2>
                                    <p class="card-text text-center">Total due amount <?= $previousMonth ?></p>
                                </div>
                            </div>
                          </div>
                       

                        <!-- Expense Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="card-title">Manager Expense</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-danger"><?= get_expense_sum(date('m', strtotime($expenseMonth)), $expenseYear) ?> .TK</h2>
                                    <p class="card-text text-center">Total expenses in <?= $expenseMonth ?></p>
                                </div>
                            </div>
                        </div>

                      <!-- Due list card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-lg border-0 due-card"  style="cursor: pointer;">
                                <div class="card-header bg-danger text-white text-center">
                                    <h5 class="card-title m-0 p-2">Last Month's Due List</h5>
                                </div>
                                <div class="card-body text-center ">
                                    <h2 class="view-due-text p-2"><button onclick="openDueModal()" class='btn btn-outlet border border-bold'>view due</button></h2>
                                </div>
                            </div>
                        </div>




                    </div>
                </div>

                <!-- Modal Structure -->
                <div class="modal fade" id="dueModal" tabindex="-1" aria-labelledby="dueModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dueModalLabel">Due Amount Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                            <p id="dueAmountDetails">Loading...</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>



            </div>



         


                <!-- Include Font Awesome for icons -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            </div>
    
    </div>


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

<script>

function openDueModal() {
    // Load dynamic content via AJAX
    $('#dueAmountDetails').html('Loading...');

    $.ajax({
        url: 'fetch_due_details.php',
        method: 'GET',
        success: function(response) {
            $('#dueAmountDetails').html(response);
            $('#dueModal').modal('show');
        },
        error: function() {
            $('#dueAmountDetails').html('<p class="text-danger">Failed to load data.</p>');
        }
    });
}
</script>

<?php include ('footer.php') ?>