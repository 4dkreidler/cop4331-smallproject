<?php
header("Content-Type: application/json; charset=UTF-8");

// Get JSON input
$inData = json_decode(file_get_contents('php://input'), true);
$ID = $inData["ID"] ?? 0;

// Connect to database
$conn = new mysqli("localhost", "DBuser", "passwordpassword", "CONTACTS_DB");

if ($conn->connect_error) {
    echo json_encode(["status" => "error"]);
    exit();
}

// Prepare delete statement
$stmt = $conn->prepare("DELETE FROM User_Contacts WHERE ID=?");
$stmt->bind_param("i", $ID);
$stmt->execute();

// Check if something was deleted
if($stmt->affected_rows > 0)
{
    echo json_encode(["status" => "success"]);
}
else
{
    echo json_encode(["status" => "error"]);
}

$stmt->close();
$conn->close();
?>
