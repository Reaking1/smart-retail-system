<?php
/**
 * HTTP Client for JSON-based REST API
 */

function httpRequest($url, $method = 'GET', $data = [], $headers = []) {
    $ch = curl_init(); 

    // Set URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Set HTTP method
    $method = strtoupper($method);
    switch ($method) {
        case 'POST':
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case 'PUT':
        case 'DELETE':
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case 'GET':
        default:
            if (!empty($data)) {
                $url .= '?' . http_build_query($data);
                curl_setopt($ch, CURLOPT_URL, $url);
            }
            break;
    }

    // Ensure JSON headers
    $defaultHeaders = ["Content-Type: application/json", "Accept: application/json"];
    $allHeaders = array_merge($defaultHeaders, $headers);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $allHeaders);

    // Execute request
    $response = curl_exec($ch);

    // Handle errors
    if (curl_errno($ch)) {
        $error_msg = curl_error($ch);
        curl_close($ch);
        return ['success' => false, 'error' => $error_msg];
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Decode JSON response automatically
    $decoded = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $decoded = $response; // fallback: raw response if not JSON
    }

    return [
        'success' => true,
        'status_code' => $http_code,
        'response' => $decoded
    ];
}
?>
