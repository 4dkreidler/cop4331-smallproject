<?php
header("Content-Type: application/json; charset=UTF-8");

// Decode JSON input from frontend
$inData = json_decode(file_get_contents('php://input'), true);

$Login    = $inData["Login"] ?? "";
$Password = $inData["Password"] ?? "";

// -----------------------------
// Connect to local XAMPP MySQL
// -----------------------------
$conn = new mysqli("localhost", "DBuser", "passwordpassword", "CONTACTS_DB");

if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Connection failed: " . $conn->connect_error
    ]);
    exit();
}

// -----------------------------
// Prepare and execute login query
// -----------------------------
$stmt = $conn->prepare("SELECT ID, FirstName, LastName FROM Users WHERE Login=? AND Password=?");
if (!$stmt) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to prepare statement: " . $conn->error
    ]);
    exit();
}

$stmt->bind_param("ss", $Login, $Password);
$stmt->execute();

$result = $stmt->get_result();

// -----------------------------
// Return JSON response
// -----------------------------
if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => "success",
        "ID" => $row["ID"],
        "FirstName" => $row["FirstName"],
        "LastName" => $row["LastName"]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid login"
    ]);
}

// -----------------------------
// Close connections
// -----------------------------
$stmt->close();
$conn->close();
?>
