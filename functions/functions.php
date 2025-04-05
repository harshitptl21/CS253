<?php
namespace functions;

$appname = 'Ride With Us';
$dbhost = 'localhost';
$dbname = 'ridewithus';
$dbuser = 'ridewithus';
$dbpass = 'rideLikeABoss';

$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die(mysqli_connect_error());

function sanitize_string($var)
{
    global $connection;

    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    $var = trim($var);
    return mysqli_real_escape_string($connection, $var);
}

function html_respond($status, $msg) {
    $string = "<h1 style='text-align: center;'>$status</h1>";
    $string .= "<p style='text-align: center;'>$msg</p>";
    $string .= "<p style='text-align: center;'><a href='/index.php'>Click here to continue</a></p>";
    echo $string;
}

function json_respond($status, $msg, $data = NULL) {
    $response = array("status" => $status, "msg" => $msg);
    if ($data) {
        foreach ($data as $key => $value)
            $response[$key] = $value;
    }
    echo json_encode($response);
}

?>
