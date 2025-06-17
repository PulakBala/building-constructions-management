<?php
include('connection.php');

$search = $_GET['query'] ?? '';

$sql = "SELECT * FROM people_info 
        WHERE name LIKE '%$search%' 
        OR work LIKE '%$search%' 
        OR address LIKE '%$search%' 
        OR mobile_number LIKE '%$search%' 
        OR note LIKE '%$search%'
        ORDER BY id DESC";

$result = $conn->query($sql);

$output = "";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<tr>";
        $output .= "<td class='text-center'>" . $row['id'] . "</td>";
        $output .= "<td>" . $row['name'] . "</td>";
        $output .= "<td>" . $row['work'] . "</td>";
        $output .= "<td>" . $row['address'] . "</td>";
        $output .= "<td>" . $row['mobile_number'] . "</td>";
        $output .= "<td>" . $row['note'] . "</td>";
        $output .= "<td class='text-center'>
                    <a href='edit_people_info.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning me-1'>✏️ Edit</a>
                    <a href='delete_people_info.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Are you sure you want to delete this entry?');\">🗑️ Delete</a>
                  </td>";
        $output .= "</tr>";
    }
} else {
    $output .= "<tr><td colspan='7' class='text-center text-muted'>No matching records found</td></tr>";
}

echo $output;
?>
