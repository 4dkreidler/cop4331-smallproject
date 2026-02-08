<?php
header("Content-Type: application/json; charset=UTF-8");

$inData = json_decode(file_get_contents('php://input'), true);

$login    = $inData["login"];
$password = $inData["password"];

include 'db.php';

$stmt = $conn->prepare("SELECT id, firstName, lastName FROM Users WHERE login=? AND password=?");
$stmt->bind_param("ss", $login, $password);
$stmt->execute();

$result = $stmt->get_result();

// Return status and ID for frontend login session
if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => "success",
        "id" => $row["id"],
        "firstName" => $row["firstName"],
        "lastName" => $row["lastName"]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid login"]);
}

$stmt->close();
$conn->close();
?>
