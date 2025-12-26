<?php 
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/functions.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/config.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/constants.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/thirdParty/third_party_api.php';

use \Gumlet\ImageResize;

class Media {

    public function getIbracketPremiumSetting($mqttCode){

        $method = new ThirdParty();
        $getResult = $method -> getIbracketPremiumDeviceSettingLocal($mqttCode);

        return $getResult;

    }

    public function saveMediaImage($imageBase64, $mediaPath, $deviceId) {
        global $dbConnection;
        global $ADMIN_HOST_URL;
    
        // Ensure base64 encoding is properly formatted
        $imageBase64 = str_replace(' ', '+', $imageBase64);
    
        // Validate Base64 format
        if (!str_contains($imageBase64, ',')) {
            $outputArray = array('status' => 440, 'message' => "Invalid Base64 format");
            outputJson($outputArray);
            return;
        }
    
        // Extract base64 data
        $imageBase64Split = explode(',', $imageBase64);
        if (!isset($imageBase64Split[1]) || empty($imageBase64Split[1])) {
            $outputArray = array('status' => 440, 'message' => "Empty or Invalid Image Data");
            outputJson($outputArray);
            return;
        }
    
        // Decode base64 data
        $decodedImageData = base64_decode($imageBase64Split[1], true);
        if ($decodedImageData === false) {
            $outputArray = array('status' => 440, 'message' => "Base64 decoding failed");
            outputJson($outputArray);
            return;
        }
    
        // Generate file path
        $imageName = getNewTokenWithTimestamp();
        $imgFullname = $imageName . '.jpg';
        $path = "../../images/$mediaPath/";
        createFilePathIsNotExist($path);
        $imagePathForSaving = $path . $imgFullname;
        $mediaImageDbPath = $ADMIN_HOST_URL . "images/$mediaPath/" . $imgFullname;
    
        // Save image using Gumlet ImageResize
        try {
            $saveImg = ImageResize::createFromString($decodedImageData);
            $saveImg->save($imagePathForSaving);
        } catch (Exception $e) {
            $outputArray = array('status' => 500, 'message' => "Error processing image: " . $e->getMessage());
            outputJson($outputArray);
            return;
        }
    
        // Save to database
        $currentTimestamp = currentTimestamp();
        date_default_timezone_set('Asia/Singapore');
        $dateTime = date("Y-m-d H:i:s");
    
        $insertRecord = "INSERT INTO media (media_url, device_id, datetime, timestamp) 
                         VALUES ('$mediaImageDbPath', '$deviceId', '$dateTime', '$currentTimestamp')";
        mysqli_query($dbConnection, $insertRecord);
    
        // Response
        $returnData = array(
            'image_path' => $mediaImageDbPath,
            'device_id' => $deviceId,
            'updated_timestamp' => $currentTimestamp,
            'updated_datetime' => $dateTime,
            'timezone' => "Malaysia"
        );
    
        $outputArray = array('status' => 200, 'message' => "Saved image", 'data' => $returnData);
        outputJson($outputArray);
    }

    
    public function saveMediaImageOld($imageBase64, $mediaPath, $deviceId){
        global $dbConnection;
        global $ADMIN_HOST_URL;

        $imageBase64 = str_replace(' ', '+', $imageBase64);

        // ADD IMAGE
        $mediaImageDbPath = "";
        $imageName = getNewTokenWithTimestamp();
        $imgFullname = $imageName . '.jpg';

        $path = "../../images/$mediaPath/";
        createFilePathIsNotExist($path);
        $imagePathForSaving = $path . $imgFullname;
        $mediaImageDbPath = $ADMIN_HOST_URL . "images/$mediaPath/" . $imgFullname;

        $imageBase64Split = explode( ',', $imageBase64 );

        $saveImg = ImageResize::createFromString( base64_decode( $imageBase64Split[ 1 ] ) );
        $saveImg->save( $imagePathForSaving );

        $currentTimestamp = currentTimestamp();
        date_default_timezone_set('Asia/Singapore');
        $dateTime = date("Y-m-d H:i:s");

        $statusCode = 200;
        $statusMessage = 'Saved image';

        $insertRecord =
        "INSERT INTO media (media_url, device_id, datetime, timestamp)";
        $insertRecord .= " VALUES ('$mediaImageDbPath', '$deviceId', '$dateTime', '$currentTimestamp')";
        mysqli_query($dbConnection, $insertRecord);

        $returnData = array('image_path' => $mediaImageDbPath, 'device_id' => $deviceId, 'updated_timestamp' => $currentTimestamp,  'updated_datetime' => $dateTime, 'timezone' => "Malaysia");

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $returnData);
        outputJson( $outputArray );

    }

    public function saveMediaFile($media, $mediaPath){
        global $dbConnection;
        global $ADMIN_HOST_URL;

        $mediaUrl = '';
        $filename = $media[ 'name' ];

        $normalFileType = pathinfo( $filename, PATHINFO_EXTENSION );
        $normalFileType = strtolower( $normalFileType );

        $randomString = generateRandomID();
        $fileFullname = $randomString . ".$normalFileType";

        $path = "../../media/$mediaPath/";
        createFilePathIsNotExist($path);
        $filePath = $path . $fileFullname;
        $serverPath = "media/$mediaPath/" . $fileFullname;

        
        if ( move_uploaded_file( $media[ 'tmp_name' ], $filePath ) ) {
            $checkFile = checkFileExist( $filePath );

            if($checkFile){
                $statusCode = 200;
                $statusMessage = 'Saved file';
        
                $mediaUrl = $ADMIN_HOST_URL . $serverPath;
        
                $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'media_path' => $mediaUrl );
                outputJson( $outputArray );
            }else{
                $statusCode = 404;
                $statusMessage = 'Failed to save file';
        
                $mediaUrl = "";
        
                $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'media_path' => $mediaUrl );
                outputJson( $outputArray );
            }
        }

   

    }

    public function saveOrderDoc($media, $mediaPath, $orderDbCode){
        global $dbConnection;
        global $ADMIN_HOST_URL;

        $mediaUrl = '';
        $filename = $media[ 'name' ];

        $normalFileType = pathinfo( $filename, PATHINFO_EXTENSION );
        $normalFileType = strtolower( $normalFileType );

        $randomString = generateRandomID();
        $fileFullname = $randomString . ".$normalFileType";

        $path = "../../media/$mediaPath/";
        createFilePathIsNotExist($path);
        $filePath = $path . $fileFullname;
        $serverPath = "media/$mediaPath/" . $fileFullname;

        
        if ( move_uploaded_file( $media[ 'tmp_name' ], $filePath ) ) {
            $checkFile = checkFileExist( $filePath );

            if($checkFile){
                $statusCode = 200;
                $statusMessage = 'Saved file';
        
                $mediaUrl = $ADMIN_HOST_URL . $serverPath;

                $this -> saveDocToOrder($mediaUrl, $orderDbCode);
        
                $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'media_path' => $mediaUrl );
                outputJson( $outputArray );
            }else{
                $statusCode = 404;
                $statusMessage = 'Failed to save file';
        
                $mediaUrl = "";
        
                $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'media_path' => $mediaUrl );
                outputJson( $outputArray );
            }
        }

   

    }

    private function saveDocToOrder($newMediaUrl, $orderDbCode){
        global $dbConnection;

        $uploadDoList = array();

        $getInfo = "SELECT upload_do FROM library_order_table WHERE order_db_code = '$orderDbCode'";
        $getInfoQuery = mysqli_query( $dbConnection, $getInfo );

        if ( $row = mysqli_fetch_assoc( $getInfoQuery ) ) {

            $uploadDo = $row[ 'upload_do' ];

            if ( $uploadDo != '' ) {
                $uploadDoList = json_decode( $uploadDo, true );
            }

        }

        $uploadDoList[] = $newMediaUrl;
        $uploadDoList = json_encode( $uploadDoList, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

        $updateInfo = "UPDATE library_order_table SET upload_do = '$uploadDoList' WHERE order_db_code = '$orderDbCode'";
        $status = mysqli_query( $dbConnection, $updateInfo );



    }

}

?>