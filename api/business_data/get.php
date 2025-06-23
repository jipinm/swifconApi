<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'business_data';
// This table is expected to have a single record.
// No 'status' or 'is_visible' column. Filtering is unlikely.
$queryParams = $_GET;

$data = execute_select_query($conn, $tableName, $queryParams);

if ($data === null) {
    return;
}

if (empty($data)) {
    if (!empty($queryParams) && $conn->error == "") {
        send_json_response(404, ['message' => 'No record found matching your criteria.']);
    } else if ($conn->error == "") {
        send_json_response(200, (object)[]); // Send empty object for single record not found
    }
} else {
    // Return the first (and typically only) record.
    send_json_response(200, $data[0]);
}

$conn->close();
?>
