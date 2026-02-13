<?php
    $inData = getRequestInfo(); 

    $newFirstName = $inData["newFirstName"];
    $newLastName = $inData["newLastName"]; 
    $id = $inData["id"]; 

    //connects to database
    $conn = new mysqli("localhost", "DBuser", "passwordpassword", "CONTACTS_DB"); 

    if( $conn -> connect_error)
    {
            returnWithError( $conn -> connect_error); 
    }
    else
    {
		$stmt = $conn->prepare("UPDATE Contacts SET firstName = ?, lastName = ? WHERE id = ? "); //Names must correspond to database so maybe change later
        $stmt = bind_param("ssi", $newFirstName, $newLastName, $id);
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