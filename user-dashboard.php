<?php session_start();

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

$likes_beach = $likes_hill = $likes_forest = $likes_waterfalls = $likes_landscape_view = $likes_temple = false;

// output data of each row
while( $row = $result->fetch_assoc() )
{
	extract( $row );
}

$places = [];

if ( (bool)intval( $likes_beach ) )
{
	$sql = "SELECT * FROM places WHERE ( tags LIKE '%beach%' ) OR ( tags LIKE '%sea%' ) OR ( tags LIKE '%ocean%' )";

	$beach_likes_places_result = $conn->query( $sql );

	if ( $beach_likes_places_result->num_rows > 0 )
	{
        // output data of each row
		while( $row = $beach_likes_places_result->fetch_assoc() )
		{
			$places[$row['id']] = $row['tags'];
		}
	}
}

if ( (bool)intval( $likes_hill ) )
{
	$sql = "SELECT * FROM places WHERE ( tags LIKE '%hill%' ) OR ( tags LIKE '%mountain%' ) OR ( tags LIKE '%mount%' )";

	$hill_likes_places_result = $conn->query( $sql );

	if ( $hill_likes_places_result->num_rows > 0 )
	{
        // output data of each row
		while( $row = $hill_likes_places_result->fetch_assoc() )
		{
			$places[$row['id']] = $row['tags'];
		}
	}
}

if ( (bool)intval( $likes_forest ) )
{
	$sql = "SELECT * FROM places WHERE ( tags LIKE '%forest%' ) OR ( tags LIKE '%wood%' ) OR ( tags LIKE '%tree%' )";

	$forest_likes_places_result = $conn->query( $sql );

	if ( $forest_likes_places_result->num_rows > 0 )
	{
        // output data of each row
		while( $row = $forest_likes_places_result->fetch_assoc() )
		{
			$places[$row['id']] = $row['tags'];
		}
	}
}

if ( (bool)intval( $likes_waterfalls ) )
{
	$sql = "SELECT * FROM places WHERE ( tags LIKE '%waterfalls%' ) OR ( tags LIKE '%fountain%' ) OR ( tags LIKE '%water%' )";

	$waterfalls_likes_places_result = $conn->query( $sql );

	if ( $waterfalls_likes_places_result->num_rows > 0 )
	{
        // output data of each row
		while( $row = $waterfalls_likes_places_result->fetch_assoc() )
		{
			$places[$row['id']] = $row['tags'];
		}
	}
}

if ( (bool)intval( $likes_landscape_view ) )
{
	$sql = "SELECT * FROM places WHERE ( tags LIKE '%landscape%' ) OR ( tags LIKE '%scenery%' ) OR ( tags LIKE '%land%' )";

	$landscape_view_likes_places_result = $conn->query( $sql );

	if ( $landscape_view_likes_places_result->num_rows > 0 )
	{
        // output data of each row
		while( $row = $landscape_view_likes_places_result->fetch_assoc() )
		{
			$places[$row['id']] = $row['tags'];
		}
	}
}

