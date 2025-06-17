<?php
include_once('connection.php');

// default to current if no override
$currentMonth = date('F');
$currentYear = date('Y');

// override if passed via GET or POST
if (isset($_REQUEST['month'], $_REQUEST['year'])) {
    $currentMonth = $_REQUEST['month'];
    $currentYear = (int) $_REQUEST['year'];
}

// compute previous month from the *selected* month/year
$previousMonth = date(
  'F',
  strtotime("first day of -1 month", strtotime("{$currentMonth} 1 {$currentYear}"))
);

// now fetch
$stmt = $conn->prepare(
  "SELECT fb.f_due_flat, fb.f_date, fb.f_year, fb.f_month, f.owner_name
     FROM flat_bill fb
     JOIN flats f ON f.id = fb.f_flatId
    WHERE fb.f_due_flat > 0
      AND fb.f_month = ?
      AND fb.f_year  = ?
    ORDER BY fb.f_date DESC"
);
$stmt->bind_param('si', $previousMonth, $currentYear);
$stmt->execute();
$result = $stmt->get_result();

$html = '';
if ($result->num_rows > 0) {
    $html .= '<table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Owner Name</th>
                        <th>Due Flat</th>
                        <th>Year</th>
                        <th>Month</th>
                    </tr>
                </thead>
                <tbody>';
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($row['owner_name']) . '</td>
                    <td>' . htmlspecialchars($row['f_due_flat']) . '</td>
                    <td>' . htmlspecialchars($row['f_year']) . '</td>
                    <td>' . htmlspecialchars($row['f_month']) . '</td>
                  </tr>';
    }
    $html .= '</tbody></table>';
} else {
    $html = '<p>No due flat records found for ' . htmlspecialchars($previousMonth) . '</p>';
}

echo $html;
?>