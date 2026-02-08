<?php
header("Content-Type: application/json; charset=UTF-8");

$inData = json_decode(file_get_contents('php://input'), true);

// Grab request info
$firstName = $inData["firstName"];
$lastName  = $inData["lastName"];
$login     = $inData["login"];
$password  = $inData["password"];

// Assumes we handle database connections elsewhere
include 'db.php';

// Verify that the user isn't a duplicate
$check = $conn->prepare("SELECT id FROM Users WHERE login = ?");
$check->bind_param("s", $login);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "Login already exists"]);
    exit();
}

// Connect and create the user
$stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, login, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $firstName, $lastName, $login, $password);

if ($stmt->execute()) {
    $newId = $conn->insert_id;
    echo json_encode([ "status" => "success", "id" => $newId, "firstName" => $firstName, "lastName" => $lastName ]);
} else {
    echo json_encode(["message" => "Insert failed",]);
}

$stmt->close();
$conn->close();
?>