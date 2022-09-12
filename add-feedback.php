<?php session_start();

// set the default timezone to use.
date_default_timezone_set( 'Asia/Dhaka' );

if ( isset( $_SESSION['userid'] ) )
{
    require_once __DIR__ . '/includes/conf.php';
    
    $user_id = intval( $_SESSION['userid'] );
    
    $sql = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";

    $result = $conn->query( $sql );

    if ( ! $result->num_rows > 0 )
    {
        header( 'Location: user-login.php' );
    }
}
else
{
    header( 'Location: user-login.php' );
}

if ( isset( $_POST['submitted_at'] ) && intval( $_POST['submitted_at'] ) < time() )
{
	require_once __DIR__ . '/includes/conf.php';

	$query = [];

	foreach ( $_POST as $name => $val )
	{
		$query[$name] = mysqli_real_escape_string( $conn, $val );
	}

	if ( ! isset( $query['feedback'] ) || empty( $query['feedback'] ) )
	{
		$errors[] = 'Feedback Is Required!';
	}

	if ( ! isset( $errors ) && empty( $errors ) )
	{
		$values = "'" . implode( "','", array_values( $query ) ) . "'";

		$sql = "INSERT INTO feedbacks (" . implode( ',', array_keys( $query ) ) . ") VALUES ( " . $values . " )";

		if ( $conn->query( $sql ) === true )
		{
			$success_messages[] = "Feedback Submit Successful!";
		}
		else
		{
			$errors[] = "Feedback Submit Failed!";
		}
	}

	$conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Smart Tourist Guide</title>
	<link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="admin/assets/css/admin-dashboard.css">
</head>
<body>
	<header class="mb-5">
		<nav class="navbar bg-dark navbar-expand-md navbar-light mb-3 fixed-top">
			<div class="container">
				<a href="" class="navbar-brand text-light">Tourist-Guide</a>
				<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sndp3"><span class="navbar-toggler-icon"></span></button>
				<div class="justify-content-end collapse navbar-collapse" id="sndp3">
					<ul class="navbar-nav ml-auto p-2">
						<li class="nav-item active"><a class="nav-link text-white active" href="user-dashboard.php">Create Plan</a></li>
						<li class="nav-item"><a class="nav-link text-white" href="view-plans.php">View Plans</a></li>
						<li class="nav-item"><a class="nav-link text-white" href="add-feedback.php">Feedback</a></li>
						<li class="nav-item"><a class="nav-link text-white" href="user-dashboard.php">Dashboard</a></li>
						<li class="nav-item"><a class="nav-link text-white" href="index.php">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</header><br><br>
	<main class="container w-25 mt-5">
		<h1 class="text-danger text-center">
			Write Feedback
		</h1>
		<hr class="w-100">

		<?php if ( isset( $errors ) ) : ?>
			<?php foreach( $errors as $error ) : ?>
				<!-- Error Alert -->
			    <div class="alert alert-danger fade show">
			         <strong>Error!</strong> <?php echo $error; ?>.
			    </div>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if ( isset( $success_messages ) ) : ?>
			<?php foreach( $success_messages as $success_message ) : ?>
				<!-- Success Alert -->
			    <div class="alert alert-success fade show">
			        <strong>Congratulations!</strong> <?php echo $success_message; ?>.
			    </div>
			<?php endforeach; ?>
		<?php endif; ?>

		<form action="" method="post">
			<div class="form-group row">
				<label class="col-3" for="feedback"><b>Feedback:</b></label>
				<textarea id="feedback" class="col-6" rows="8" name="feedback" value=""></textarea><br><br>
			</div>
			<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
			<input type="hidden" name="submitted_at" value="<?php echo time(); ?>">
			<input class="btn btn-primary submit-btn w-25 mx-auto" type="submit" value="Submit">
		</form>
	</main>


</body>
</html>