if ( (bool)intval( $likes_temple ) )
{
	$sql = "SELECT * FROM places WHERE ( tags LIKE '%temple%' ) OR ( tags LIKE '%church%' ) OR ( tags LIKE '%sanctuary%' ) OR ( tags LIKE '%mosque%' )";

	$temple_likes_places_result = $conn->query( $sql );

	if ( $temple_likes_places_result->num_rows > 0 )
	{
        // output data of each row
		while( $row = $temple_likes_places_result->fetch_assoc() )
		{
			$places[$row['id']] = $row['tags'];
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
	<link rel="stylesheet" href="admin/assets/css/admin-dashboard.css">
	<link rel="stylesheet" href="//unpkg.com/leaflet@1.3.1/dist/leaflet.css" />
	<script src="//unpkg.com/leaflet@1.3.1/dist/leaflet.js"></script>
	<style type="text/css">#map { width:800px;height:600px;padding:0;margin:0; }@media (min-width: 576px){#mapModal .modal-dialog {max-width: 830px;}#createPlanModal .modal-dialog {max-width: 1080px;}#mapModal .modal-content{background-color: transparent;border: none; border-radius: none;}</style>
	<script src="//cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>
</head>
<body>
	<header class="mb-5">
		<nav class="navbar bg-dark navbar-expand-md navbar-light mb-3 fixed-top">
			<div class="container">
				<a href="" class="navbar-brand text-light">Tourist-Guide</a>
				<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sndp3"><span class="navbar-toggler-icon"></span></button>
				<div class="justify-content-end collapse navbar-collapse" id="sndp3">
					<ul class="navbar-nav ml-auto p-2">
						<li class="nav-item active"><a class="nav-link text-white active createPlan" href="#">Create Plan</a></li>
						<li class="nav-item"><a class="nav-link text-white" href="view-plans.php">View Plans</a></li>
						<li class="nav-item"><a class="nav-link text-white" href="add-feedback.php">Feedback</a></li>
						<li class="nav-item"><a class="nav-link text-white" href="user-dashboard.php">Dashboard</a></li>
						<li class="nav-item"><a class="nav-link text-white" href="logout.php">Logout</a></li>
					</ul>
				</div>
			</div>     
		</nav>
	</header><br><br>

	<main class="container mt-5">
		<h1 class="text-danger text-center">
			Select Places
		</h1>
		<hr>
		<div class="text-center place-selection mt-5 mb-5">
			<label for="places"><b>Select place category</b></label>
			<form action="" method="get" accept-charset="utf-8" style="display: inline;">
				<select id="places" name="places">
					<option value="">All Places</option>
					<?php foreach ( array_unique( $places ) as $place_id => $place_name ) : ?>
						<?php

						if ( isset( $_GET['places'] ) && ! empty( $_GET['places'] ) && $_GET['places'] == $place_name )
						{
							?><option value="<?php echo $place_name; ?>" selected><?php echo $place_name; ?></option><?php
						}
						else
						{
							?><option value="<?php echo $place_name; ?>"><?php echo $place_name; ?></option><?php
						}
						?>

					<?php endforeach; ?>
				</select>
			</form>
		</div>

		<table class="table table-bordered">
			<thead class="bg-light">
				<tr class="text-dark text-center">
					<th scope="col">Image</th>
					<th scope="col">Name</th>
					<th scope="col">Tags</th>
					<th scope="col">Address</th>
					<th scope="col">Description</th>
					<th scope="col">Cost By Road</th>
					<th scope="col">Cost by Air</th>
					<th scope="col">Cost by Train</th>
					<th scope="col">Cost by Ocean</th>
					<th scope="col"></th>
				</tr>
			</thead>
			<tbody>
				<?php

				$sqlWhere = [];

				if ( isset( $_GET['places'] ) && ! empty( $_GET['places'] ) )
				{
					$sqlWhere[] = "tags LIKE '%{$_GET['places']}%'";
				}
				else
				{
					foreach ( $places as $place_id => $place_tags )
					{
						$sqlWhere[] = "id = $place_id";
					}
				}

				$sql = "SELECT * FROM places WHERE " . implode( ' OR ', array_values( $sqlWhere ) ) . " LIMIT 100";

				$uploaddir = '/Tourist-Guide/uploads/';

				$result = $conn->query( $sql );

				if ( $result->num_rows > 0 )
				{
                        // output data of each row
					while( $row = $result->fetch_assoc() )
					{
						echo "<tr>";

						echo "<td><img src='{$uploaddir}{$row['image']}'</td>";
						echo "<td>{$row['name']}</td>";
						echo "<td>{$row['tags']}</td>";
						echo "<td>{$row['address']}</td>";
						echo "<td>{$row['description']}</td>";
						echo "<td>{$row['cost_by_road']}</td>";
						echo "<td>{$row['cost_by_air']}</td>";
						echo "<td>{$row['cost_by_train']}</td>";
						echo "<td>{$row['cost_by_ocean']}</td>";
						echo "<td><button type='button' class='btn btn-primary selectPlace' data-place_id='{$row['id']}'>Select</button><button type='button' class='btn btn-primary mapModal' data-place='{$row['address']}' data-bs-toggle='modal' data-bs-target='#mapModal'>View Map</button></td>";

						echo "</tr>";
					}
				}

				$conn->close();
				?>
			</tbody>
		</table>
	</main>
	<!-- Modal -->
	<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-body">
					<div id="map"></div>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="createPlanModal" tabindex="-1" aria-labelledby="createPlanModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
			    	<h5 class="modal-title">Create Your Tour Plan</h5>
			        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			    </div>
				<div class="modal-body">
					<div class="mb-3">
						<label for="DaysFormControlInput" class="form-label">Tour Duration (days)?</label>
						<input type="number" class="form-control" id="DaysFormControlInput" placeholder="3">
					</div>
					<div id="planForDays"></div>
				</div>
				<div class="modal-footer">
			        <button type="button" class="btn btn-primary" id="createPlanBtn">Create Your Plan</button>
			    </div>
			</div>
		</div>
	</div>
	<script src="admin/assets/js/script.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>