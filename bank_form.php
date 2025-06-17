<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<main class="page-content">
  <div class="container-fluid px-4">

    <div class="row justify-content-center">
      <div class="col-lg-10">
        <div class="card shadow-lg border-0 mt-4">
          <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Bank Account Information</h4>
          </div>
          <div class="card-body">

            <form action="upload_bank.php" method="POST" enctype="multipart/form-data" class="row g-4">

              <div class="col-md-6">
                <label class="form-label">Bank Name</label>
                <input type="text" name="bank_name" class="form-control" placeholder="e.g. BRAC Bank" required>
              </div>

              <div class="col-md-6">
                <label class="form-label">Account Number</label>
                <input type="text" name="account_number" class="form-control" placeholder="e.g. 0123456789">
              </div>

              <div class="col-md-6">
                <label class="form-label">Branch</label>
                <input type="text" name="branch" class="form-control" placeholder="e.g. Dhanmondi Branch">
              </div>

              <div class="col-md-6">
                <label class="form-label">Account Holder Name</label>
                <input type="text" name="account_name" class="form-control" placeholder="e.g. John Doe">
              </div>

              <div class="col-md-6">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact" class="form-control" placeholder="e.g. 017xxxxxxxx">
              </div>

              <div class="col-md-6">
                <label class="form-label">Signature (Image)</label>
                <input type="file" name="signature" class="form-control" accept="image/*">
              </div>

              <div class="col-md-6">
                <label class="form-label">Amount (৳)</label>
                <input type="number" step="0.01" name="amount" class="form-control" placeholder="e.g. 100000.00">
              </div>

              <div class="col-md-6">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control">
              </div>

              <div class="col-12 d-flex justify-content-end">
                <button type="submit" class="btn btn-success px-4">Save Information</button>
              </div>

            </form>

          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<?php include('footer.php') ?>
