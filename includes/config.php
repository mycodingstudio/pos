<?php

// Get the Origin header from the request
// $origin = $_SERVER[ 'HTTP_ORIGIN' ];

// Define allowed origins
// $allowed_origins = [
//     'https://house.zpay.support'
// ];

// // Check if the Origin header matches one of the allowed origins
// if ( in_array( $origin, $allowed_origins ) ) {
//     header( 'Access-Control-Allow-Origin: ' . $origin );
// } else {
//     header( 'HTTP/1.1 403 Forbidden' );
//     exit( 'Access Denied' );
// }

header( 'Access-Control-Allow-Origin: *' );
header( 'Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS' );
header( 'Access-Control-Max-Age: 3600' );
header( 'Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token' );

// WRITER

$pageName = $_SERVER[ 'SERVER_NAME' ];
if ( $pageName == 'localhost' ) {
    $db[ 'db_host' ] = 'localhost';
    $db[ 'db_user' ] = 'seven77up';
    $db[ 'db_pass' ] = 'Delld6@0@0';
    $db[ 'db_name' ] = 'multihome';

} else {

    $db[ 'db_host' ] = 'localhost';
    $db[ 'db_user' ] = 'u264680968_multihome';
    $db[ 'db_pass' ] = 'o%9P75@ExhAx';
    $db[ 'db_name' ] = 'u264680968_multihome';

}

foreach ( $db as $key => $value ) {
    define( strtoupper( $key ), $value );
}

$dbConnection = mysqli_connect( DB_HOST, DB_USER, DB_PASS, DB_NAME );
$dbConnection -> set_charset( 'utf8mb4' );

// if ( !$dbConnection ) {
//     echo 'Not Connected!' . '<br>';
//     die( 'connection Database connection failed: ' . mysqli_connect_error() );
// } else {
//     echo 'connection Connected!' . '<br>';
// }

?>