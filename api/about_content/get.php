<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'about_content';
$queryParams = $_GET;

$data = execute_select_query($conn, $tableName, $queryParams);

if ($data === null) {
    return;
}

// For single record tables, we expect one record or none.
// The 'active' status filter is not applicable here as per schema.
// If filtering is applied and nothing found, or if table is empty.
if (empty($data)) {
    // Check if specific query params led to no results vs table being empty
    if (!empty($queryParams) && $conn->error == "") {
        send_json_response(404, ['message' => 'No record found matching your criteria.']);
    } else if ($conn->error == "") { // No specific filters, table might be empty or record doesn't match implicit filters
        send_json_response(200, []); // Or send a specific message like "No content available"
    }
    // If there was a DB error, it's handled by execute_select_query
} else {
    // For single record tables, typically return the first record if expecting one.
    // If $data is not empty, it contains an array of rows. For single record, take the first.
    send_json_response(200, $data[0]);
}

$conn->close();
?>
