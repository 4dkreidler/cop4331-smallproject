<?php
header("Content-Type: application/json; charset=UTF-8");

$inData = json_decode(file_get_contents('php://input'), true);

// Grab request info
$FirstName = $inData["FirstName"];
$LastName  = $inData["LastName"];
$Login     = $inData["Login"];
$Password  = $inData["Password"];

$conn = new mysqli("localhost", "DBuser", "passwordpassword", "CONTACTS_DB"); 
if( $conn -> connect_error) {returnWithError( $conn -> connect_error);}

// Verify that the user isn't a duplicate
$check = $conn->prepare("SELECT ID FROM Users WHERE Login = ?");
$check->bind_param("s", $Login);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Login already exists"]);
    exit();
}

// Connect and create the user
$stmt = $conn->prepare("INSERT INTO Users (FirstName, LastName, Login, Password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $FirstName, $LastName, $Login, $Password);

if ($stmt->execute()) {
    $newId = $conn->insert_id;
    echo json_encode([ "status" => "success", "ID" => $newId, "FirstName" => $FirstName, "LastName" => $LastName ]);
} else {
    echo json_encode(["message" => "Insert failed",]);
}

$stmt->close();
$conn->close();
?>