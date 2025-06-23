<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'category_key_features';
$queryParams = $_GET;

// This table does not have a 'status' column, so active filtering won't apply by default.
// Filtering will be based on query parameters like 'category_id'.
if (!isset($queryParams['category_id'])) {
    // send_json_response(400, ['message' => 'Missing required query parameter: category_id.']);
    // It might be valid to request all key features, though less common.
    // For now, allow fetching all, or specific ones by category_id.
}

$data = execute_select_query($conn, $tableName, $queryParams);

if ($data === null) {
    return;
}

if (empty($data) && !empty($queryParams) && $conn->error == "") {
    send_json_response(404, ['message' => 'No records found matching your criteria.']);
} elseif (empty($data) && $conn->error == "") {
    send_json_response(200, []);
} else {
    send_json_response(200, $data);
}

$conn->close();
?>
