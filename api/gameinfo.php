<?php
// Allow both GET (to view) and POST (to update)
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // allow frontend to read it
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Path to store game info
$file = __DIR__ . '/gameinfo.json';

// Handle POST request from Roblox
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    if ($data && isset($data['gameId'])) {
        // Save JSON data to file
        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode(['status' => 'success', 'message' => 'Game info updated']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
    exit;
}

// Handle GET request (for frontend)
if (file_exists($file)) {
    echo file_get_contents($file);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data yet']);
}
?>
