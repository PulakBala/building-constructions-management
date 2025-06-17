<?php
include('connection.php');

if (isset($_GET['main_assets_id'])) {
    $main_assets_id = intval($_GET['main_assets_id']);
    $sql = "SELECT id, asset_name FROM assets WHERE main_assets_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $main_assets_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<li><a class="dropdown-item" href="#" onclick="selectAsset(' . $row['id'] . ', \'' . htmlspecialchars($row['asset_name'], ENT_QUOTES) . '\')">' . 
             htmlspecialchars($row['asset_name']) . '</a></li>';
    }
}
?>
