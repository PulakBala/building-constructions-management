<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>


<main class="page-content">

  <div class="container-fluid">
    <div class="row">

   

    <div class="form-group col-md-12">
                <div class="container mt-4">
                    <div class="row">
                        <!-- Bill Paid Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="card-title">TOTAL BILL</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-success"><?=get_acc('0','0','0','ALL')?> .TK</h2>
                                    <p class="card-text text-center">Total Collection</p>
                                </div>
                            </div>
                        </div>

                        <!-- Expense Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-warning text-white">
                                    <h5 class="card-title">MONTHLY COLLECTION</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-danger"><?=get_acc('0',date('F'),date('Y'),'MONTH')?> .TK</h2>
                                    <p class="card-text  text-center">Total collections for <?=date('F')?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Collection Card -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow border-0">
                                <div class="card-header bg-success text-white">
                                    <h5 class="card-title  text-center">MONTHLY EXPENSE</h5>
                                </div>
                                <div class="card-body">
                                    <h2 class="card-text text-info"><?= get_expense_sum('ALL') ?> .TK</h2>
                                    <p class="card-text text-center">Total expense for <?=date('F')?></p>
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
</main>
<?php include('footer.php') ?>