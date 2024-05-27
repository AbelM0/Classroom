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
        $response['error'] = 'None';

        // Get the raw POST data
        $eData = file_get_contents("php://input");
        $dData = json_decode($eData, true);

        if ($dData) {
            $errors = array();

            $className = "";
            $section = "";
            $ownerEmail = "";
            $subject = "";
            $description = "";
            $classCode = "";


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

            if (empty($dData["Section"])) {
              $errors["Section"] = "Section is required";
            } else {
                $section = test_input($dData["Section"]);
            }


            if (empty($dData["Subject"])) {
              $errors["Subject"] = "Subject is required";
            } else {
                $subject = test_input($dData["Subject"]);
                }

            if (empty($dData["Description"])) {
              
            } else {
              $description = test_input($dData["Description"]);
            }


            if (!empty($errors)) {
                $response["error"] = $errors;
            } else {
              $ownerEmail = test_input($dData["Owner_email"]);
              $classCode = generateRandomString();
              try {
                  insertClass($className, $section, $ownerEmail, $subject, $description, $classCode);
                  $response["message"] = "Class registered successfully";    
                  $response["data"] = retrieveCreatedClasses($ownerEmail);
              } catch (PDOException $e) {
                  $response["error"] = "Database error: " . $e->getMessage();
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
function generateRandomString($length = 10) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  return substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
}

// Send JSON response
    echo json_encode($response);





