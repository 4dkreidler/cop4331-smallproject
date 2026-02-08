<?php
header("Content-Type: application/json; charset=UTF-8");

$inData = json_decode(file_get_contents('php://input'), true);

// Assumes the frontend requests with the user's id for the new contact
$userId     = $inData["userId"];
$firstName  = $inData["firstName"];
$lastName   = $inData["lastName"];

include 'db.php';

$stmt = $conn->prepare("INSERT INTO Contacts (userId, firstName, lastName) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $userId, $firstName, $lastName);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Contact added"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to add contact"]);
}

$stmt->close();
$conn->close();
?>
