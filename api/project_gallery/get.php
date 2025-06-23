<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'project_gallery';
$queryParams = $_GET;

// This table does not have a 'status' column.
// Filtering will be primarily based on 'project_id'.
if (!isset($queryParams['project_id'])) {
    // send_json_response(400, ['message' => 'Missing required query parameter: project_id.']);
    // Allow fetching all gallery images, though typically filtered by project_id.
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
