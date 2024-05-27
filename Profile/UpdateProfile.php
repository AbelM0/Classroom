<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header('Access-Control-Allow-Methods: PATCH');

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

} elseif ($_SERVER['REQUEST_METHOD'] == 'PATCH') {
    // Handle PATCH request
    $response['method'] = 'PATCH';
    $response['message'] = 'Received a PATCH request';
    $response['error'] = "None";

    // Get the raw PATCH data
    $eData = file_get_contents("php://input");
    $dData = json_decode($eData, true);

    $errors = array();

    $firstName = "";
    $lastName = "";
    $email = "";
    $currentPassword = "";
    $newPassword="";

    if($dData["Updating"] == "First_name"){
        // Validation and Sanitization
        if (empty($dData["First_name"])) {
            $errors["First_name"] = "First name Cannot be empty";
        } else {
            $firstName = test_input($dData["First_name"]);

            if(3 >= strlen($firstName)) {
                $errors["First_name"] = "Name must be longer than 3 letters";
            } elseif(strlen($firstName) > 15){
                $errors["First_name"] = "Name must be shorter than 15 letters";
            }else {
                if (!preg_match("/^[a-zA-Z'-]+$/", $firstName)) {
                    $errors["First_name"] = "Only letters, apostrophes, and hyphens allowed";
                }
            }

        }

        $email = test_input($dData["Email"]);

        if (!empty($errors)) {
            $response["error"] = $errors;
        } else {
            updateFirstName($email, $firstName);
            $user = retrieveData($email);
            unset($user['Password']);
            $response["data"] =  $user;
        }

    } elseif ($dData["Updating"] == "Last_name") {

        // Validation and Sanitization
        if (empty($dData["Last_name"])) {

        } else {
            $lastName = test_input($dData["Last_name"]);

            if(3 >= strlen($lastName)) {
                $errors["Last_name"] = "Name must be longer than 3 letters";
            } elseif(strlen($lastName) > 15){
                $errors["Last_name"] = "Name must be shorter than 15 letters";
            }else {
                if (!preg_match("/^[a-zA-Z'-]+$/", $lastName)) {
                    $errors["Last_name"] = "Only letters, apostrophes, and hyphens allowed";
                }
            }

        }

        $email = test_input($dData["Email"]);

        if (!empty($errors)) {
            $response["error"] = $errors;
        } else {
            updateLastName($email, $lastName);
            $user = retrieveData($email);
            unset($user['Password']);
            $response["data"] =  $user;
        }
    } elseif ($dData["Updating"] == "Password") {
        // Validation and Sanitization
        if (empty($dData["Current_password"])) {
            $errors["Current_password"] = "Password is required";
        } else {
            $currentPassword = test_input($dData["Current_password"]);
        }

        if (empty($dData["New_password"])) {
            $errors["New_password"] = "Password is required";
        } else {
            $newPassword = test_input($dData["New_password"]);
            $passwordErrors = validatePassword($newPassword);
            if (!empty($passwordErrors)) {
                $errors["New_password"] = $passwordErrors;
            }
        }

        $email = test_input($dData["Email"]);

        $user = retrieveData($email);

        if (!empty($errors)) {
            $response["error"] = $errors;
        } else {
            if (password_verify($currentPassword, $user['Password'])) {
                updatePassword($email, $newPassword);
                $user = retrieveData($email);
                unset($user['Password']);
                $response["data"] =  $user;
            } else {
                $errors["Current_password"] = "Incorrect password.";
                $response["error"] = $errors;
            }
        }

    }


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

function validatePassword($password)
{
    $errors = array();

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Password must contain at least one uppercase letter.";
    }

    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Password must contain at least one lowercase letter.";
    }

    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Password must contain at least one number.";
    }

    if (!preg_match('/[\W]/', $password)) { // \W matches any non-word character
        $errors[] = "Password must contain at least one special character.";
    }

    return $errors;
}

// Send JSON response
echo json_encode($response);




