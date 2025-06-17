<?php include('connection.php') ?>
<?php include('header.php') ?>
<?php include('sidebar.php') ?>

<main class="page-content">
  <div class="container py-4">
    <h3 class="mb-4 text-center text-primary">📋 People Information</h3>
    
       <div class="mb-3">
      <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search by name, work, mobile, etc...">
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover shadow-sm">
        <thead class="table-primary text-center">
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Work</th>
            <th>Address</th>
            <th>Mobile Number</th>
             <th>Note</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="peopleData">
          <?php
          $sql = "SELECT * FROM people_info ORDER BY id DESC";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td class='text-center'>" . $row['id'] . "</td>";
                  echo "<td>" . $row['name'] . "</td>";
                  echo "<td>" . $row['work'] . "</td>";
                  echo "<td>" . $row['address'] . "</td>";
                  echo "<td>" . $row['mobile_number'] . "</td>";
                   echo "<td>" . $row['note'] . "</td>";
                  echo "<td class='text-center'>
                          <a href='edit_people_info.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning me-1'>✏️ Edit</a>
                          <a href='delete_people_info.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this entry?');\">🗑️ Delete</a>
                        </td>";
                  echo "</tr>";
              }
          } else {
              echo "<tr><td colspan='6' class='text-center text-muted'>No data available</td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</main>

<script>
  const searchInput = document.getElementById("searchInput");

  searchInput.addEventListener("keyup", function () {
    const query = this.value;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "search_people.php?query=" + encodeURIComponent(query), true);
    xhr.onload = function () {
      if (this.status === 200) {
        document.getElementById("peopleData").innerHTML = this.responseText;
      }
    };
    xhr.send();
  });
</script>

<?php include('footer.php') ?>
