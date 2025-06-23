<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'contact_settings';
// This table is expected to have a single record, no status or complex filtering usually.
$queryParams = $_GET; // Allow query params for potential future use, though unlikely for this table.

$data = execute_select_query($conn, $tableName, $queryParams);

if ($data === null) {
    return;
}

if (empty($data)) {
    if (!empty($queryParams) && $conn->error == "") {
        send_json_response(404, ['message' => 'No record found matching your criteria.']);
    } else if ($conn->error == "") {
        send_json_response(200, []); // Or a specific message like "No contact settings configured."
    }
} else {
    // Return the first (and typically only) record.
    send_json_response(200, $data[0]);
}

$conn->close();
?>
