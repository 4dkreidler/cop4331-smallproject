<?php
header("Content-Type: application/json; charset=UTF-8");

$inData = json_decode(file_get_contents('php://input'), true);

$Login    = $inData["Login"];
$Password = $inData["Password"];

$conn = new mysqli("localhost", "DBuser", "passwordpassword", "CONTACTS_DB"); 
if( $conn -> connect_error) {returnWithError( $conn -> connect_error);}

$stmt = $conn->prepare("SELECT ID, FirstName, LastName FROM Users WHERE Login=? AND Password=?");
$stmt->bind_param("ss", $Login, $Password);
$stmt->execute();

$result = $stmt->get_result();

// Return status and ID for frontend login session
if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => "success",
        "ID" => $row["ID"],
        "FirstName" => $row["FirstName"],
        "LastName" => $row["LastName"]
    ]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid login"]);
}

$stmt->close();
$conn->close();
?>
