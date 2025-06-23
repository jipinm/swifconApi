<?php

require_once __DIR__ . '/../includes/db_connect.php';

/**
 * Sends a JSON response with a specific HTTP status code.
 *
 * @param int $statusCode HTTP status code.
 * @param mixed $data Data to be encoded as JSON.
 */
function send_json_response($statusCode, $data) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

/**
 * Executes a SELECT query and returns the results.
 * Filters by 'status' = 'active' if the column exists and no other status is specified.
 * Allows additional filtering based on query parameters.
 *
 * @param mysqli $conn The database connection object.
 * @param string $tableName The name of the table to query.
 * @param array $queryParams Associative array of query parameters for filtering.
 * @return array The fetched data as an associative array.
 */
function execute_select_query(mysqli $conn, $tableName, $queryParams = []) {
    $sql = "SELECT * FROM `$tableName`";
    $whereClauses = [];
    $paramTypes = '';
    $paramValues = [];
    $tableColumns = [];

    // Get table column details
    $result = $conn->query("DESCRIBE `$tableName`");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $tableColumns[$row['Field']] = $row;
        }
    } else {
        send_json_response(500, ['error' => "Failed to describe table: " . $conn->error]);
        return [];
    }

    $hasStatusColumn = isset($tableColumns['status']);
    $hasIsVisibleColumn = isset($tableColumns['is_visible']);

    // Default filtering logic
    $statusParamProvided = array_key_exists('status', $queryParams);
    $isVisibleParamProvided = array_key_exists('is_visible', $queryParams);

    if ($hasStatusColumn && !$statusParamProvided) {
        // If 'status' column exists and no 'status' param is given, default to 'active'.
        $whereClauses[] = "status = ?";
        $paramTypes .= 's';
        $paramValues[] = 'active';

        // If 'status' defaults to 'active', and 'is_visible' also exists but is not provided,
        // we might also want to default 'is_visible' to 1.
        // This assumes 'active' items should also be 'visible' by default if both columns are present.
        if ($hasIsVisibleColumn && !$isVisibleParamProvided) {
            $whereClauses[] = "is_visible = ?";
            $paramTypes .= 'i';
            $paramValues[] = 1;
        }
    } elseif ($hasIsVisibleColumn && !$isVisibleParamProvided) {
        // If 'status' column does not exist (or a 'status' param WAS provided),
        // AND 'is_visible' column exists and no 'is_visible' param is given, default to 'is_visible = 1'.
        $whereClauses[] = "is_visible = ?";
        $paramTypes .= 'i';
        $paramValues[] = 1;
    }

    // Add query parameters to the WHERE clause
    foreach ($queryParams as $key => $value) {
        // Ensure the column exists in the table to prevent errors and potential SQL injection via column names
        if (!isset($tableColumns[$key])) {
            // Optionally, log or send a notice about an invalid filter parameter
            // For now, we'll just skip it to avoid query errors.
            // send_json_response(400, ['error' => "Invalid filter parameter: $key"]);
            // return [];
            continue;
        }

        $sanitizedKey = $conn->real_escape_string($key); // Key is now validated against table columns

        // For 'is_visible', ensure value is boolean-like (0 or 1) if it's a boolean column type
        if ($key === 'is_visible' && strtolower($tableColumns[$key]['Type']) === 'tinyint(1)') {
            $whereClauses[] = "`$sanitizedKey` = ?";
            $paramTypes .= 'i';
            $paramValues[] = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0;
        } else {
            $whereClauses[] = "`$sanitizedKey` = ?";
            // Determine param type based on column type (simplified)
            if (strpos(strtolower($tableColumns[$key]['Type']), 'int') !== false) {
                $paramTypes .= 'i';
            } else if (strpos(strtolower($tableColumns[$key]['Type']), 'double') !== false || strpos(strtolower($tableColumns[$key]['Type']), 'float') !== false || strpos(strtolower($tableColumns[$key]['Type']), 'decimal') !== false) {
                $paramTypes .= 'd';
            } else {
                $paramTypes .= 's';
            }
            $paramValues[] = $value;
        }
    }

    if (!empty($whereClauses)) {
        $sql .= " WHERE " . implode(" AND ", $whereClauses);
    }

    // Add sort_order if the column exists
    $tableHasSortOrderColumn = false;
    $result = $conn->query("DESCRIBE `$tableName`");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Field'] === 'sort_order') {
                $tableHasSortOrderColumn = true;
                break;
            }
        }
    }
    if ($tableHasSortOrderColumn) {
        $sql .= " ORDER BY sort_order ASC";
    }


    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        send_json_response(500, ['error' => "Failed to prepare statement: " . $conn->error, 'sql' => $sql]);
        return [];
    }

    if (!empty($paramValues)) {
        $stmt->bind_param($paramTypes, ...$paramValues);
    }

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    } else {
        send_json_response(500, ['error' => "Failed to execute statement: " . $stmt->error, 'sql' => $sql]);
        $stmt->close();
        return [];
    }
}

/**
 * Executes an INSERT query.
 *
 * @param mysqli $conn The database connection object.
 * @param string $tableName The name of the table to insert into.
 * @param array $data Associative array of data to insert (column_name => value).
 * @return int|false The ID of the inserted row or false on failure.
 */
function execute_insert_query(mysqli $conn, $tableName, $data) {
    if (empty($data)) {
        return false;
    }

    $columns = array_keys($data);
    $placeholders = array_fill(0, count($columns), '?');
    $paramTypes = str_repeat('s', count($columns)); // Assuming all values are strings for simplicity

    $sql = "INSERT INTO `$tableName` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $placeholders) . ")";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        send_json_response(500, ['error' => "Failed to prepare statement for insert: " . $conn->error, 'sql' => $sql]);
        return false;
    }

    $stmt->bind_param($paramTypes, ...array_values($data));

    if ($stmt->execute()) {
        $insertedId = $stmt->insert_id;
        $stmt->close();
        return $insertedId;
    } else {
        send_json_response(500, ['error' => "Failed to execute insert statement: " . $stmt->error, 'sql' => $sql, 'data' => $data]);
        $stmt->close();
        return false;
    }
}

?>
