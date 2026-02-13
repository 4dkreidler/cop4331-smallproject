<?php
    $inData = getRequestInfo(); 

    $userId =   $inData["UserID"];
    $firstName = $inData["FirstName"];
    $lastName = $inData["LastName"]; 

    //connects to database
    $conn = new mysqli("localhost", "DBuser", "passwordpassword", "CONTACTS_DB"); 

    if( $conn -> connect_error)
    {
            returnWithError( $conn -> connect_error); 
    }
    else
    {
		$stmt = $conn->prepare("DELETE FROM User_Contacts WHERE UserID = ? AND FirstName = ? AND LastName = ?");
        $stmt = bind_param("iss", $userId, $firstName, $lastName);
        $stmt -> execute(); 
        $stmt -> close();
        $conn -> close(); 
        returnWithError(""); 
    }

    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'),true);
    }

    function sendResultInfoAsJson( $obj)
    {
        header('Content-type: application/json');
        echo $obj; 
    }

    function returnWithError($err)
    {
		$retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson($retValue); 
    }
?>