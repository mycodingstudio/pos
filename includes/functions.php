<?php

if ( !function_exists( 'convertToMicroTimestampWithDate' ) ) {
    function convertToMicroTimestampWithDate( $datetime ) {
        $date = new DateTime( $datetime );
        $microTimestamp = $date->format( 'U.u' ) * 1000;
        return $microTimestamp;
    }
}

if ( !function_exists( 'currentMicroTimestamp' ) ) {
    function currentMicroTimestamp() {
        $milliseconds = floor( microtime( true ) * 1000 );
        return $milliseconds;
    }
}

if ( !function_exists( 'generateRandomCode' ) ) {
    function generateRandomCode() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        for ( $i = 0; $i < 5; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'stringContains' ) ) {
    function stringContains( $sentence, $keyword ) {
        if ( !is_string( $keyword ) ) {
            $keyword = strval( $keyword );
        }
        return strpos( $sentence, $keyword ) !== false;
    }
}

if ( !function_exists( 'generateMerchantCode' ) ) {
    function generateMerchantCode() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'M';

        for ( $i = 0; $i < 5; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'generateMerchantKey' ) ) {
    function generateMerchantKey() {
        $hexCharacters = '0123456789abcdef';
        $hexString = '';

        for ( $i = 0; $i < 32; $i++ ) {
            if ( $i === 8 || $i === 13 || $i === 18 || $i === 23 ) {
                $hexString .= '-';
            } else {
                $hexString .= $hexCharacters[ rand( 0, strlen( $hexCharacters ) - 1 ) ];
            }
        }

        return $hexString;
    }
}

if ( !function_exists( 'createFilePathIsNotExist' ) ) {
    function createFilePathIsNotExist( $filePath ) {
        if ( !file_exists( $filePath ) ) {
            mkdir( $filePath, 0777, true );
        }

    }
}

if ( !function_exists( 'convertCurrencyDecimal' ) ) {
    function convertCurrencyDecimal( $amount ) {
        $amount = ( double ) $amount;
        $amount = number_format( $amount, 2, '.', '' );
        return $amount;
    }
}

