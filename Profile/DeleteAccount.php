<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Methods: DELETE');

include "../includes/Database.php";

$response = array();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle GET request
    $response['method'] = 'GET';
    $response['message'] = 'Received a GET request';
    $response['data'] = $_GET; // Retrieve GET parameters
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request
    $response['method'] = 'POST';
    $response['message'] = 'Received a POST request';
    $response['error'] = "None";

    // Get the raw POST data
    $eData = file_get_contents("php://input");
    $dData = json_decode($eData, true);

} elseif($_SERVER['REQUEST_METHOD'] === 'DELETE'){

    $response['method'] = 'DELETE';
    $response['message'] = 'Received a DELETE request';
    $response['error'] = "None";

    $eData = file_get_contents("php://input");
    $dData = json_decode($eData, true);

    $email = test_input($dData["email"]);

    $response['data'] = deleteRecord($email);

} else {
    // Handle other request methods
    $response['method'] = $_SERVER['REQUEST_METHOD'];
    $response['message'] = 'Request method not supported';
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

// Send JSON response
echo json_encode($response);




