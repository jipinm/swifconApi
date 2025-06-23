<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'latest_news';
$queryParams = $_GET;

// Similar to latest_updates, this table uses 'is_visible'
if (!isset($queryParams['is_visible'])) {
    // $queryParams['is_visible'] = '1'; // Filter by visible news by default
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
