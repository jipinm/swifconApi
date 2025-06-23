<?php
require_once __DIR__ . '/../../core/functions.php';
require_once __DIR__ . '/../../includes/db_connect.php'; // Defines $conn

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    send_json_response(405, ['error' => 'Method Not Allowed. Only POST requests are accepted.']);
    exit;
}

// Get JSON data from the request body
$jsonInput = file_get_contents('php://input');
$requestData = json_decode($jsonInput, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    send_json_response(400, ['error' => 'Invalid JSON data in request body.']);
    exit;
}

// Validate required fields
$requiredFields = ['name', 'email', 'subject', 'content'];
$missingFields = [];
foreach ($requiredFields as $field) {
    if (empty($requestData[$field])) {
        $missingFields[] = $field;
    }
}

if (!empty($missingFields)) {
    send_json_response(400, ['error' => 'Missing required fields: ' . implode(', ', $missingFields)]);
    exit;
}

// Sanitize and prepare data for insertion
$insertData = [
    'name' => trim($requestData['name']),
    'email' => filter_var(trim($requestData['email']), FILTER_SANITIZE_EMAIL),
    'phone' => isset($requestData['phone']) ? trim($requestData['phone']) : null,
    'subject' => trim($requestData['subject']),
    'content' => trim($requestData['content']),
    // 'status' defaults to 'new' in the database schema
];

// Validate email format
if (!filter_var($insertData['email'], FILTER_VALIDATE_EMAIL)) {
    send_json_response(400, ['error' => 'Invalid email format.']);
    exit;
}


$tableName = 'form_enquiries';
$insertedId = execute_insert_query($conn, $tableName, $insertData);

if ($insertedId) {
    send_json_response(201, ['message' => 'Enquiry submitted successfully.', 'id' => $insertedId]);
} else {
    // Error response is handled by execute_insert_query if $conn->error occurs
    // If it returns false for other reasons (e.g. empty data, though validated above)
    if ($conn->error == "") { // Check if error was already sent
        send_json_response(500, ['error' => 'Failed to submit enquiry. Please try again.']);
    }
}

$conn->close();
?>
