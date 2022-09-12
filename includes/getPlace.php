<?php

header( 'Content-type: application/json' );

$data = [];

if ( isset( $_GET['places'] ) && ! empty( $_GET['places'] ) )
{
	$places = explode( ',', $_GET['places'] );
	
	require_once __DIR__ . '/conf.php';

	$sqlWhere = [];

	foreach ( $places as $place_id )
	{
		$sqlWhere[] = "id = $place_id";
	}

	$sql = "SELECT * FROM places WHERE " . implode( ' OR ', array_values( $sqlWhere ) ) . " LIMIT 100";

	$result = $conn->query( $sql );

	if ( $result->num_rows > 0 )
	{
	    // output data of each row
	    while( $row = $result->fetch_assoc() )
	    {
	        $data[] = $row;
	    }
	}

	$conn->close();
}

echo json_encode( $data );

die();