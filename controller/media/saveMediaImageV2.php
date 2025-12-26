<?php include_once 'media_api.php';
?>

<?php

if ( !isset( $_POST[ 'imageBase64' ] ) || !isset( $_POST[ 'deviceId' ] ) ) {
    $outputArray = array( 'status' => 440, 'message' => 'Parameters error' );
    outputJson( $outputArray );
    return;
}

global $ADMIN_HOST_URL;

$imageBase64 = escape( $_POST[ 'imageBase64' ] );
$deviceId = escape( $_POST[ 'deviceId' ] );
$mediaPath = $deviceId;

$method = new Media();
$getSetting = $method -> getIbracketPremiumSetting( $deviceId );


$isCameraAllow = (bool) $getSetting[ 'is_camera_allow' ];
$isCaptureAllFaceIn = (bool) $getSetting[ 'is_capture_all_face_in' ];
$isByPassFaceOn = (bool) $getSetting[ 'is_by_pass_face_on' ];

if ( !$isCameraAllow) {

    $outputArray = array( 'status' => 400, 'message' => 'Capture image is not allowed' );
    outputJson( $outputArray );
    return;
}

$isAllowToProceed = true;

if($isByPassFaceOn){
    $isAllowToProceed = true;
}

if(!$isAllowToProceed){
    $outputArray = array( 'status' => 400, 'message' => 'Activated ignored face mode' );
    outputJson( $outputArray );
    return;
}

$imageBase64 = str_replace( ' ', '+', $imageBase64 );

// ✅ Use a fixed 32-character encryption key
$encryptionKey = '0123456789ABCDEFGHIJKLMNOPQRSTUV';
// 32-byte key for AES-256
$iv = '1234567890123456';
// 16-byte IV for AES-256-CBC

// Generate file path
$imageName = getNewTokenWithTimestamp();
$imgFullname = $imageName . '.enc';
$path = "../../images/$mediaPath/";
createFilePathIsNotExist( $path );
$imagePathForSaving = $path . $imgFullname;
$mediaImageDbPath = $ADMIN_HOST_URL . "images/$mediaPath/" . $imgFullname;

// Extract base64 data
$imageBase64Split = explode( ',', $imageBase64 );
if ( !isset( $imageBase64Split[ 1 ] ) || empty( $imageBase64Split[ 1 ] ) ) {
    $outputArray = array( 'status' => 440, 'message' => 'Invalid Base64 format' );
    outputJson( $outputArray );
    return;
}

// Decode base64 to binary
$decodedImageData = base64_decode( $imageBase64Split[ 1 ], true );
if ( $decodedImageData === false ) {
    $outputArray = array( 'status' => 440, 'message' => 'Base64 decoding failed' );
    outputJson( $outputArray );
    return;
}

// ✅ Apply PKCS7 Padding to ensure block size is correct

function pkcs7_pad( $data, $blocksize = 16 ) {
    $pad = $blocksize - ( strlen( $data ) % $blocksize );
    return $data . str_repeat( chr( $pad ), $pad );
}

$paddedImageData = pkcs7_pad( $decodedImageData );

// ✅ Encrypt the image
$encryptedImageData = openssl_encrypt( $paddedImageData, 'AES-256-CBC', $encryptionKey, OPENSSL_RAW_DATA, $iv );
if ( $encryptedImageData === false ) {
    $outputArray = array( 'status' => 500, 'message' => 'Encryption failed' );
    outputJson( $outputArray );
    return;
}

// Save encrypted image
file_put_contents( $imagePathForSaving, base64_encode( $encryptedImageData ) );
// Save as Base64

// Save to database
$currentTimestamp = currentTimestamp();
$dateTime = date( 'Y-m-d H:i:s' );

$insertRecord = "INSERT INTO media (media_url, device_id, datetime, timestamp) 
                 VALUES ('$mediaImageDbPath', '$deviceId', '$dateTime', '$currentTimestamp')";
$updateStatus = mysqli_query( $dbConnection, $insertRecord );

// Response
$returnData = array(
    'image_url' => $mediaImageDbPath, // Send only the file URL
    'device_id' => $deviceId,
    'updated_timestamp' => $currentTimestamp,
    'updated_datetime' => $dateTime
);

if ( $updateStatus ) {

    $curl = curl_init();

    curl_setopt_array( $curl, array(
        CURLOPT_URL => 'https://portal.multihome.tech/controller/fcm/sendIbracketFcmToUser',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "mqttCode=$deviceId&type=media_capture",
    ) );

    $response = curl_exec( $curl );

    curl_close( $curl );

}

$outputArray = array( 'status' => 200, 'message' => 'Encrypted image saved', 'data' => $returnData );
outputJson( $outputArray );

?>
