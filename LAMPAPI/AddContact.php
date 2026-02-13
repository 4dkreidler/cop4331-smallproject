<?php
header("Content-Type: application/json; charset=UTF-8");

$inData = json_decode(file_get_contents('php://input'), true);

// Assumes the frontend requests with the user's id for the new contact
$UserID     = $inData["UserID"];
$FirstName  = $inData["FirstName"];
$LastName   = $inData["LastName"];

$conn = new mysqli("localhost", "DBuser", "passwordpassword", "CONTACTS_DB"); 
if( $conn -> connect_error) {returnWithError( $conn -> connect_error);}

$stmt = $conn->prepare("INSERT INTO Contacts (UserID, FirstName, LastName) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $UserID, $FirstName, $LastName);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Contact added"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add contact"]);
}

$stmt->close();
$conn->close();
?>