if ( !function_exists( 'getPayoutPgReferenceCode' ) ) {
    function getPayoutPgReferenceCode() {
        // Get the current date in the format ddMMyyyy
        $date = date( 'dmY' );

        // Generate a random alphanumeric string ( for example, 8 characters long )
        $randomCode = substr( str_shuffle( '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 0, 8 );

        date_default_timezone_set( 'UTC' );
        $now = new DateTime();
        $currentTimestamp = $now->getTimestamp();

        // Combine them all
        return 'RN' . $date . $randomCode;
    }
}

if ( !function_exists( 'generateContractItemId' ) ) {
    function generateContractItemId( $type ) {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'ID';

        $type = strtoupper( $type );
        if ( $type == 'PAYIN' ) {
            $code = 'PI';
        } else if ( $type == 'PAYOUT' ) {
            $code = 'PO';
        } else if ( $type == 'SETTLEMENT' ) {
            $code = 'ST';
        } else if ( $type == 'WITHDRAWAL' ) {
            $code = 'WT';
        } else {
            $code = 'ID';
        }

        for ( $i = 0; $i < 8; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'generateSettlementId' ) ) {
    function generateSettlementId() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'S';

        for ( $i = 0; $i < 8; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'generateTopUpId' ) ) {
    function generateTopUpId() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'T';

        for ( $i = 0; $i < 8; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'generateMerchantCodeForTechnicalSupport' ) ) {
    function generateMerchantCodeForTechnicalSupport() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'MS';

        for ( $i = 0; $i < 8; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'generateMerchantCode' ) ) {
    function generateMerchantCode() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'M0';

        for ( $i = 0; $i < 7; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'generateResellerTechnicalSupportCode' ) ) {
    function generateResellerTechnicalSupportCode() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'RS';

        for ( $i = 0; $i < 8; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'generateResellerId' ) ) {
    function generateResellerId() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'R0';

        for ( $i = 0; $i < 7; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'generateContactId' ) ) {
    function generateContactId() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'C0';

        for ( $i = 0; $i < 7; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'paginateData' ) ) {
    function paginateData( $data, $page, $limit ) {

        if ( $page == 0 ) {
            return [];
        }

        $totalData = count( $data );

        $searchTotal = $page * $limit;

        // Calculate the starting point
        $start = ( $page - 1 ) * $limit;

        if ( $start >= $searchTotal ) {
            return [];
        }

        // If the start index is beyond the length of the data, return an empty array
        if ( $start >= $totalData ) {
            return [];
        }

        // Use array_slice to get the required data
        $slicedData = array_slice( $data, $start, $limit );

        return $slicedData;

    }
}

if ( !function_exists( 'activeLog' ) ) {
    function activeLog( $message ) {

        date_default_timezone_set( 'Asia/Kuala_Lumpur' );
        $dateTime = date( 'Y-m-d H:i:sa' );
        $currentMonth = date( 'Y-m-d' );
        $filepath = $_SERVER[ 'DOCUMENT_ROOT' ] . "/logs/$currentMonth";

        if ( !file_exists( $filepath ) ) {
            mkdir( $filepath );
            // saving file
        }

        $file = fopen( "$filepath/$currentMonth.dat", 'a+' );
        fwrite( $file, "$dateTime -- $message\r\n" );
        fclose( $file );

    }
}

if ( !function_exists( 'sendPostRequest' ) ) {
    function sendPostRequest( $params, $postUrl ) {
        $string = http_build_query( $params );

        $ch = curl_init( $postUrl );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $string );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $response = curl_exec( $ch );
        curl_close( $ch );

        $returnDataJson = json_encode( $response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS );
        $params2 = json_encode( $params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_APOS );

        activeLog( 'sendPostRequest' );
        activeLog( $postUrl );
        activeLog( $params2 );
        activeLog( $returnDataJson );
        activeLog( $response );

        // $returnDataJson = json_decode( $response, true );
        return $response;
    }
}


if ( !function_exists( 'sendPostRequestWithBearerToken' ) ) {
    function sendPostRequestWithBearerToken( $postUrl, $data, $token ) {
        $string = http_build_query( $data );

        $ch = curl_init( $postUrl );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $string );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $headers = array(
            "Authorization: Bearer $token",
        );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

        $response = curl_exec( $ch );
        curl_close( $ch );

        return $response;
    }
}

if ( !function_exists( 'encodeIpay88Signature' ) ) {
    function encodeIpay88Signature( $refNo, $amount ) {

        $amount = str_replace( ',', '', $amount );
        $amount = str_replace( '.', '', $amount );
        $string = '2KYEYXeK4x' . 'M33507_S0001' . $refNo . $amount . 'MYR';
        return hash( 'sha256', $string );
    }
}

if ( !function_exists( 'generateOk2payHash' ) ) {
    function generateOk2payHash( $transNumber, $amount ) {
        $merchantCode = 'M1236122';
        $merchantSecret = 'dd875c91-57be-4753-8d0c-7fe660b468b5';
        $currency = 'MYR';

        $concate = $merchantCode . $merchantSecret . $transNumber . $currency . $amount;
        $hash = hash( 'sha256', $concate, true );
        $hex = bin2hex( $hash );
        return $hex;
    }
}

function microsecondsSinceEpoch() {
    $microseconds = microtime( true );
    $seconds = ( int ) $microseconds;
    $microseconds = ( $microseconds - $seconds ) * 1000000;
    return $microseconds;
}

function sendGetRequest( $url ) {
    $cURLConnection = curl_init();

    curl_setopt( $cURLConnection, CURLOPT_URL, $url );
    curl_setopt( $cURLConnection, CURLOPT_RETURNTRANSFER, true );

    $returnData = curl_exec( $cURLConnection );
    curl_close( $cURLConnection );

    $returnDataJson = json_decode( $returnData, true );

    return $returnDataJson;
}

function ip_info() {

    $ip = $_SERVER[ 'REMOTE_ADDR' ];

    $url = "http://www.geoplugin.net/json.gp?ip=$ip";

    $getIpInfo = sendGetRequest( $url );
    $countryCode = $getIpInfo[ 'geoplugin_countryCode' ];

    if ( $countryCode == '' ) {
        $api2Url = "http://ipinfo.io/{$ip}/json";
        $details = sendGetRequest( $api2Url );

        if ( array_key_exists( 'country', $details ) ) {
            $countryCode = $details[ 'country' ];
        } else {
            $countryCode = null;
        }
    }

    return $countryCode;
}

if ( !function_exists( 'encodeIpay88Signature' ) ) {
    function encodeIpay88Signature( $refNo, $amount ) {

        $amount = str_replace( ',', '', $amount );
        $amount = str_replace( '.', '', $amount );
        $string = '2KYEYXeK4x' . 'M33507_S0001' . $refNo . $amount . 'MYR';
        return hash( 'sha256', $string );
    }
}

if ( !function_exists( 'is_session_started' ) ) {
    function is_session_started() {
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare( phpversion(), '5.4.0', '>=' ) ) {
                return session_status() === PHP_SESSION_ACTIVE ? true : false;
            } else {
                return session_id() === '' ? false : true;
            }
        }
        return false;
    }
}

