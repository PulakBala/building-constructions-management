<?php
include('connection.php'); // Ensure this file establishes a MySQLi connection

$query = $_POST['query'] ?? ''; // Use null coalescing operator to avoid undefined index

// Updated SQL query to include consultant in the WHERE clause
$sql = "SELECT * FROM flats WHERE (flatname LIKE ? OR flatname LIKE ? OR mobile_number LIKE ?)";
$stmt = $conn->prepare($sql);
$queryParam = "%$query%";
$stmt->bind_param('sss', $queryParam, $queryParam, $queryParam); // Bind the parameters
$stmt->execute();

// Fetch the results
$result = $stmt->get_result(); // Get the result set from the prepared statement
$flatData = $result->fetch_all(MYSQLI_ASSOC); // Fetch all results as an associative array

foreach ($flatData as $flat) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($flat['f_flatId']) . "</td>";
    echo "<td>" . htmlspecialchars($flat['flatname']) . "</td>";
    echo "<td>" . htmlspecialchars($flat['flat_number']) . "</td>";
    echo "<td>" . number_format($flat['total_collected'], 2) . "</td>";
    echo "<td>" . number_format($flat['f_due'], 2) . "</td>";
    echo "</tr>";
}
?>