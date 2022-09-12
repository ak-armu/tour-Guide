<?php session_start();

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

if ( isset( $_POST['loginForm'] ) && intval( $_POST['loginForm'] ) < time() )
{
    require_once __DIR__ . '/includes/conf.php';

    if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) )
    {
        $username = filter_var( $_POST['username'], FILTER_SANITIZE_STRING );
        
        $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";

        $result = $conn->query( $sql );

        if ( $result->num_rows > 0 )
        {
            // output data of each row
            while( $row = $result->fetch_assoc() )
            {
                // Check if the hash of the entered login password, matches the stored hash.
                // The salt and the cost factor will be extracted from $row['password'].
                $isPasswordCorrect = password_verify($_POST['password'], $row['password'] );

                if ( $isPasswordCorrect )
                {
                    $_SESSION['userid'] = $row['user_id'];

                    header( 'Location: user-dashboard.php' );
                }
                else
                {
                    $errors[] = 'Invalid User Login Information!';
                }
            }
        }
        else
        {
            $errors[] = 'Invalid User Login Information!';
        }
    }
    else
    {
        $errors[] = 'Invalid User Login Information!';
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
    <!--bootsrap css link-->
    <link href="//cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--CSS file Link-->
    <link rel="stylesheet" href="admin/assets/css/style2.css">
</head>
<body>
    <!--Start Navbar-->
    <header>
        <nav class="navbar bg-dark navbar-expand-md navbar-light mb-3 fixed-top">
            <div class="container">
            
              <a href="" class="navbar-brand text-light">Tourist-Guide</a>
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sndp3"><span class="navbar-toggler-icon"></span></button>
              <div class="justify-content-end collapse navbar-collapse" id="sndp3">
                <ul class="navbar-nav ml-auto p-2">
                    <li class="nav-item"><a class="nav-link text-white" href="index.php">Home</a></li>
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="admin/admin-login.php">Admin</a></li>
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="#">User</a></li>
                    <li class="nav-item ms-4"><a class="nav-link text-white" href="register-form.php">Register</a></li>
                </ul>
             </div>
            </div>
        </nav>
    </header>
    <!--End Navbar-->
    <!--Start Main section-->
    <main class="admin-login container-fluid">
        <div class="login-box bg-light rounded">
            <h3>User Login</h3>
            <form action="" method="post" class="login-form">
                  <div class="container">
                    <label for="username"><b>Username:</b></label>
                    <input type="text" placeholder="Enter Username" name="username" required> <br><br>
                    <label for="password"><b>Password:</b></label>
                    <input type="password" placeholder="Enter Password" name="password" required> <br><br>
                    <input type="hidden" name="loginForm" value="<?php echo time(); ?>">
                    <button type="submit" class="btn btn-primary ps-4 pe-4">Login</button>
                  </div>
             </form>
        </div>
    </main>
</body>
</html>