<?php
// Proxy script to forward requests to Shiprocket API

$shiprocketBaseUrl = 'https://apiv2.shiprocket.in/v1/external/';
$loginEndpoint = $shiprocketBaseUrl . 'auth/login';
$orderEndpoint = $shiprocketBaseUrl . 'orders/create/adhoc';
$AWBAssignmentEndpoint = $shiprocketBaseUrl . 'courier/assign/awb';
$PickUPRequestEndpoint = $shiprocketBaseUrl . 'courier/generate/pickup';
$shipmentTrackingEndpoint = $shiprocketBaseUrl . 'courier/track/shipment/'; // Fix the typo here
$shipmentcancelEndpoint = $shiprocketBaseUrl . 'orders/cancel'; 
$AddPickupAddressEndpoint = $shiprocketBaseUrl . 'settings/company/addpickup'; 




// Get the request body from the frontend
$requestData = json_decode(file_get_contents('php://input'));
error_log("Request received: " . json_encode($requestData));
// Determine the endpoint based on the request
$endpoint = '';

switch ($requestData->action) {
    case 'login':
        $endpoint = $loginEndpoint;
        break;
    case 'createOrder':
        $endpoint = $orderEndpoint;
        break;
    case 'assignAWB':
        $endpoint = $AWBAssignmentEndpoint;
        break;
    case 'generatePickup':
        $endpoint = $PickUPRequestEndpoint;
        break;
    case 'trackShipment':
        $endpoint = $shipmentTrackingEndpoint . $requestData->shipmentId;
        break;
    case 'cancelorder':
        $endpoint = $shipmentcancelEndpoint;
        break;
    case 'addPickupAddress':
        $endpoint = $AddPickupAddressEndpoint;
        break;
    // Add more cases as needed for other actions

    default:
        // Handle unsupported actions or set a default endpoint
        break;
}

$token = $requestData->token;

// Set up the request headers
$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token,
];

// Make the request to the Shiprocket API
$ch = curl_init($endpoint);

if ($requestData->action === 'trackShipment') {
    // If it's a GET request for tracking shipment, set cURL options for GET
   
    // Force it to be a GET request
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
} 
else {
    // For other actions, it's a POST request
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData->data));
}




// Set common cURL options
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// $response = curl_exec($ch);

// // Forward the Shiprocket API response to the frontend
// echo $response;

// curl_close($ch);
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    // If cURL request fails, handle the error
    $errorMessage = curl_error($ch);
    // You can log the error or return an error response to the frontend
    // For example:
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Failed to execute cURL request: ' . $errorMessage]);
} else {
    // Forward the Shiprocket API response to the frontend
    echo $response;
}

curl_close($ch);

?>
