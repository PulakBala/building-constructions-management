<?php
function getFlatBillSummary($month, $year, $searchQuery = '') {
    global $conn; // Assuming $conn is your database connection
    $sql = "SELECT * FROM flats WHERE month = ? AND year = ?";

    // Add search condition if a search query is provided
    if (!empty($searchQuery)) {
        // Check if the search query is numeric (for flat number) or not (for flat name)
        if (is_numeric($searchQuery)) {
            $sql .= " AND flat_number LIKE ?";
        } else {
            $sql .= " AND flatname LIKE ?";
        }
        $stmt = $conn->prepare($sql);
        $searchParam = "%" . $searchQuery . "%";
        $stmt->bind_param("ss", $month, $year, $searchParam);
    } else {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $month, $year);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}
?>