if ( !function_exists( 'escape' ) ) {
    function escape( $string ) {
        global $dbConnection;
        // must set as global so that you can call out function on other page
        return mysqli_real_escape_string( $dbConnection, trim( $string ) );
    }
}

if ( !function_exists( 'redirect' ) ) {
    function redirect( $location ) {
        return header( 'Location:' . $location );
    }
}

if ( !function_exists( 'redirectIfNotLogin' ) ) {
    function redirectIfNotLogin( $name ) {
        if ( !isset( $name ) ) {
            redirect( '../logout' );
        }
    }
}

if ( !function_exists( 'redirectIfNoInstaSession' ) ) {
    function redirectIfNoInstaSession() {
        if ( !isset( $_SESSION[ 'shortToken' ] ) && !isset( $_SESSION[ 'plusUserID' ] ) ) {
            redirect( 'introduction' );
        }
    }
}

if ( !function_exists( 'redirectIfHasInstaSession' ) ) {
    function redirectIfHasInstaSession() {
        if ( isset( $_SESSION[ 'plusUserID' ] ) && isset( $_SESSION[ 'longToken' ] ) ) {
            redirect( 'campaign' );
        }
    }
}

if ( !function_exists( 'ifNotThisRole' ) ) {
    function ifNotThisRole( $role ) {
        if ( $role != 1 ) {
            redirect( 'logout' );
        }
    }
}

if ( !function_exists( 'ifNotThisMultipleRole' ) ) {
    function ifNotThisMultipleRole( $role1, $role2 ) {
        if ( $role1 != 1 && $role2 != 1 ) {
            redirect( 'logout' );
        }
    }
}

if ( !function_exists( 'outputJson' ) ) {
    function outputJson( $content ) {
        header( 'Content-type: application/json' );
        echo json_encode( $content );
    }
}

if ( !function_exists( 'getCurrentDate' ) ) {
    function getCurrentDate() {
        date_default_timezone_set( 'UTC' );
        return date( 'Y-m-d' );
    }
}

if ( !function_exists( 'base64ToImage' ) ) {
    function base64ToImage( $base64_string, $path, $output_file ) {

        if ( !file_exists( $path ) ) {
            mkdir( $path, 0777, true );
        }

        $file = fopen( $output_file, 'wb' );
        $data = explode( ',', $base64_string );
        fwrite( $file, base64_decode( $data[ 1 ] ) );
        fclose( $file );
        return $output_file;
    }
}

