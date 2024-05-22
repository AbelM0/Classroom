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

    function createTable($conn){
        $sql = "CREATE TABLE users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            First_name VARCHAR(30) NOT NULL,
            Last_name VARCHAR(30) NOT NULL,
            Email VARCHAR(50),
            Password VARCHAR(255)
            )";

        $conn->exec($sql);
    }
    function insertData($firstName, $email, $lastName, $password){

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

    function updatePasswordName($email, $password){
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

    useDB($conn, "classroom");
} catch (PDOException $e){
    echo "Connection failed: " . $e->getMessage();

}