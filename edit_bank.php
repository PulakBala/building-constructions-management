<?php
include('connection.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Invalid request.');
}

$id = $_GET['id'];
$query = "SELECT * FROM bank_accounts WHERE id = $id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) != 1) {
    die('Record not found.');
}

$data = mysqli_fetch_assoc($result);
?>

<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<main class="page-content">
    <div class="container-fluid">
        <h3 class="mb-4">Edit Bank Account</h3>

        <form action="update_bank.php" method="POST" enctype="multipart/form-data" class="row g-3">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">

            <div class="col-md-6">
                <label>Bank Name</label>
                <input type="text" name="bank_name" class="form-control" value="<?= $data['bank_name'] ?>" >
            </div>
            <div class="col-md-6">
                <label>Account Number</label>
                <input type="text" name="account_number" class="form-control" value="<?= $data['account_number'] ?>" >
            </div>
            <div class="col-md-6">
                <label>Branch</label>
                <input type="text" name="branch" class="form-control" value="<?= $data['branch'] ?>" >
            </div>
            <div class="col-md-6">
                <label>Account Name</label>
                <input type="text" name="account_name" class="form-control" value="<?= $data['account_name'] ?>" >
            </div>
            <div class="col-md-6">
                <label>Contact</label>
                <input type="text" name="contact" class="form-control" value="<?= $data['contact'] ?>">
            </div>
            <div class="col-md-6">
                <label>Signature (change if needed)</label>
                <input type="file" name="signature" class="form-control" accept="image/*">
                <?php if (!empty($data['signature_path'])): ?>
                    <img src="<?= $data['signature_path'] ?>" width="100" class="mt-2" alt="Current Signature">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control" value="<?= $data['amount'] ?>" >
            </div>
            <div class="col-md-6">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="<?= $data['date'] ?>" >
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success">Update</button>
                <a href="bank_list.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php include('footer.php') ?>
