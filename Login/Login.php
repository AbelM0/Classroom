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
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle POST request
    $response['method'] = 'POST';
    $response['message'] = 'Received a POST request';
    $response['error'] = "None";

    // Get the raw POST data
    $eData = file_get_contents("php://input");
    $dData = json_decode($eData, true);

    $error = array();

    if ($dData) {
        if (empty($dData["Email"])) { 
            $error["Email"] = "Email is required";
            $response['error'] = $error;
        } else {
            $email = test_input($dData["Email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error["Email"] = "Invalid email format";
                $response['error'] = $error; }
            else {
                if(empty($dData["Password"])){ 
                    $error["Password"] = "Password is required";
                    $response['error'] = $error;
                } else {
                    $user = retrieveData($email);
                    $password = test_input($dData["Password"]);

                    if ($user) {
                        if (password_verify($password, $user['Password'])) {
                            unset($user['Password']);
                            $response["data"] = $user;
                            $response["message"] = "User logged in successfully.";
                        } else {
                            $error["Password"] = "Incorrect password.";
                            $response['error'] = $error;
                        }
                    } else {
                        $error["Email"] = "No user found with this email.";
                        $response['error'] = $error;
                    }
                }

            }
        }

    } else {
        $response['data'] = $_POST; // Retrieve POST parameters if JSON decode fails
        $response['error'] = $error;
    }
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




