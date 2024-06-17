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

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle POST request
        $response['method'] = 'POST';
        $response['message'] = 'Received a POST request';
        $response['error'] = 'None';

        $eData = file_get_contents("php://input");
        $dData = json_decode($eData, true);

        $message = test_input($dData["message"]);
        $fileId = test_input($dData["fileId"]);
        $fileName = test_input($dData["fileName"]);
        $fileType = test_input($dData["fileType"]);
        $classId = test_input($dData["classId"]);
        $email = test_input($dData["Email"]);
        $submissionDate = test_input($dData["submissionDate"]);

        insertToAssignments($message, $fileId, $fileName, $fileType, $classId, $email, $submissionDate) ;
        $response["assignments"] = retrieveClassAssignments($classId);
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





