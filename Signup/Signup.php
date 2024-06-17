<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

    include "../includes/Database.php";
    include "../Email/sendWelcomeEmail.php";

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
        $response['error'] = 'None';

        // Get the raw POST data
        $eData = file_get_contents("php://input");
        $dData = json_decode($eData, true);

        if ($dData) {
            $response['data'] = $dData;

            $errors = array();

            $firstName = "";
            $lastName = "";
            $email = "";
            $password = "";

            if (empty($dData["First_name"])) {
                $errors["First_name"] = "First name is required";
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

            if (empty($dData["Last_name"])) {

            } else {
                $lastName = test_input($dData["Last_name"]);
                if(3 >=  strlen($lastName)) {
                    $errors["Last_name"] = "Name must be longer than 3 letters";
                } elseif(strlen($lastName) > 15){
                    $errors["Last_name"] = "Name must be shorter than 15 letters";
                }else {
                    if (!preg_match("/^[a-zA-Z'-]+$/", $lastName)) {
                        $errors["Last_name"] = "Only letters, apostrophes, and hyphens allowed";
                    }
                }
            }

            if (empty($dData["Email"])) {
                $errors["Email"] = "Email is required";
            } else {
                $email = test_input($dData["Email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors["Email"] = "Invalid email format";
                }
            }

            if (empty($dData["Password"])) {
                $errors["Password"] = "Password is required";
            } else {
                $password = test_input($dData["Password"]);
                $passwordErrors = validatePassword($password);
                if (!empty($passwordErrors)) {
                    $errors["Password"] = $passwordErrors;
                }

            }

            if (!empty($errors)) {
                $response["error"] = $errors;
            } else {
                $user = retrieveData($email);

                if ( $user ) {
                    $errors["Email"] = 'User with this email already exists.';
                    $response['error'] = $errors;
                } else {
                    try {
                        insertUser($firstName, $email, $lastName, $password);
                        $user = retrieveData($email);
                        unset($user['Password']);
                        $response["data"] = $user;
                        $response["message"] = "User registered successfully";
                        sendWelcomeEmailTo($email, $firstName);
                    } catch (PDOException $e) {
                        $response["error"] = "Database error: " . $e->getMessage();
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


function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data);
}

// Send JSON response
    echo json_encode($response);





