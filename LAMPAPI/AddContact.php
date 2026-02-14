<?php
header("Content-Type: application/json; charset=UTF-8");

$inData = json_decode(file_get_contents('php://input'), true);

// Assumes the frontend requests with the user's id for the new contact
$FirstName  = $inData["FirstName"];
$LastName   = $inData["LastName"];
$Phone   = $inData["Phone"];
$Email   = $inData["Email"];
$UserID     = $inData["UserID"];

$conn = new mysqli("localhost", "DBuser", "passwordpassword", "CONTACTS_DB"); 
if( $conn -> connect_error) {returnWithError( $conn -> connect_error);}

$check = $conn->prepare(
    "SELECT ID FROM User_Contacts 
     WHERE FirstName = ? AND LastName = ? AND Phone = ? AND Email = ? AND UserID = ?"
);
$check->bind_param("ssssi", $FirstName, $LastName, $Phone, $Email, $UserID);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Contact already exists"]);
    exit();
}

$stmt = $conn->prepare("INSERT INTO User_Contacts (FirstName, LastName, Phone, Email, UserID) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $FirstName, $LastName, $Phone, $Email, $UserID);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Contact added"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add contact"]);
}

$stmt->close();
$conn->close();
?>
