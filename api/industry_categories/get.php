<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'industry_categories';
$queryParams = $_GET;

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
