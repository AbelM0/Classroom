<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

    include "../includes/Database.php";

    $response = array();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Handle GET request
        $response['method'] = 'GET';
        $response['message'] = 'Received a GET request';
        $response['error'] = "None";
        
        // $eData = file_get_contents("php://input");
        // $dData = json_decode($eData, true);

        $classId = isset($_GET['classId']) ? test_input($_GET['classId']) : null;

        if ($classId) {
            $assignments = retrieveClassAssignments($classId);
    
            foreach ($assignments as &$assignment) {
                $unformattedDate = $assignment['uploadDate'];
                $assignment['uploadDate'] = timeAgo($unformattedDate);
            }
    
            $response["assignments"] = $assignments;
        } else {
            $response['error'] = "classId parameter is missing";
        }

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

function timeAgo($datetime) {
    $currentTime = time();
    $eventTime = strtotime($datetime);
    $timeDifference = $currentTime - $eventTime;

    if ($timeDifference < 0) {
        return 'just now';
    }

    $seconds = $timeDifference;
    $minutes = round($timeDifference / 60);
    $hours = round($timeDifference / 3600);
    $days = round($timeDifference / 86400);
    $months = round($timeDifference / 2592000);
    $years = round($timeDifference / 31536000);

    if ($years > 0) {
        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
    } elseif ($months > 0) {
        return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
    } elseif ($days > 0) {
        return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
    } elseif ($hours > 0) {
        return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
    } elseif ($minutes > 0) {
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '') . ' ago';
    } else {
        return $seconds . ' second' . ($seconds > 1 ? 's' : '') . ' ago';
    }
}

// Send JSON response
    echo json_encode($response);





