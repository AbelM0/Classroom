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
      

    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle POST request
        $response['method'] = 'POST';
        $response['message'] = 'Received a POST request';
        $response['error'] = 'None';

        // Check if a file was uploaded without errors
    if (isset($_FILES["file"]) && $_FILES["file"]["error"] == 0) {
      $target_dir = "uploads/";
      $target_file = $target_dir . basename($_FILES["file"]["name"]);
      $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

      // Check if the file is allowed 
      $allowed_types = array("jpg", "jpeg", "png", "gif", "pdf", "txt", "pptx");
      if (!in_array($file_type, $allowed_types)) {
           $response["error"] = "Sorry, only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
      } else {
          // Move the uploaded file to the specified directory
          if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
              // File upload success, now store information in the database
              $fileName = $_FILES["file"]["name"];
              $fileSize = $_FILES["file"]["size"];
              $fileType = $_FILES["file"]["type"];

              // Insert the file information into the database
              $file = insertToFile($fileName, $fileSize, $fileType);
              $response["fileId"] = $file["id"];
              $response["fileName"] = $file["fileName"];
              $response["fileType"] = $file["fileType"];
          } else {
            $response["error"] = "Sorry, there was an error uploading your file.";
          }
      } 

    } else {
      $response["error"] = "No file was uploaded.";
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





