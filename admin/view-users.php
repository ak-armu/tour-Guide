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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Tourist Guide</title>
    <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="view-places.css">
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
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="index.php">Logout</a></li>
                </ul>
                </div>
            </div>
            
        </nav>

    </header><br><br>

    <main class="container mt-5">
        <h1 class="text-danger text-center mb-4">All Users</h1>
        <hr class="container w-100">
        <table class="table table-bordered">
            <thead class="bg-light">
              <tr class="text-dark text-center">
                <th scope="col">User ID</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Contact No.</th>
                <th scope="col">Address</th>
                <th scope="col">Gender</th>
                <th scope="col">Age</th>
              </tr>
            </thead>
            <tbody>
                <?php

                    require_once __DIR__ . '../../includes/conf.php';

                    $sql = "SELECT * FROM users LIMIT 100";

                    $result = $conn->query( $sql );

                    if ( $result->num_rows > 0 )
                    {
                        // output data of each row
                        while( $row = $result->fetch_assoc() )
                        {
                            echo "<tr>";
                                
                                echo "<td>{$row['user_id']}</td>";
                                echo "<td>{$row['name']}</td>";
                                echo "<td>{$row['email']}</td>";
                                echo "<td>{$row['phone']}</td>";
                                echo "<td>{$row['address']}</td>";
                                echo "<td>{$row['gender']}</td>";
                                echo "<td>{$row['age']}</td>";

                            echo "</tr>";
                        }
                    }

                    $conn->close();
                ?>
           </tbody>
          </table>
    </main>
   
</body>
</html>