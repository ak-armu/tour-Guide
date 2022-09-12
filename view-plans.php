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
                        <li class="nav-item active"><a class="nav-link text-white active" href="#">Create Plan</a></li>
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
        <h1 class="text-danger text-center mb-4">User Plans</h1>
        <hr class="container w-100">
        <table class="table table-bordered">
            <thead class="bg-light">
              <tr class="text-dark text-center">
                <th scope="col">Plan ID</th>
                <th scope="col">Plan Duration</th>
                <th scope="col">Plan</th>               
              </tr>
            </thead>
            <tbody>
                <?php

                    require_once __DIR__ . '/includes/conf.php';

                    $sql = "SELECT * FROM tour_plans WHERE user_id = '$user_id' ORDER BY id DESC LIMIT 100";

                    $result = $conn->query( $sql );

                    if ( $result->num_rows > 0 )
                    {
                        // output data of each row
                        while( $row = $result->fetch_assoc() )
                        {
                            $content = json_decode( $row['content'], true );

                            $days = [];

                            foreach ( $content as $cont )
                            {
                                $days[$cont['day']][] = $cont['val'];   
                            }

                            sort( $days );

                            $planData = '';

                            foreach ( $days as $day => $place_ids )
                            {
                                $sqlWhere = [];
                                
                                foreach ( $place_ids as $place_id )
                                {
                                    $sqlWhere[] = "id = $place_id";    
                                }

                                $sqld = "SELECT * FROM places WHERE " . implode( ' OR ', array_values( $sqlWhere ) ) . " LIMIT 100";

                                $resultd = $conn->query( $sqld );

                                $data = [];

                                if ( $resultd->num_rows > 0 )
                                {
                                    // output data of each row
                                    while( $rowd = $resultd->fetch_assoc() )
                                    {
                                        $data[] = $rowd['name'];
                                    }
                                }

                                $planData .= 'Day ' . $day + 1 . ' : ' . implode( ', ', $data ) . '</br>';
                            }
                            
                            echo "<tr>";
                                
                                echo "<td>{$row['id']}</td>";
                                echo "<td>{$row['total_days']} Days</td>";
                                echo "<td>{$planData}</td>";

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