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
  "SELECT fb.f_due_flat, fb.f_due_note, fb.f_date, fb.f_year, fb.f_month, fb.f_flatId, f.owner_name
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
                        <th>Flat Due Amount</th>
                        <th>Flat Due Note</th>
                        <th>Year</th>
                        <th>Month</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($row['owner_name']) . '</td>
                    <td>' . htmlspecialchars($row['f_due_flat']) . '</td>
                    <td>' . htmlspecialchars($row['f_due_note']) . '</td>
                    <td>' . htmlspecialchars($row['f_year']) . '</td>
                    <td>' . htmlspecialchars($row['f_month']) . '</td>
                    <td>
                        <form class="update-due-form" onsubmit="return updateDueAmount(event, ' . $row['f_flatId'] . ')">
                            <input type="hidden" name="id" value="' . $row['f_flatId'] . '">
                            <div class="mb-2">
                                <input type="number" class="form-control form-control-sm" 
                                       name="paid_amount" placeholder="Enter amount" 
                                       max="' . $row['f_due_flat'] . '" required>
                            </div>
                            <div class="mb-2">
                                <input type="text" class="form-control form-control-sm" 
                                       name="due_note" placeholder="Enter flat due note" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                    </td>
                  </tr>';
    }
    $html .= '</tbody></table>';
} else {
    $html = '<p>No due flat records found for ' . htmlspecialchars($previousMonth) . '</p>';
}

echo $html;