<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PointController extends Controller
{
    public function getStores()
    {        
        // User ID to replace placeholder
        $user_id = '94840704';

        // Endpoint URL with user ID placeholder
        $url = 'https://api.mercadopago.com/users/'.$user_id.'/stores/search';

        // Authorization header
        $authorization_header = 'Authorization: Bearer '.$_ENV['MERCADOPAGO_ACCESS_TOKEN'];
        $test_scope_header = 'x-test-scope: sandbox';

        // Initialize curl
        $curl = curl_init();

        // Set curl options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
                $authorization_header,
                $test_scope_header
            ),
        ));

        // Execute curl request
        $response = curl_exec($curl);

        // Check for errors
        if(curl_error($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        // Close curl
        curl_close($curl);
        $response = json_decode($response);

        // Output response
        return response()->json($response);
    }

    public function index(Request $request)
    {
        // de prueba
        $store_id = '55252196';

        // Endpoint URL
        $url = 'https://api.mercadopago.com/point/integration-api/devices?store_id='.$store_id;

        // Authorization header
        $authorization_header = 'Authorization: Bearer '.$_ENV['MERCADOPAGO_ACCESS_TOKEN'];
        // Test scope header
        $test_scope_header = 'x-test-scope: sandbox';

        // Initialize curl
        $curl = curl_init();

        // Set curl options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => array(
            $authorization_header,
            $test_scope_header,
            ),
        ));

        // Execute curl request
        $response = curl_exec($curl);

        // Check for errors
        if(curl_error($curl)) {
        echo 'Error:' . curl_error($curl);
        }

        // Close curl
        curl_close($curl);

        $response = json_decode($response);

        // Output response
        return response()->json($response);
    }

    public function changeOperationType(){
        // Endpoint URL with device ID placeholder
        $url = 'https://api.mercadopago.com/point/integration-api/devices/PAX_A910__SMARTPOS6046005242';

        // Authorization header
        $authorization_header = 'Authorization: Bearer '.$_ENV['MERCADOPAGO_ACCESS_TOKEN'];
         // Test scope header
         $test_scope_header = 'x-test-scope: sandbox';

        // Payload data for the request
        $data = array(
            'operating_mode' => 'PDV',
        );

        // Initialize curl
        $curl = curl_init();

        // Set curl options
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
            CURLOPT_HTTPHEADER => array(
                $authorization_header,
                $test_scope_header,
            ),
            CURLOPT_POSTFIELDS => json_encode($data),
        ));

        // Execute curl request
        $response = curl_exec($curl);

        // Check for errors
        if(curl_error($curl)) {
            echo 'Error:' . curl_error($curl);
        }

        // Close curl
        curl_close($curl);
        $response = json_decode($response);

        // Output response
        return response()->json($response);
    }

    public function payment(){
        // Endpoint URL with device ID placeholder
$url = 'https://api.mercadopago.com/point/integration-api/devices/PAX_A910__SMARTPOS6046005242/payment-intents';

// Device ID to replace placeholder
$device_id = 'your_device_id_here';

// Authorization header
$authorization_header = 'Authorization: Bearer '.$_ENV['MERCADOPAGO_ACCESS_TOKEN'];

// Content type header
$content_type_header = 'Content-Type: application/json';

// Test scope header
$test_scope_header = 'x-test-scope: sandbox';

// Payload data for the request
$data = array(
    'amount' => 1500,
    'additional_info' => array(
        'external_reference' => 'alguna-referencia-sobre-tu-aplicación',
        'print_on_terminal' => true,
        'ticket_number' => 'S0392JED',
    ),
);

// Initialize curl
$curl = curl_init();

// Set curl options
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => array(
        $authorization_header,
        $content_type_header,
        $test_scope_header,
    ),
    CURLOPT_POSTFIELDS => json_encode($data),
));

// Execute curl request
$response = curl_exec($curl);

// Check for errors
if(curl_error($curl)) {
    echo 'Error:' . curl_error($curl);
}

// Close curl
curl_close($curl);

// Output response
echo $response;
    }
}
