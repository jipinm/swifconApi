<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'service_benefits';
$queryParams = $_GET;

// This table does not have a 'status' column.
// Filtering will be primarily based on 'service_id'.
if (!isset($queryParams['service_id'])) {
    // send_json_response(400, ['message' => 'Missing required query parameter: service_id.']);
    // Allow fetching all benefits, though typically filtered by service_id.
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
