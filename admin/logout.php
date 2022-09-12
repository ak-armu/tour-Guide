<?php session_start();

session_destroy();

unset( $_SESSION['userid'] );

header( 'Location: admin-login.php' );