if ( !function_exists( 'cleanFolder' ) ) {
    function cleanFolder( $folder ) {
        //Get a list of all of the file names in the folder.
        $files = glob( $folder . '/*' );
        //Loop through the file list.
        foreach ( $files as $file ) {
            //Make sure that this is a file and not a directory.
            if ( is_file( $file ) ) {

                if ( file_exists( $file ) ) {
                    unlink( $file_url );
                }
            }
        }
    }
}

if ( !function_exists( 'removeFolder' ) ) {
    function removeFolder( $folder ) {
        rmdir( $folder );
    }
}

if ( !function_exists( 'deleteFile' ) ) {
    function deleteFile( $file_url ) {

        if ( file_exists( $file_url ) ) {
            unlink( $file_url );
            return 200;
        } else {
            return 400;
        }
    }
}
if ( !function_exists( 'removeSpacesAndSymbol' ) ) {
    function removeSpacesAndSymbol( $string ) {
        $string = removeSpaces( $string );
        $string = removeWierdSymbol( $string );

        return $string;
    }
}

if ( !function_exists( 'removeSpaces' ) ) {
    function removeSpaces( $string ) {
        return str_replace( ' ', '', $string );
    }
}

if ( !function_exists( 'checkFile' ) ) {
    function checkFile( $file_url ) {
        if ( file_exists( $file_url ) ) {
            return 1;
        } else {
            return 0;
        }
    }
}

if ( !function_exists( 'removeWierdSymbol' ) ) {
    function removeWierdSymbol( $string ) {
        $string = str_replace( '&', '', $string );
        $string = str_replace( '+', '', $string );
        $string = str_replace( '/', '', $string );
        $string = str_replace( '\/', '', $string );
        $string = preg_replace( '/[^\p{L}\p{N}\s]/u', '', $string );
        return $string;
    }
}

if ( !function_exists( 'checkFileExist' ) ) {
    function checkFileExist( $file_url ) {
        if ( file_exists( $file_url ) ) {
            //unlink( $file_url );
            return 200;
        } else {
            return 400;
        }
    }
}

if ( !function_exists( 'convertTimestampToTimezoneTime' ) ) {
    function convertTimestampToTimezoneTime( $timestamp, $timezone ) {
        $dateTime = new DateTime();
        $dateTime->setTimestamp( $timestamp );
        $dateTime->setTimezone( new DateTimeZone( $timezone ) );
        return $dateTime->format( 'Y-m-d H:i:s' );
    }
}

if ( !function_exists( 'convertTimestampToIsoTime' ) ) {
    function convertTimestampToIsoTime( $timestamp ) {
        return date( 'Y-m-d\TH:i:s\Z', $timestamp );
    }
}

if ( !function_exists( 'currentTimestamp' ) ) {
    function currentTimestamp() {
        date_default_timezone_set( 'UTC' );
        $now = new DateTime();
        return $now->getTimestamp();
    }
}

if ( !function_exists( 'codepoint_encode' ) ) {
    function codepoint_encode( $str ) {
        return substr( json_encode( $str ), 1, -1 );
    }
}

if ( !function_exists( 'codepoint_decode' ) ) {
    function codepoint_decode( $str ) {
        return json_decode( sprintf( '"%s"', $str ) );
    }
}

if ( !function_exists( 'generateRandomID' ) ) {
    function generateRandomID() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ( $i = 0; $i < 10; $i++ ) {
            $index = rand( 0, strlen( $characters ) - 1 );
            $randomString .= $characters[ $index ];
        }

        $randomNumber = rand( 1, 10000000 );

        return $randomString . $randomNumber;
    }
}

