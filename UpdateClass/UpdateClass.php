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

    $className = "";
    $subject = "";
    $email = "";
    $id = "";
    $description = "";

    if($dData["Updating"] == "Class_name"){
        // Validation and Sanitization
        if (empty($dData["Class_name"])) {
          $errors["Class_name"] = "Class name is required";
        } else {
            $className = test_input($dData["Class_name"]);
      
            if(3 >= strlen($className)) {
                $errors["Class_name"] = "Name must be longer than 3 letters";
            } elseif(strlen($className) > 50){
                $errors["Class_name"] = "Name must be shorter than 15 letters";
            }else {
                if (!preg_match("/^[a-zA-Z\s'.-]+$/", $className)) {
                    $errors["Class_name"] = "Only letters, apostrophes, and hyphens allowed";
                }
            }
        }

        $id = test_input($dData["Id"]);
        $email = test_input($dData["Email"]);

        if (!empty($errors)) {
            $response["error"] = $errors;
        } else {
            updateClassName($id, $className);
            $classes = retrieveCreatedClasses($email);
            $response["data"] =  $classes;
        }

    } elseif ($dData["Updating"] == "Section") {

        // Validation and Sanitization
        if (empty($dData["Section"])) {
          $errors["Section"] = "Section is required";
        } else {
            $section = test_input($dData["Section"]);
        }

        $id = test_input($dData["Id"]);
        $email = test_input($dData["Email"]);

        if (!empty($errors)) {
            $response["error"] = $errors;
        } else {
          updateSection($id, $section);
          $classes = retrieveCreatedClasses($email);
          $response["data"] =  $classes;
        }
    } elseif ($dData["Updating"] == "Description") {
        // Validation and Sanitization
        if (empty($dData["Description"])) {
    
        } else {
          $description = test_input($dData["Description"]);
        }


        $id = test_input($dData["Id"]);
        $email = test_input($dData["Email"]);

        if (!empty($errors)) {
            $response["error"] = $errors;
        } else {
          updateDescription($id, $description);
          $classes = retrieveCreatedClasses($email);
          $response["data"] =  $classes;
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


// Send JSON response
echo json_encode($response);




