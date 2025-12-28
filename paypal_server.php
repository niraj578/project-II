<?php
require __DIR__ . '/vendor/autoload.php';

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

header('Content-Type: application/json');

// Replace with your sandbox credentials
$clientId = "AY4m0Szd_BM9QgQA9QFRgmvLl8vmM-IHx6NfhOZ0H4BQgK3SfmQe3sZjIMkf-XBvuwGVG4qDjgS80MOE";
$clientSecret = "EO0aNPc2XoHm8Mao0RdrXLnyP1j46ujfXH_HiH_LgSJy8VN6ULt_285hzeZjjWqQfy2YLVKjVv0gKcpT"; // Replace with your actual client secret


$environment = new SandboxEnvironment($clientId, $clientSecret);
$client = new PayPalHttpClient($environment);

function handle_create_order() {
    global $client;

    $request = new OrdersCreateRequest();
    $request->prefer('return=representation');
    $request->body = [
        "intent" => "CAPTURE",
        "purchase_units" => [[
            "amount" => [
                "currency_code" => "USD",
                "value" => "100.00"
            ]
        ]]
    ];

    try {
        $response = $client->execute($request);
        http_response_code($response->statusCode);
        echo json_encode($response->result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to create order.", "details" => $e->getMessage()]);
    }
}

function handle_capture_order() {
    global $client;

    $orderID = $_GET['orderID'];
    $request = new OrdersCaptureRequest($orderID);
    $request->prefer('return=representation');

    try {
        $response = $client->execute($request);
        http_response_code($response->statusCode);
        echo json_encode($response->result);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to capture order.", "details" => $e->getMessage()]);
    }
}

$action = $_GET['action'] ?? '';

if ($action === 'create_order') {
    handle_create_order();
} elseif ($action === 'capture_order') {
    handle_capture_order();
} else {
    http_response_code(404);
    echo json_encode(["error" => "Route not found"]);
}
