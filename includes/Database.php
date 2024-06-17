<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=".$servername,
        $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    function createDB($conn){
        $sql = "CREATE DATABASE classroom";

        $conn->exec($sql);
    }

    function useDB($conn, $DB){
        $conn->query("use $DB");
    }

    function createUserTable($conn){
        $sql = "CREATE TABLE users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            First_name VARCHAR(30) NOT NULL,
            Last_name VARCHAR(30) NOT NULL,
            Email VARCHAR(50),
            Password VARCHAR(255)
            )";

        $conn->exec($sql);
    }
    function createClassTable($conn){
        $sql = "CREATE TABLE class (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            Class_name VARCHAR(50) NOT NULL,
            Section VARCHAR(30) NOT NULL,
            Subject VARCHAR(50),
            Description VARCHAR(255),
            Owner_email VARCHAR(50),
            Class_code VARCHAR(50)
            )";

        $conn->exec($sql);
    }
    function createClassUserTable($conn){
        $sql = "CREATE TABLE classUser (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            userId VARCHAR(30) NOT NULL,
            classId VARCHAR(30) NOT NULL
            )";

        $conn->exec($sql);
    }

    function createAnnouncementTable($conn){
        $sql = "CREATE TABLE Announcement (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            message VARCHAR(255),
            classId VARCHAR(30) NOT NULL,
            fileId VARCHAR(30),
            announcerEmail VARCHAR(50) NOT NULL,
            fileName VARCHAR(200) NOT NULL,
            fileType VARCHAR(100) NOT NULL,
            uploadDate TIMESTAMP NOT NULL
            )";

        $conn->exec($sql);
    }

    function createFileTable($conn){
        $sql = "CREATE TABLE Files (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            fileName VARCHAR(200) NOT NULL,
            fileSize INT(11) NOT NULL,
            fileType VARCHAR(100) NOT NULL,
            uploadDate TIMESTAMP NOT NULL
            )";

        $conn->exec($sql);
    }

    function createAssignmentTable($conn){
        $sql = "CREATE TABLE assignment (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            message VARCHAR(255),
            classId VARCHAR(30) NOT NULL,
            fileId VARCHAR(30),
            Email VARCHAR(50) NOT NULL,
            fileName VARCHAR(200) NOT NULL,
            fileType VARCHAR(100) NOT NULL,
            submissionDate VARCHAR(100) NOT NULL,
            uploadDate TIMESTAMP NOT NULL
            )";

        $conn->exec($sql);
    }
    function createAssignmentSubmissionsTable($conn){
        $sql = "CREATE TABLE assignmentSubmissions (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            studentId VARCHAR(30) NOT NULL,
            assignmentId VARCHAR(30) NOT NULL,
            submissionDate VARCHAR(100) NOT NULL,
            submission VARCHAR(100) NOT NULL,
            grade VARCHAR(100) NOT NULL,
            uploadDate TIMESTAMP NOT NULL
            )";

        $conn->exec($sql);
    }

    function createTestTable($conn){
        $sql = "CREATE TABLE test (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            classId VARCHAR(30) NOT NULL,
            testName VARCHAR(30) NOT NULL,
            testDuration VARCHAR(30) NOT NULL,
            testStartTime VARCHAR(30) NOT NULL
            )";

        $conn->exec($sql);
    }
    function createTestSubmissionsTable($conn){
        $sql = "CREATE TABLE testSubmissions (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            studentId VARCHAR(30) NOT NULL,
            testId VARCHAR(30) NOT NULL,
            score VARCHAR(30) NOT NULL
            )";

        $conn->exec($sql);
    }
    function createQuestionsTable($conn){
        $sql = "CREATE TABLE question (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            testId VARCHAR(30) NOT NULL,
            questionText VARCHAR(230) NOT NULL,
            answerId VARCHAR(30) NOT NULL
            )";

        $conn->exec($sql);
    }
    function createAnswersTable($conn){
        $sql = "CREATE TABLE answer (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            questionId VARCHAR(30) NOT NULL,
            answerText VARCHAR(230) NOT NULL,
            correct TINYINT(1) NOT NULL
            )";

        $conn->exec($sql);
    }


    function insertUser($firstName, $email, $lastName, $password){

        global $conn;
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare an SQL statement with placeholders
        $sql = "INSERT INTO users (First_name, Last_name, Email, Password) 
                VALUES (:firstName, :lastName, :email, :password)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the statement
        $stmt->execute();

    }

    function insertClass($className, $section, $ownerEmail, $subject, $description, $classCode){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "INSERT INTO class (Class_name, Section, Subject, Description, Owner_email, Class_code) 
                VALUES (:className, :section, :subject, :description, :ownerEmail, :classCode)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':className', $className);
        $stmt->bindParam(':section', $section);
        $stmt->bindParam(':subject', $subject);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':ownerEmail', $ownerEmail);
        $stmt->bindParam(':classCode', $classCode);

        // Execute the statement
        $stmt->execute();
    }

    function retrieveData($email){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "SELECT * FROM users WHERE Email = :email";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function deleteRecord($email){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "DELETE FROM users WHERE Email = :email";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateFirstName($email, $firstName){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "UPDATE users SET First_name = :firstName WHERE Email = :email";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':firstName', $firstName);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function updateClassName($id, $className){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "UPDATE class SET Class_name = :className WHERE id = :id";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':className', $className);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function updateSection($id, $section){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "UPDATE class SET Section = :section WHERE id = :id";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':section', $section);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    function updateDescription($id, $description){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "UPDATE class SET Description = :description WHERE id = :id";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':description', $description);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updateLastName($email, $lastName){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "UPDATE users SET Last_name = :lastName WHERE Email = :email";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':lastName', $lastName);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function updatePassword($email, $password){
        global $conn;
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare an SQL statement with placeholders
        $sql = "UPDATE users SET Password = :password WHERE Email = :email";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    function retrieveCreatedClasses($email){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "SELECT * FROM class  WHERE Owner_email = :email";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function retrieveClassesWithName($className){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "SELECT * FROM class  WHERE Class_name = :className";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':className', $className);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function retrieveCreatedClassesWithClassName($className){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "SELECT * FROM class  WHERE Class_name = :className";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':className', $className);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function retrieveCreatedClassesWithId($classId){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "SELECT * FROM class  WHERE id = :classId";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':classId', $classId);

        $stmt->execute();

        // Fetch user data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function retrieveJoinedClasses($userId){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "SELECT classId FROM classuser  WHERE userId = :userId";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter to the placeholder
        $stmt->bindParam(':userId', $userId);

        $stmt->execute();

        $joinedClassesId = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $joinedClasses = array();

        foreach( $joinedClassesId as $joinedClassId) {
            $joinedClass = retrieveCreatedClassesWithId($joinedClassId['classId']);
            unset($joinedClass['Class_code']);
            array_push( $joinedClasses, $joinedClass);
        }

        return $joinedClasses;
    }

    function retrieveJoinedCreatedClass($email, $classCode){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "SELECT * FROM class  WHERE Owner_email = :email AND Class_code = :classCode";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':classCode', $classCode);

        $stmt->execute();

        // Fetch class data
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    function insertToClassuser($userId, $classId){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "INSERT INTO classuser (userId, classId) VALUES (:userId, :classId)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':classId', $classId);

        // Execute the statement
        $stmt->execute();
    }

    function insertToFile($fileName, $fileSize, $fileType){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "INSERT INTO files (fileName, fileSize, fileType) VALUES (:fileName, :fileSize, :fileType)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':fileSize', $fileSize);
        $stmt->bindParam(':fileType', $fileType);

        // Execute the statement
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // If inserted successfully, fetch the last inserted record ID
            $lastInsertId = $conn->lastInsertId();
    
            // Prepare SQL statement to fetch the inserted record
            $sql = "SELECT * FROM files WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $lastInsertId);
            $stmt->execute();
    
            // Fetch the record and return it
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return null;
        }
    }

    function insertToAnnouncement($message, $fileId, $fileName, $fileType, $classId, $email){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "INSERT INTO announcement (message, classId, fileId, fileType, fileName, announcerEmail) VALUES (:message, :classId, :fileId, :fileType, :fileName, :email)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':classId', $classId);
        $stmt->bindParam(':fileId', $fileId);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':fileType', $fileType);

        // Execute the statement
        $stmt->execute();
    }

    function insertToAssignments($message, $assignmentName, $fileId, $fileName, $fileType, $classId, $email, $submissionDate){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "INSERT INTO assignment (message, assignmentName, classId, fileId, fileType, fileName, Email, submissionDate) VALUES (:message, :assignmentName, :classId, :fileId, :fileType, :fileName, :email, :submissionDate)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':classId', $classId);
        $stmt->bindParam(':fileId', $fileId);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':fileType', $fileType);
        $stmt->bindParam(':submissionDate', $submissionDate);
        $stmt->bindParam(':assignmentName', $assignmentName);

        // Execute the statement
        $stmt->execute();
    }
    function insertToAssignmentSubmission($studentId, $assignmentId, $submissionDate, $fileName, $fileType, $classId, $email){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "INSERT INTO assignment (message, assignmentName, classId, fileId, fileType, fileName, Email, submissionDate) VALUES (:message, :assignmentName, :classId, :fileId, :fileType, :fileName, :email, :submissionDate)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':classId', $classId);
        $stmt->bindParam(':fileId', $fileId);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':fileName', $fileName);
        $stmt->bindParam(':fileType', $fileType);
        $stmt->bindParam(':submissionDate', $submissionDate);
        $stmt->bindParam(':assignmentName', $assignmentName);

        // Execute the statement
        $stmt->execute();
    }

    function retrieveClassAnnouncement($classId){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "SELECT * FROM announcement  WHERE classId = :classId ";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':classId', $classId);

        $stmt->execute();

        // Fetch class data
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    function retrieveClassAssignments($classId){
        global $conn;

        // Prepare an SQL statement with placeholders
        $sql = "SELECT * FROM assignment  WHERE classId = :classId ";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters to the placeholders
        $stmt->bindParam(':classId', $classId);

        $stmt->execute();

        // Fetch class data
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTestsByClassId($classId) {
        global $conn;     
    
        $sql = "SELECT * FROM test WHERE classId = :classId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':classId', $classId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    }

    function getQuestionsAndAnswersByTestID($testId) {
        global $conn;

        $sql = "SELECT * FROM question WHERE testId = :testId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':testId', $testId);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($questions as $question) {
            $sql = "SELECT * FROM answer WHERE questionId = :questionId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':questionId', $question['id']);
            $stmt->execute();
            $answers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result[] = [
                'id' => $question['id'],
                'question' => $question['questionText'],
                'answers' => $answers
            ];
        }
        return json_encode($result);
    }


    function insertTestSubmission($studentId, $testId, $submission) {
        
        global $conn;

        $score = 0;
        foreach ($submission as $questionId => $answerId) {
            $sql = "SELECT Correct FROM Answers WHERE AnswerID = :answerID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':answerId', $answerId);
            $stmt->execute();
            $correct = $stmt->fetchColumn();
            if ($correct) {
                $score++;
            }
        }

        $sql = "INSERT INTO testsubmission (studentId, testId, score) VALUES (:studentId, :testId, :score)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':studentId', $studentId);
        $stmt->bindParam(':testId', $testId);
        $stmt->bindParam(':score', $score);
        $stmt->execute();

       
    }



    function countUsersInClass($classId) {
        global $conn; // Ensure $conn is accessible
    
        try {
            $stmt = $conn->prepare("SELECT COUNT(*) as user_count FROM classUser WHERE classId = :classId");
            $stmt->bindParam(':classId', $classId);
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['user_count'];
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }

    function getUsersInClass($classId) {
        global $conn; // Ensure $conn is accessible
    
        try {
            $stmt = $conn->prepare("SELECT u.* FROM users u INNER JOIN classUser cu ON u.id = cu.userId WHERE cu.classId = :classId");
            $stmt->bindParam(':classId', $classId);
            $stmt->execute();
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            return array("error" => $e->getMessage());
        }
    }
    



    useDB($conn, "classroom");
} catch (PDOException $e){
    echo "Connection failed: " . $e->getMessage();

}