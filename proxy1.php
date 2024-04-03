<?php
// Proxy script to forward requests to a specific Shiprocket API endpoint

$shiprocketBaseUrl = 'https://apiv2.shiprocket.in/v1/external/';
$trackShipmentEndpoint = $shiprocketBaseUrl . 'courier/track/shipment/';

// Extract parameters from the URL
$action = $_GET['action'] ?? '';
$token = $_GET['token'] ?? '';
$shipmentId = $_GET['shipmentId'] ?? '';

// Validate action
if ($action !== 'trackShipment') {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Unsupported action']);
    exit();
}

// Set up the request headers
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token,
];

// Construct the Shiprocket API endpoint
$endpoint = $trackShipmentEndpoint . $shipmentId;

// Make the request to the Shiprocket API
$ch = curl_init($endpoint);

// Set cURL options for GET request
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);

// Forward the Shiprocket API response to the frontend
echo $response;

curl_close($ch);
?>
