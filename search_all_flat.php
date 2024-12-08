<?php include('connection.php')?>

<?php 

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']);
    $query = "SELECT * FROM flats WHERE owner_name LIKE '%$search%' OR flat_number LIKE '%$search%' OR mobile_number LIKE '%$search%' OR optional_number LIKE '%$search%'";
  
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '
            <div class="card mt-2">
                <div class="card-body p-4 shadow-lg rounded" style="background-color: #f8f9fa;">
                    <h5 class="card-title">
                        <span class="fw-bold" style="color: #3498db;">Name:</span>
                        <span class="text-muted">' . htmlspecialchars($row['owner_name']) . '</span>
                    </h5>
                    <hr>
                    <p class="card-text">
                        <span class="fw-bold" style="color: #2ecc71;">Mobile Number:</span>
                        <span class="text-muted">' . htmlspecialchars($row['mobile_number']) . '</span>
                    </p>
  
                    <p class="card-text">
                        <span class="fw-bold" style="color: #2ecc71;">Optional Number:</span>
                        <span class="text-muted">' . htmlspecialchars($row['optional_number']) . '</span>
                    </p>
  
                    <p class="card-text">
                        <span class="fw-bold" style="color: #e74c3c;">Flat No:</span>
                        <span class="text-muted">' . htmlspecialchars($row['flat_number']) . '</span>
                    </p>
                    <a href="edit-flat.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                    <a href="flat-details.php?id=' . $row['id'] . '" class="btn btn-info btn-sm">Add Bill</a>
                </div>
            </div>
            ';
        }
    } else {
        echo '<p class="text-center">No flats found.</p>';
    }
  }

?>