if ( !function_exists( 'generateRandomIGMedia' ) ) {
    function generateRandomIGMedia() {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';

        for ( $i = 0; $i < 10; $i++ ) {
            $index = rand( 0, strlen( $characters ) - 1 );
            $randomString .= $characters[ $index ];
        }

        $randomNumber = rand( 1, 10000000 );

        return $randomString . $randomNumber;
    }
}

if ( !function_exists( 'initAccCookie' ) ) {
    function initAccCookie( $username, $accessToken ) {
        // 86400 = 1 Day
        $cookie_period = ( 86400 * 365 ) * 99;

        $cookie_name1 = 'username';
        $cookie_name1_value = $username;

        $cookie_name2 = 'accessToken';
        $cookie_name2_value = $accessToken;

        setcookie( $cookie_name1, $cookie_name1_value, time() + $cookie_period, '/' );
        setcookie( $cookie_name2, $cookie_name2_value, time() + $cookie_period, '/' );

    }
}

if ( !function_exists( 'getAccCookie' ) ) {
    function getAccCookie() {
        $cookie_name1 = 'username';
        $cookie_name2 = 'accessToken';

        return array( 'username' => $_COOKIE[ $cookie_name1 ], 'accessToken' => $_COOKIE[ $cookie_name2 ] );
    }
}

if ( !function_exists( 'checkAccCookie' ) ) {
    function checkAccCookie() {

        $cookie_name1 = 'username';
        $cookie_name2 = 'accessToken';

        if ( isset( $_COOKIE[ $cookie_name1 ] ) && isset( $_COOKIE[ $cookie_name2 ] ) ) {
            return 200;
        } else {
            return 400;
        }

    }

}

if ( !function_exists( 'cleanAccCookie' ) ) {
    function cleanAccCookie() {
        $cookie_period = ( 86400 * 365 ) * 99;

        $cookie_name1 = 'username';
        $cookie_name2 = 'accessToken';

        $cookie_name1_value = null;
        $cookie_name2_value = null;

        setcookie( $cookie_name1, $cookie_name1_value, time() - $cookie_period, '/' );
        setcookie( $cookie_name2, $cookie_name2_value, time() - $cookie_period, '/' );

    }
}

if ( !function_exists( 'accCheckLogin' ) ) {
    function accCheckLogin() {
        $checkCookies = checkAccCookie();
        if ( $checkCookies == 200 ) {
            $getCookies = getAccCookie();

            $cookiePhone = $getCookies[ 'username' ];
            $cookieToken = $getCookies[ 'accessToken' ];

            $method = new Account();
            $verifyResult = $method->verifyAccToken( $cookiePhone, $cookieToken );

            echo $verifyResult;

            if ( $verifyResult != 200 ) {
                redirect( 'logout' );
            } else if ( $verifyResult == 200 ) {
                redirect( 'all_loan' );
            }

        }
    }
}

if ( !function_exists( 'managerLogin' ) ) {
    function managerLogin() {
        $checkCookies = checkAccCookie();
        if ( $checkCookies == 200 ) {
            $getCookies = getAccCookie();

            $cookieUsername = $getCookies[ 'username' ];
            $cookieToken = $getCookies[ 'accessToken' ];

            $method = new Account();
            $verifyResult = $method->verifyAdminToken( $cookieUsername, $cookieToken );

            if ( $verifyResult != 200 ) {
                redirect( 'logout' );
            }

        } else {
            redirect( 'logout' );
        }
    }
}

if ( !function_exists( 'accLogin' ) ) {
    function accLogin() {
        $checkCookies = checkAccCookie();
        if ( $checkCookies == 200 ) {
            $getCookies = getAccCookie();

            $cookieUsername = $getCookies[ 'username' ];
            $cookieToken = $getCookies[ 'accessToken' ];

            $method = new Account();
            $verifyResult = $method->verifyAccToken( $cookieUsername, $cookieToken );

            if ( $verifyResult != 200 ) {
                redirect( 'logout' );
            }

        } else {
            redirect( 'logout' );
        }
    }
}

