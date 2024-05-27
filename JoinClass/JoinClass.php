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

            $classCode = "";
            $ownerEmail = "";
            $email = "";
            

            if (empty($dData["Class_code"])) {
                $errors["Class_code"] = "Class code is required";
            } else {
                $classCode = test_input($dData["Class_code"]);

            }

            if (empty($dData["Owner_email"])) {
              $errors["Owner_email"] = "Email is required";
            } else {
                $ownerEmail = test_input($dData["Owner_email"]);
                if (!filter_var($ownerEmail, FILTER_VALIDATE_EMAIL)) {
                    $errors["Owner_email"] = "Invalid email format";
                }
            }

            if (!empty($errors)) {
                $response["error"] = $errors;
            } else {
              $email = test_input($dData["Email"]);
              if($email == $ownerEmail){
                $error["Owner_email"] =  "You cannot join your own class.";
                $response["error"] = $error;
              } else {
                $user = retrieveData($email);
                $userId = $user["id"];
                $class = retrieveJoinedCreatedClass($ownerEmail, $classCode);

                if($class){
                  $classId = $class["id"];

                  $joinedClasses = retrieveJoinedClasses($userId);
                  $alreadyJoined = false;  

                  foreach( $joinedClasses as $joinedClass ){
                    $id = $joinedClass["id"];
                    if($classId == $id){
                        $alreadyJoined = true;
                        break;
                    }
                  }

                  if($alreadyJoined){
                    $error["Class_code"] = "You already have joined this class.";
                    $response["error"] = $error;
                  } else {
                    try {
                        insertToClassuser($userId, $classId);
                        $response["message"] = "Joined class successfully";    
                        $response["data"] = retrieveJoinedClasses($userId);
                    } catch (PDOException $e) {
                        $response["error"] = "Database error: " . $e->getMessage();
                    }
                  }

                  
                } else {
                  $response["error"] = "Invalid Class Code or Owners Email";
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





