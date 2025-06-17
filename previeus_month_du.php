<?php 
include ('connection.php'); 
include ('header.php'); 
include ('sidebar.php'); 

// Default current month and year
$currentMonth = date('F');
$currentYear = date('Y');

// যদি ফিল্টার ফর্ম সাবমিট হয় তাহলে নতুন মান সেট করবো
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentMonth = $_POST['month'];
    $currentYear = $_POST['year'];
}

// আগের মাস নির্ণয়
$previousMonth = date('F', strtotime("first day of -1 month", strtotime($currentMonth . " 1 " . $currentYear)));

// Get total expenses for selected month and year
$totalExpense = getTotalExpense($currentMonth, $currentYear);

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
    return $row['total_amount'] ?? 0;
}
?>


<style>
  .amount {
    color: red;
    /* Change to your desired color */
  }
  tr.marked .color{
    border:5px solid blue;
    border-radius:13px;
    padding:5px 10px;
    font-weight: bold; /* Make font bold */
    transition: background-color 0.3s ease-in-out; /* Smooth transition */
  }
  
    .status-received {
    color: green !important;  /* Added !important to ensure it overrides other styles */
    font-weight: bold;
  }
  
  .status-pending {
    color: red !important;  /* Added !important to ensure it overrides other styles */
    font-weight: bold;
  }
  
  
  
</style>

<main class="page-content">
    <div class="container-fluid  ">
        <div class="row">
            <div class="form-group col-md-12 ">
                <!-- Add filter form -->
                <div class="row mb-3">
                        <div class="col-md-4 mb-2 row">
                            <form method="POST" id="filterForm" class="d-flex gap-2">
                                <select name="month" class="form-control">
                                    <?php
                                    $months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
                                    foreach ($months as $month) {
                                        $selected = ($month == $currentMonth) ? 'selected' : '';
                                        echo "<option value='$month' $selected>$month</option>";
                                    }
                                    ?>
                                </select>
                                <select name="year" class="form-control">
                                    <?php
                                    $currentYearNum = date('Y');
                                    for ($year = $currentYearNum; $year >= $currentYearNum - 5; $year--) {
                                        $selected = ($year == $currentYear) ? 'selected' : '';
                                        echo "<option value='$year' $selected>$year</option>";
                                    }
                                    ?>
                                </select>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </form>
                        </div>
                 


               


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
                                    <h2> ৳<?php echo number_format($totalExpense, 2); ?></h2>
                                    <p class="card-text text-center">Total expense amount <?= $currentMonth ?>, <?= $currentYear ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="card-title">Manager Expense</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-danger"><?= get_expense_sum(date('m', strtotime($currentMonth)), $currentYear) ?> .TK</h2>
                                    <p class="card-text text-center">Total expenses in <?= $currentMonth ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card shadow-lg border-0 due-card"  style="cursor: pointer;">
                                <div class="card-header bg-danger text-white text-center">
                                    <h5 class="card-title m-0 p-2">Last Month's Due List</h5>
                                </div>
                                <div class="card-body text-center ">
                                    <h2 class="view-due-text p-2">
                                     <button
                                        id="viewDueBtn"
                                        class="btn btn-outlet border border-bold"
                                        data-month="<?= htmlspecialchars($previousMonth) ?>"
                                        data-year="<?= htmlspecialchars($currentYear) ?>"
                                        onclick="openDueModal()"
                                        >View Due
                                    </button>
                                    </h2>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 mb-4">
                            <div class="card shadow-lg border-0 due-card"  style="cursor: pointer;">
                                <div class="card-header bg-dark text-white text-center">
                                    <h5 class="card-title m-0 p-2">BILL REPORTS</h5>
                                </div>
                                <div class="card-body text-center ">
                               <h2 class=" p-2 "> <button onclick="openBill()" class='btn btn-outlet border border-bold'>View bill</button></h2>
                                </div>
                            </div>
                        </div>


                    </div>
           


<!-- Add Modal HTML for Bill Reports -->
<div class="modal fade" id="billModal" tabindex="-1" aria-labelledby="billModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="billModalLabel">Bill Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body overflow-auto" id="billModalBody">
                <!-- Bill report details will be loaded here -->
                <p>Loading bill report...</p>
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

                    <!-- last month's due list modal js code  -->
                    <script>
                            //fetch due details modal open
                             function openDueModal() {
                                //grab the button
                                const btn = document.getElementById('viewDueBtn');
                                const month = btn.dataset.month;
                                const year  = btn.dataset.year;

                                // Load dynamic content via AJAX
                                $('#dueModal').modal('show');
                                $('#dueAmountDetails').html('Loading...');

                                $.ajax({
                                    url: 'fetch_due_details.php',
                                    method: 'GET',
                                    data: { month, year },
                                    success: function(html) {
                                        $('#dueAmountDetails').html(html);
                                       
                                    },
                                    error: function() {
                                        $('#dueAmountDetails').html(
                                        '<p class="text-danger">Failed to load data.</p>'
                                        );
                                    }
                                });
                            }
                    </script>


                        <script>
                            
                            function openBill() {
                                $('#billModalBody').html('Loading...');
                                loadBillReports();
                            }

                            function loadBillReports(formData = null) {
                            $.ajax({
                                url: 'fetch_bill_reports.php',
                                method: 'GET',
                                data: formData,
                                success: function(response) {
                                    $('#billModalBody').html(response);
                                    
                                    if (!formData) {  // Only show modal on initial load
                                        var myModal = new bootstrap.Modal(document.getElementById('billModal'), {
                                            backdrop: 'static',
                                            keyboard: false
                                        });
                                        myModal.show();
                                    }
                                },
                                error: function() {
                                    $('#billModalBody').html('<p class="text-danger">Failed to load data.</p>');
                                }
                            });
                        }

                        // Only target the bill report filter form
                        $(document).on('submit', '#billReportForm', function(event) {
                            event.preventDefault();
                            let formData = $(this).serialize();
                            loadBillReports(formData);
                        });


                        </script>





<?php include('footer.php') ?>