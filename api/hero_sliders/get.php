<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'hero_sliders';
$queryParams = $_GET; // Use all query parameters for filtering

$data = execute_select_query($conn, $tableName, $queryParams);

if ($data === null) {
    // Error already handled by send_json_response in execute_select_query
    return;
}

if (empty($data) && !empty($queryParams) && $conn->error == "") {
    send_json_response(404, ['message' => 'No records found matching your criteria.']);
} elseif (empty($data) && $conn->error == "") {
    send_json_response(200, []); // Send empty array if no active records but query was successful
} else {
    send_json_response(200, $data);
}

$conn->close();
?>
