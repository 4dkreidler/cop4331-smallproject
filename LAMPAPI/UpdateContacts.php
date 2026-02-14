<?php
    $inData = getRequestInfo(); 

    $newFirstName = $inData["newFirstName"];
    $newLastName = $inData["newLastName"]; 
    $newPhone = $inData["newPhoneNum"];
    $newEmail = $inData["newEmail"];
    $id = $inData["id"]; 

    //connects to database
    $conn = new mysqli("localhost", "DBuser", "passwordpassword", "CONTACTS_DB"); 

    if( $conn -> connect_error)
    {
            returnWithError( $conn -> connect_error); 
    }
    else
    {
		$stmt = $conn->prepare("UPDATE User_Contacts SET FirstName = ?, LastName = ?, Phone = ?, Email = ? WHERE ID = ? "); 
        $stmt -> bind_param("ssssi", $newFirstName, $newLastName, $newPhone, $newEmail, $id);
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