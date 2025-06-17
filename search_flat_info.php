<?php
include('connection.php');

if (isset($_POST['query'])) {
    $search = mysqli_real_escape_string($conn, $_POST['query']);

    // SQL query to search name, mobile_number or nid_number
    $sql = "SELECT id, bulding_name, name, mobile_number, nid_number, nid_img, rent, advance, created_at 
            FROM flat_info 
            WHERE name LIKE '%$search%' 
               OR mobile_number LIKE '%$search%' 
               OR nid_number LIKE '%$search%'
            ORDER BY created_at DESC";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Rent</th>
                    <th>Advance</th>
                    <th>Mobile</th>
                    <th>NID</th>
                    <th>NID Image</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
              </thead>';
        echo '<tbody>';

        $counter = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $counter++ . '</td>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['rent']) . '</td>';
            echo '<td>' . htmlspecialchars($row['advance']) . '</td>';
            echo '<td>' . htmlspecialchars($row['mobile_number']) . '</td>';
            echo '<td>' . htmlspecialchars($row['nid_number']) . '</td>';
            echo '<td><a href="' . htmlspecialchars($row['nid_img']) . '" target="_blank">View Image</a></td>';
            echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
            echo '<td>
                    <a href="edit-flat.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning">Edit</a>
                    <a href="delete_flat_info.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger">Delete</a>
                  </td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo "<p class='text-danger'>No matching records found!</p>";
    }
} else {
    echo "<p class='text-danger'>No search query received.</p>";
}
?>
