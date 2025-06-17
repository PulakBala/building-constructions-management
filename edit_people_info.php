<?php include('connection.php'); ?>
<?php include('header.php'); ?>
<?php include('sidebar.php'); ?>

<main class="page-content">
  <div class="container py-4">
    <h3 class="mb-4 text-primary text-center">✏️ Edit People Info</h3>

    <?php
    // Get ID from URL
    $id = $_GET['id'];

    // Fetch existing data
    $sql = "SELECT name, work, address, mobile_number FROM people_info WHERE id=$id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = $_POST['name'];
        $work = $_POST['work'];
        $address = $_POST['address'];
        $mobile_number = $_POST['mobile_number'];
        $note = $_POST['note'];
        
        $update = "UPDATE people_info SET name='$name', work='$work', address='$address', mobile_number='$mobile_number', note='$note' WHERE id=$id";

        if ($conn->query($update) === TRUE) {
            echo "<div class='alert alert-success text-center'>✅ Data updated successfully!</div>";
            // Optional: Redirect back to main page
            echo "<script>setTimeout(() => window.location.href='contact_info.php', 1500);</script>";
        } else {
            echo "<div class='alert alert-danger text-center'>❌ Error updating data: " . $conn->error . "</div>";
        }
    }
    ?>

    <form method="POST" class="card p-4 shadow rounded-3">
      <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="name" value="<?= $row['name']; ?>" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Work</label>
        <input type="text" name="work" value="<?= $row['work']; ?>" class="form-control">
      </div>

      <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control"><?= $row['address']; ?></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Mobile Number</label>
        <input type="text" name="mobile_number" value="<?= $row['mobile_number']; ?>" class="form-control">
      </div>
      
      <div class="mb-3">
       <label class="form-label">Note</label>
       <textarea name="note" class="form-control" rows="3"><?= $row['note']; ?></textarea>
      </div>

      <button type="submit" class="btn btn-success rounded-pill">💾 Update</button>
</br>
      <a href="contact_info.php" class="btn btn-secondary rounded-pill">🔙 Back</a>

    </form>
  </div>
</main>

<?php include('footer.php'); ?>
