<?php
include 'connection.php'; // connection include korun

if (isset($_POST['asset_id'])) {
    $asset_id = intval($_POST['asset_id']);

    // মূল asset সহ তার children (main_asset_id অথবা parent_asset_id দিয়ে)
    $query = "SELECT id, parent_asset_id, asset_value, date, created_at 
              FROM assets 
              WHERE id = $asset_id 
                 OR main_assets_id = $asset_id 
                 OR parent_asset_id = $asset_id";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table class='table table-bordered'>";
        echo "<thead><tr><th>Value</th><th>Date</th><th>Created At</th></tr></thead>";
        echo "<tbody>";
        $total_value = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            // echo "<td>" . htmlspecialchars($row['parent_asset_id'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['asset_value'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['date'] ?? '') . "</td>";
            echo "<td>" . htmlspecialchars($row['created_at'] ?? '') . "</td>";
            echo "</tr>";
            $total_value += floatval($row['asset_value'] ?? 0);
        }
        echo "</tbody>";
        echo "</table>";
        
        // Display total calculation
        echo "<div class='mt-3'>";
        echo "<strong>Total Value: </strong>" . number_format($total_value, 2);
        echo "</div>";
    } else {
        echo "<p>No values found for this asset.</p>";
    }
}
?>

