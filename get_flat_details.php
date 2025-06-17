<?php
include('connection.php');

if (!isset($_GET['flat_id'])) {
    echo '<div class="alert alert-danger">Flat ID is required</div>';
    exit;
}

$flat_id = $_GET['flat_id'];
$month = isset($_GET['month']) ? $_GET['month'] : null;
$year = isset($_GET['year']) ? $_GET['year'] : null;

// Debug information
// echo "<div style='background: #f8f9fa; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd;'>";
// echo "<h4>Debug Information:</h4>";
// echo "Received Parameters:<br>";
// echo "Flat ID: " . htmlspecialchars($flat_id) . "<br>";
// echo "Month: " . htmlspecialchars($month) . "<br>";
// echo "Year: " . htmlspecialchars($year) . "<br>";

// Convert month name to number
$month_number = date('m', strtotime($month));
// echo "Month Number: " . $month_number . "<br>";
// echo "</div>";

// Fetch all details for this flat
$query = "SELECT * FROM flat_details WHERE building_id = '$flat_id'";

// Add month and year filter if provided
if ($month && $year) {
    $query .= " AND MONTH(date) = '$month_number' AND YEAR(date) = '$year'";
}

$query .= " ORDER BY date DESC";

// Debug information - Show the final query
// echo "<div style='background: #f8f9fa; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd;'>";
// echo "SQL Query:<br>";
// echo htmlspecialchars($query);
// echo "</div>";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-striped">';
    echo '<thead class="table-dark">';
    echo '<tr>
            <th>Name</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Note</th>
          </tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['amount']) . '</td>';
        echo '<td>' . htmlspecialchars($row['date']) . '</td>';
        echo '<td>' . nl2br(htmlspecialchars($row['note'])) . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<div class="alert alert-info">No details found for this flat</div>';
}
?> 