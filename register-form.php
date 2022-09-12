<?php

if ( isset( $_SESSION['userid'] ) )
{
	require_once __DIR__ . '/includes/conf.php';
	
	$user_id = intval( $_SESSION['userid'] );

	$sql = "SELECT * FROM users WHERE user_id = '$user_id' LIMIT 1";

	$result = $conn->query( $sql );

	if ( $result->num_rows > 0 )
	{
		header( 'Location: user-dashboard.php' );
	}
}

if ( isset( $_POST['registered_at'] ) && intval( $_POST['registered_at'] ) < time() )
{
	require_once __DIR__ . '/includes/conf.php';

	$query = [];

	foreach ( $_POST as $name => $val )
	{
		$query[$name] = mysqli_real_escape_string( $conn, $val );

		if ( $name == 'password' )
		{
			// Hash a new password for storing in the database.
			// The function automatically generates a cryptographically safe salt.
			$query[$name] = password_hash( $query[$name], PASSWORD_DEFAULT );	
		}
	}

	if ( username_exists( $query['username'] ) )
	{
		$errors[] = 'Username Already Exists!';
	}

	if ( email_exists( $query['email'] ) )
	{
		$errors[] = 'Email Already Exists!';
	}

	if ( ! isset( $query['username'] ) )
	{
		$errors[] = 'Username Is Required!';
	}

	if ( ! isset( $query['email'] ) )
	{
		$errors[] = 'Email Is Required!';
	}

	if ( ! isset( $errors ) && empty( $errors ) )
	{
		$values = "'" . implode( "','", array_values( $query ) ) . "'";

		$sql = "INSERT INTO users (" . implode( ',', array_keys( $query ) ) . ") VALUES ( " . $values . " )";

		if ( $conn->query( $sql ) === true )
		{
			$success_messages[] = "Registration Successful!";
		}
		else
		{
			$errors[] = "Registration Failed!";
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
	<link rel="stylesheet" href="admin-dashboard.css">
</head>
<body>
	<header>
		<nav class="navbar bg-dark navbar-expand-md navbar-light mb-3 fixed-top">
			<div class="container">
				<a href="index.php" class="navbar-brand text-light">Tourist-Guide</a>
				<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sndp3"><span class="navbar-toggler-icon"></span></button>
				<div class="justify-content-end collapse navbar-collapse" id="sndp3">
					<ul class="navbar-nav ml-auto p-2">
						<li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>
						<li class="nav-item ms-4"><a class="nav-link text-white" href="admin/admin-login.php">Admin</a></li>
						<li class="nav-item ms-4"><a class="nav-link text-white" href="user-login.php">User</a></li>
						<li class="nav-item ms-4"><a class="nav-link text-white" href="#">Register</a></li>
					</ul>
				</div>
			</div>
		</nav>
	</header><br><br>

	<main class="container w-25 mt-5">
		<h1 class="text-danger text-center">Register</h1>
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
		
		<div class="form-box">
			<form action="" method="post">
				<div class="form-group row">
					<label class="col-3" for="username"><b>User Name</b></label>
					<input type="text" class="col-6" id="username" name="username" value="" required>
				</div><br>
				<div class="form-group row">
					<label class="col-3" for="password"><b>Password</b></label>
					<input type="password" class="col-6" id="password" name="password" value="" required>
				</div><br>
				<div class="form-group row">
					<label class="col-3" for="name"><b>Name</b></label>
					<input type="text" class="col-6" id="name" name="name" value="" required>
				</div><br>
				<div class="form-group row">
					<label class="col-3" for="email"><b>Email</b></label>
					<input type="email" class="col-6" id="email" name="email" value="" required>
				</div><br>
				<div class="form-group row">
					<label class="col-3" for="phone"><b>Contact No</b></label>
					<input type="text" class="col-6" id="phone" name="phone" value="">
				</div><br>
				<div class="form-group row">
					<label class="col-3" for="address"><b>Address</b></label>
					<input type="text" class="col-6" id="address" name="address" value="">
				</div><br>
				<div class="form-group row">
					<label class="col-3" for="area"><b>Area</b></label>
					<input type="text" class="col-6" id="area" name="area" value="">
				</div><br>
				<hr>

				<h3 class="text-center text-danger">Answer the below</h3><br>
				<div class="text-center mt-5 d-flex justify-content-between"> 
					<label for="gender"><b>Q1.</b>  What is your gender?</label>
					<select id="gender" name="gender">
						<option value="Male">Male</option>
						<option value="female">Female</option>
					</select>
				</div>
				<div class="text-center mt-5 d-flex justify-content-between"> 
					<label for="age"><b>Q2.</b> How old are you?</label>
					<select id="age" name="age">
						<option value="18">18</option>
						<option value="18-25">18-25</option>
						<option value="25-35">25-35</option>
						<option value="35-50">35-50</option>
					</select>
				</div>
				<div class="text-center mt-5 d-flex justify-content-between"> 
					<label for="likes_beach"><b>Q3.</b>  Would you like to go to the beach?</label>
					<select id="likes_beach" name="likes_beach">
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>
				</div>
				<div class="text-center mt-5 d-flex justify-content-between"> 
					<label for="likes_hill"><b>Q4.</b>  Would you like to go to the hill station?</label>
					<select id="likes_hill" name="likes_hill">
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>
				</div>
				<div class="text-center mt-5 d-flex justify-content-between"> 
					<label for="likes_forest"><b>Q5.</b>  Would you like to go to forest?</label>
					<select id="likes_forest" name="likes_forest">
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>
				</div>
				<div class="text-center mt-5 d-flex justify-content-between"> 
					<label for="likes_waterfalls"><b>Q6.</b>  Would you like to visit waterfalls?</label>
					<select id="likes_waterfalls" name="likes_waterfalls">
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>
				</div>
				<div class="text-center mt-5 d-flex justify-content-between"> 
					<label for="likes_landscape_view"><b>Q7.</b>  Would you like to visit Landscape view?</label>
					<select id="likes_landscape_view" name="likes_landscape_view">
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>
				</div>
				<div class="text-center d-flex justify-content-between mt-5"> 
					<label for="likes_temple"><b>Q8.</b>  Would you like to visit temple?</label>
					<select id="likes_temple" name="likes_temple">
						<option value="1">Yes</option>
						<option value="0">No</option>
					</select>
				</div>
				<input type="hidden" name="registered_at" value="<?php echo time(); ?>">
				<input class="btn btn-primary submit-btn w-25 mx-auto" type="submit" value="Register">
			</form>
		</div>
	</main>
</body>
</html>