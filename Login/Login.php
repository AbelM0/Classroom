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

    if ($dData) {
        if (empty($dData["Email"])) { $response["error"] = "Email is required";
        } else {
            $email = test_input($dData["Email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response["error"] = "Invalid email format"; }
            else {

                if(empty($dData["Password"])){ $response["error"] = "Password is required";
                } else {
                    $user = retrieveData($email);
                    $password = test_input($dData["Password"]);

                    if ($user) {
                        if (password_verify($password, $user['Password'])) {
                            unset($user['Password']);
                            $response["data"] = $user;
                            $response["message"] = "User logged in successfully.";
                        } else {
                            $response["error"] = "Incorrect password.";
                        }
                    } else {
                        $response["error"] = "No user found with this email.";
                    }
                }

            }
        }

    } else {
        $response['data'] = $_POST; // Retrieve POST parameters if JSON decode fails
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




