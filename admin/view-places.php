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

if ( isset( $_POST['place_id'] ) )
{
    require_once __DIR__ . '../../includes/conf.php';
    
    $place_id = intval( $_POST['place_id'] );
    
    $sql = "DELETE FROM places WHERE id = '$place_id'";

    $result = $conn->query( $sql );
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
    <link rel="stylesheet" href="assets/css/view-places.css">
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

    <main class="container mt-5">
        <h1 class="text-danger text-center mb-4">View Places</h1>
        <hr class="container w-100">
        <table class="table table-bordered">
            <thead class="bg-light">
              <tr class="text-dark text-center">
                <th scope="col">ID</th>
                <th scope="col">Image</th>
                <th scope="col">Name</th>
                <th scope="col">Address</th>
                <th scope="col">Tags</th>
                <th scope="col">Description</th>
                <th scope="col">Cost By Road</th>
                <th scope="col">Cost by Air</th>
                <th scope="col">Cost by Train</th>
                <th scope="col">Cost by Ocean</th>
                <th scope="col">Change</th>
              </tr>
            </thead>
            <tbody>
                <?php

                    require_once __DIR__ . '../../includes/conf.php';

                    $sql = "SELECT * FROM places LIMIT 100";

                    $uploaddir = '/Tourist-Guide/uploads/';

                    $result = $conn->query( $sql );

                    if ( $result->num_rows > 0 )
                    {
                        // output data of each row
                        while( $row = $result->fetch_assoc() )
                        {
                            echo "<tr>";
                                
                                echo "<td>{$row['id']}</td>";
                                echo "<td><img src='{$uploaddir}{$row['image']}'</td>";
                                echo "<td>{$row['name']}</td>";
                                echo "<td>{$row['address']}</td>";
                                echo "<td>{$row['tags']}</td>";
                                echo "<td>{$row['description']}</td>";
                                echo "<td>{$row['cost_by_road']}</td>";
                                echo "<td>{$row['cost_by_air']}</td>";
                                echo "<td>{$row['cost_by_train']}</td>";
                                echo "<td>{$row['cost_by_ocean']}</td>";
                                echo "<td><form action='edit-place.php'><button type='submit' class='btn btn-primary'>Edit</button><input type='hidden' name='place_id' value='{$row['id']}'></form><form action='' method='post'><button type='button' class='btn btn-danger dlt-place'>Delete</button><input type='hidden' name='place_id' value='{$row['id']}'></form></td>";

                            echo "</tr>";
                        }
                    }

                    $conn->close();
                ?>
           </tbody>
          </table>
    </main>
    <script src="assets/js/script.js" type="text/javascript" charset="utf-8"></script>
</body>
</html>