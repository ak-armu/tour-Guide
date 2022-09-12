<?php session_start();

if ( isset( $_SESSION['userid'] ) )
{
	require_once __DIR__ . '../../includes/conf.php';
	
	$user_id = intval( $_SESSION['userid'] );
	
	$sql = "SELECT * FROM admins WHERE user_id = '$user_id' LIMIT 1";

	$result = $conn->query( $sql );

	if ( ! $result->num_rows > 0 )
	{
		header( 'Location: admin-login.php' );
	}
}
else
{
	header( 'Location: admin-login.php' );
}

if ( isset( $_POST['submitted_at'] ) && intval( $_POST['submitted_at'] ) < time() && isset( $_POST['place_id'] ) && intval( $_POST['place_id'] ) )
{
	require_once __DIR__ . '../../includes/conf.php';

	unset( $_POST['submitted_at'] );

	$place_id = intval( $_POST['place_id'] );
	
	unset( $_POST['place_id'] );	

	$sqlSet = [];

	if ( isset( $_FILES['image'] ) && ! empty( $_FILES['image']['name'] ) )
	{
		$uploaddir = __DIR__ . '../../uploads/';
    	
    	$filename = time() . '-' . basename( $_FILES['image']['name'] );

    	$uploadfile = $uploaddir . $filename;

    	$type = $_FILES['image']['type'];

    	$extensions = array( 'image/jpg', 'image/jpeg', 'image/png', 'image/gif' );

    	if( ! in_array( $type, $extensions ) )
    	{
	        $errors[] = "Invalid Image Uploaded!";
	    }    

    	if ( move_uploaded_file( $_FILES['image']['tmp_name'], $uploadfile ) )
    	{
    		$sqlSet[] = "image = '$filename'";
	    }
	    else
	    {
	    	$errors[] = "Image Uploading Failed!";
	    }
	}

	foreach ( $_POST as $name => $val )
	{
		$value = mysqli_real_escape_string( $conn, $val );

		$sqlSet[] = "$name = '{$value}'";
	}

	if ( ! isset( $errors ) && empty( $errors ) )
	{
		$values = "SET " . implode( ",", array_values( $sqlSet ) );
		
		$sql = "UPDATE places $values WHERE id = $place_id";

		if ( $conn->query( $sql ) === true )
		{
			$success_messages[] = "Edit Place Successful!";
		}
		else
		{
			$errors[] = "Edit Place Failed!";
		}
	}
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
	<link rel="stylesheet" href="assets/css/admin-dashboard.css">
</head>
<body>
	<header class="mb-5">
		<nav class="navbar bg-dark navbar-expand-md navbar-light mb-3 fixed-top">
			<div class="container">
				<a href="" class="navbar-brand text-light">Tourist-Guide</a>
				<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sndp3"><span class="navbar-toggler-icon"></span></button>
				<div class="justify-content-end collapse navbar-collapse" id="sndp3">
					<ul class="navbar-nav ml-auto p-2">
						<li class="nav-item active"><a class="nav-link text-white active" href="admin-dashboard.php">Add Places</a></li>
						<li class="nav-item ms-4"><a class="nav-link text-white" href="view-places.php">View Places</a></li>
						<li class="nav-item ms-4"><a class="nav-link text-white" href="view-users.php">View Users</a></li>
						<li class="nav-item ms-4"><a class="nav-link text-white" href="view-feedback.php">View Feedback</a></li>
						<li class="nav-item ms-4"><a class="nav-link text-white" href="logout.php">Logout</a></li>
					</ul>
				</div>
			</div>
		</nav>

	</header><br><br>

	<main class="container w-25 mt-5">
		<h1 class="text-danger text-center">Edit Place</h1>
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
		<form action="" method="post" enctype="multipart/form-data">
			<?php

				require_once __DIR__ . '../../includes/conf.php';

				$place_id = isset( $_GET['place_id'] ) ? intval( $_GET['place_id'] ) : 0;

                $sql = "SELECT * FROM places WHERE id = $place_id";

                $uploaddir = '/Tourist-Guide/uploads/';

                $result = $conn->query( $sql );

                if ( $result->num_rows > 0 )
                {
                    // output data of each row
                    while( $row = $result->fetch_assoc() )
                    {
                    	?>
                    		<div class="form-group row">
								<label class="col-3" for="name"><b>Name:</b></label>
								<input type="text" class="col-6" id="name" name="name" value="<?php echo $row['name'] ?>"><br>
							</div>
							<div class="form-group row">
								<label class="col-3" for="fname"><b>Image:</b></label>
								<input type="file" class="col-6" id="image" name="image" value=""><br>
							</div>
							<div class="form-group row">
								<label class="col-3" for="address"><b>Address:</b></label>
								<input type="text" class="col-6" id="address" name="address" value="<?php echo $row['address'] ?>"><br>
							</div>
							<div class="form-group row">
								<label class="col-3" for="area"><b>Area:</b></label>
								<input type="text" class="col-6" id="area" name="area" value="<?php echo $row['area'] ?>"><br>
							</div>
							<div class="form-group row">
								<label class="col-3" for="tags"><b>Tags:</b></label>
								<input type="text" class="col-6" id="tags" name="tags" value="<?php echo $row['tags'] ?>"><br>
							</div>
							<div class="form-group row">
								<label class="col-3" for="description"><b>Description:</b></label>
								<textarea id="description" class="col-6" name="description"><?php echo $row['description'] ?></textarea><br><br>
							</div>
							<div class="form-group row">
								<label for="cost" class="col-2"><b>cost:</b></label>
								<div class="form-group row col-10">
									<div class="col-3">
										<label class="" for="cost_by_road">By Road:</label>
										<input type="text" class="" id="cost_by_road" name="cost_by_road" size="4" value="<?php echo $row['cost_by_road'] ?>">
									</div>
									<div class="col-3">
										<label class="" for="cost_by_air">By Air:</label>
										<input type="text" class="" id="cost_by_air" name="cost_by_air" size="4" value="<?php echo $row['cost_by_air'] ?>">
									</div>
									<div class="col-3">
										<label class="" for="cost_by_train">By Train:</label>
										<input type="text" class="" id="cost_by_train" name="cost_by_train" size="4" value="<?php echo $row['cost_by_train'] ?>">
									</div>
									<div class="col-3">
										<label class="" for="cost_by_ocean">By Ocean:</label>
										<input type="text" class="" id="cost_by_ocean" name="cost_by_ocean" size="4" value="<?php echo $row['cost_by_ocean'] ?>">
									</div>
								</div>
							</div>
                    	<?php
                    }
                }
			?>
			<input type="hidden" name="submitted_at" value="<?php echo time(); ?>">
			<input type="hidden" name="place_id" value="<?php echo $place_id; ?>">
			<input class="btn btn-primary submit-btn w-25 mx-auto" type="submit" value="Update">
		</form> 
	</main>
</body>
</html>
<?php $conn->close(); ?>