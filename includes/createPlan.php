<?php session_start();

if ( isset( $_SESSION['userid'] ) )
{
	require_once __DIR__ . '/conf.php';
	
	$user_id = intval( $_SESSION['userid'] );

	$sql = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";

	$result = $conn->query( $sql );

	if ( ! $result->num_rows > 0 )
	{
		echo json_encode( [] ); die();
	}
}
else
{
	echo json_encode( [] ); die();
}

header( 'Content-type: application/json' );

$data = [];

$_POST = file_get_contents( 'php://input' );

if ( $_POST = json_decode( $_POST, true ) )
{
	if ( isset( $_POST['days'] ) )
	{
		require_once __DIR__ . '/conf.php';

		$day = intval( $_POST['days'] );

		if ( $day )
		{
			if ( ! empty( $_POST['daysPlans'] ) )
			{
				$sql = "INSERT INTO tour_plans ( user_id, total_days, content ) VALUES ( $user_id, '" . $day . "', ' " . json_encode( $_POST['daysPlans'] ) . " ' )";

				if ( $conn->query( $sql ) === true )
				{
					$data['message'] = "Successful!";
				}
				else
				{
					$data['message'] = "Failed!";
				}
			}
		}
	}
}

echo json_encode( $data );

die();