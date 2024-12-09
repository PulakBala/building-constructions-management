<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<?php
// Fetch current month and year
$currentMonth = date('F');  // e.g., "December"
$currentYear = date('Y');   // e.g., "2024"

// Fetch data
$flatData = getFlatBillSummary($currentMonth, $currentYear);
// print_r($flatData);
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



                      
                    </div>
                </div>


                <div class="table-responsive mt-4">
                            <h2>FLAT BILL SUMMARY FOR <?php echo $currentMonth . " " . $currentYear; ?></h2>
                            <table class="table table-bordered  table-hover">
                                <thead>
                                    <tr>
                                        <th>Flat ID</th>
                                        <th>Flat Name</th>
                                        <th>Flat Number</th>
                                        <th>Total Collected (৳)</th>
                                        <th>Total Due (৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($flatData as $flat): ?>
                                        <tr>
                                            <td><?php echo $flat['f_flatId']; ?></td>
                                            <td><?php echo $flat['flatname']; ?></td>
                                            <td><?php echo $flat['flat_number']; ?></td>
                                           

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
<?php include('footer.php') ?>