if ( !function_exists( 'preventSingleQuote' ) ) {
    function preventSingleQuote( $str ) {
        $str = str_replace( "'", '&#8217', $str );
        $str = str_replace( "\'", '&#8217', $str );
        $str = str_replace( "\\'", '&#8217', $str );
        $str = str_replace( '"', '&quot', $str );
        $str = str_replace( '\"', '&quot', $str );
        $str = str_replace( '\\"', '&quot', $str );
        return $str;
    }
}

if ( !function_exists( 'getUserIP' ) ) {
    function getUserIP() {
        // Get real visitor IP behind CloudFlare network
        if ( isset( $_SERVER[ 'HTTP_CF_CONNECTING_IP' ] ) ) {
            $_SERVER[ 'REMOTE_ADDR' ] = $_SERVER[ 'HTTP_CF_CONNECTING_IP' ];
            $_SERVER[ 'HTTP_CLIENT_IP' ] = $_SERVER[ 'HTTP_CF_CONNECTING_IP' ];
        }
        $client = @$_SERVER[ 'HTTP_CLIENT_IP' ];
        $forward = @$_SERVER[ 'HTTP_X_FORWARDED_FOR' ];
        $remote = $_SERVER[ 'REMOTE_ADDR' ];

        if ( filter_var( $client, FILTER_VALIDATE_IP ) ) {
            $ip = $client;
        } elseif ( filter_var( $forward, FILTER_VALIDATE_IP ) ) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        return $ip;
    }
}

if ( !function_exists( 'getNewTokenWithTimestamp' ) ) {
    function getNewTokenWithTimestamp() {
        date_default_timezone_set( 'UTC' );
        $now = new DateTime();
        $currentTimestamp = $now->getTimestamp();

        //Generate a random string.
        $token = openssl_random_pseudo_bytes( 8 );

        //Convert the binary data into hexadecimal representation.
        $token = bin2hex( $token );

        //Print it out for example purposes.
        return $currentTimestamp . $token;
    }
}

if ( !function_exists( 'getNewTokenWithTimestampCustomLength' ) ) {
    function getNewTokenWithTimestampCustomLength( $length ) {
        date_default_timezone_set( 'UTC' );
        $now = new DateTime();
        $currentTimestamp = $now->getTimestamp();

        //Generate a random string.
        $token = openssl_random_pseudo_bytes( $length );

        //Convert the binary data into hexadecimal representation.
        $token = bin2hex( $token );

        //Print it out for example purposes.
        return $currentTimestamp . $token;
    }
}

if ( !function_exists( 'getReceiptToken' ) ) {
    function getReceiptToken() {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = 'T';

        for ( $i = 0; $i < 10; $i++ ) {
            $randomIndex = rand( 0, strlen( $chars ) - 1 );
            $code .= $chars[ $randomIndex ];
        }

        return $code;
    }
}

if ( !function_exists( 'getLongAccessToken' ) ) {
    function getLongAccessToken( $length ) {
        //Generate a random string.
        $token = openssl_random_pseudo_bytes( $length );
        //Convert the binary data into hexadecimal representation.
        $token = bin2hex( $token );

        //Print it out for example purposes.
        return $token;
    }
}

if ( !function_exists( 'getNewAccessToken' ) ) {
    function getNewAccessToken() {
        //Generate a random string.
        $token = openssl_random_pseudo_bytes( 16 );
        //Convert the binary data into hexadecimal representation.
        $token = bin2hex( $token );

        //Print it out for example purposes.
        return $token;
    }
}

if ( !function_exists( 'countFilesInFolder' ) ) {

    function countFilesInFolder( $directory ) {
        $filecount = 0;
        $files = glob( $directory . '*' );

        if ( $files ) {
            return count( $files );
        } else {
            return 0;
        }

    }
}

