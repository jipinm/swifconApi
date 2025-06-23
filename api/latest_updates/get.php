<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'latest_updates';
$queryParams = $_GET;

// Schema uses 'is_visible' (BOOLEAN) instead of 'status' (ENUM)
// Adjusting core/functions.php to handle 'is_visible' might be better,
// but for now, let's handle it directly here or assume 'is_visible' = 1 (true) is default.
// For simplicity, if 'is_visible' is not in queryParams, we can add it.
if (!isset($queryParams['is_visible'])) {
    // $queryParams['is_visible'] = '1'; // Filter by visible updates by default
    // Or let execute_select_query handle it if it's adapted for boolean.
    // Current execute_select_query treats all params as strings, which is fine for '1' or '0'.
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
