<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<main class="page-content">
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card shadow rounded-4 border-0">
          <div class="card-body">
            <h3 class="card-title text-center mb-4 text-primary">People Information Form</h3>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $name = $_POST['name'];
                $work = $_POST['work'];
                $address = $_POST['address'];
                $mobile_number = $_POST['mobile_number'];
                 $note = $_POST['note'];

                $sql = "INSERT INTO people_info (name, work, address, mobile_number, note) 
                        VALUES ('$name', '$work', '$address', '$mobile_number', '$note')";

                if ($conn->query($sql) === TRUE) {
                    echo "<div class='alert alert-success text-center'>✅ Data inserted successfully!</div>";
                } else {
                    echo "<div class='alert alert-danger text-center'>❌ Error: " . $conn->error . "</div>";
                }
            }
            ?>

            <form action="" method="POST">
              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Work</label>
                <input type="text" name="work" class="form-control">
              </div>

              <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3"></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Mobile Number</label>
                <input type="text" name="mobile_number" class="form-control">
              </div>
              
               <div class="mb-3">
                <label class="form-label">Note</label> <!-- ✅ new input -->
                <textarea name="note" class="form-control" rows="3" placeholder="Write any important notes..."></textarea>
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary rounded-pill">📩 Submit Info</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>

<?php include('footer.php'); ?>
