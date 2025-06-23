<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

$tableName = 'form_enquiries';
$queryParams = $_GET;

// For form_enquiries, we don't default to 'status=active'
// Admins might want to see 'new', 'read', or 'responded' enquiries.
// Filtering by status should be explicit via query param e.g. ?status=new

$data = execute_select_query($conn, $tableName, $queryParams);

if ($data === null) {
    // Error already handled by send_json_response in execute_select_query
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