if ( !function_exists( 'createFolderIfNotExist' ) ) {
    function createFolderIfNotExist( $path ) {
        if ( !file_exists( $path ) ) {
            mkdir( $path, 0777, true );
        }
    }
}

if ( !function_exists( 'isotimeToNormal' ) ) {
    function isotimeToNormal( $timedate ) {
        $timestamp = strtotime( $timedate );
        return date( 'Y-m-d', $timestamp );
    }
}

if ( !function_exists( 'isAfterThisFridayNoon' ) ) {
    function isAfterThisFridayNoon() {
        date_default_timezone_set( 'UTC' );
        $todayDay = date( 'l' );

        if ( $todayDay == 'Friday' ) {
            $todayDate = date( 'Y-m-d' );
            $now = new DateTime();
            $currentTimestamp = $now->getTimestamp();
            $specialTimestamp = strtotime( "$todayDate 12:00:00" );

            if ( $currentTimestamp > $specialTimestamp ) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }
}

function ifFriday() {
    date_default_timezone_set( 'UTC' );
    $todayDay = date( 'l' );
    if ( $todayDay == 'Friday' ) {
        return true;
    } else {
        return false;
    }
}

function generateRandomPassword() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';

    for ( $i = 0; $i < 10; $i++ ) {
        $index = rand( 0, strlen( $characters ) - 1 );
        $randomString .= $characters[ $index ];
    }

    $randomNumber = rand( 1, 10000000 );

    return $randomString;
}

if ( !function_exists( 'countLoginPeriod' ) ) {
    function countLoginPeriod( $loginTimestamp ) {
        $currentTime = currentTimestamp();
        $loginPeriod = $currentTime - $loginTimestamp;

        $oneHourTimestamp = 3600;
        $oneDayTimestamp = $oneHourTimestamp * 24;

        $loginStr = '';
        if ( $loginPeriod >= $oneDayTimestamp ) {
            $loginStr = 'A day ago';
        } else if ( $loginPeriod < $oneHourTimestamp ) {
            $loginMinutes = intdiv( $loginPeriod, 60 );

            if ( $loginMinutes == 0 ) {
                $loginStr = 'Online now';
            } else {

                $minuteStr = $loginMinutes == 1 ? 'minute' : 'minutes';
                $loginStr = "$loginMinutes $minuteStr ago";
            }

        } else if ( $loginPeriod < $oneDayTimestamp && $loginPeriod > $oneHourTimestamp ) {
            $loginHour = intdiv( $loginPeriod, $oneHourTimestamp );
            $loginStr = "$loginHour hours ago";
        }

        return $loginStr;

    }
}

if ( !function_exists( 'countLoginPeriodChinese' ) ) {
    function countLoginPeriodChinese( $loginTimestamp ) {
        $currentTime = currentTimestamp();
        $loginPeriod = $currentTime - $loginTimestamp;

        $oneHourTimestamp = 3600;
        $oneDayTimestamp = $oneHourTimestamp * 24;

        $loginStr = '';
        if ( $loginPeriod >= $oneDayTimestamp ) {
            $loginStr = '一天前';
        } else if ( $loginPeriod < $oneHourTimestamp ) {
            $loginMinutes = intdiv( $loginPeriod, 60 );

            if ( $loginMinutes == 0 ) {
                $loginStr = '在线';
            } else {
                $loginStr = "$loginMinutes 分钟前";
            }

        } else if ( $loginPeriod < $oneDayTimestamp && $loginPeriod > $oneHourTimestamp ) {
            $loginHour = intdiv( $loginPeriod, $oneHourTimestamp );
            $loginStr = "$loginHour 小时前";
        }

        return $loginStr;

    }
}

if ( !function_exists( 'generate6DigitsOtp' ) ) {
    function generate6DigitsOtp() {

        global $dbConnection;
        $randNum = str_pad( rand( 0, 999999 ), 6, '0', STR_PAD_LEFT );
        return $randNum;
    }
}