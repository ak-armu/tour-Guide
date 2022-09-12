<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tourist_guide";

// Create connection
$conn = new mysqli( $servername, $username, $password, $dbname );

// Check connection
if ( $conn->connect_error )
{
	die( "DB Connection Failed" );
}

function username_exists( $username )
{
	global $conn;
	
	$sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";

	$result = $conn->query( $sql );

	return $result->num_rows > 0;
}

function email_exists( $email )
{
	global $conn;
	
	$sql = "SELECT * FROM users WHERE email = '$email' LIMIT 1";

	$result = $conn->query( $sql );

	return $result->num_rows > 0;
}