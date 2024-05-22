<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

    include "../includes/Database.php";

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Handle GET request
        $response['method'] = 'GET';
        $response['message'] = 'Received a GET request';
        $response['data'] = $_GET; // Retrieve GET parameters 
        $response['error'] = "None";
        
        // $eData = file_get_contents("php://input");
        // $dData = json_decode($eData, true);

        $id = isset($_GET['id']) ? test_input($_GET['id']) : null;

        $response["data"] = retrieveJoinedClasses($id);

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle POST request
        $response['method'] = 'POST';
        $response['message'] = 'Received a POST request';
        $response['error'] = 'None';

    } else {
        // Handle other request methods
        $response['method'] = $_SERVER['REQUEST_METHOD'];
        $response['message'] = 'Request method not supported';
    }
  

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

// Send JSON response
    echo json_encode($response);





