<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/functions.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/config.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/constants.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/admin/admin_api.php';

class Material extends Admin {

    public function getAllMaterial($type){
        global $dbConnection;

        $infoArray = array();

        $getAllInfo = "SELECT media_link FROM library_material WHERE material_type = '$type' ORDER BY id DESC ";
        $getAllInfoQuery = mysqli_query( $dbConnection, $getAllInfo );

        while ( $row = mysqli_fetch_assoc( $getAllInfoQuery ) ) {
            $infoArray[] = $row['media_link'];
        }

        $statusCode = $getAllInfoQuery ? 200 : 400;
        $statusMessage = $getAllInfoQuery ? 'Loaded info' : 'Failed to load info';

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
    }

    public function updateImageList($accessUsername, $materialType, $imageList){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        $imageList2 = str_replace('\\', '', $imageList);
        $imageList2 = json_decode($imageList2, true);

        $dbImageList = array();

        $getAllImages = "SELECT media_link FROM library_material WHERE material_type = '$materialType'";
        $getAllImagesQuery = mysqli_query( $dbConnection, $getAllImages );

        while ( $row = mysqli_fetch_assoc( $getAllImagesQuery ) ) {
            $dbImageList[] = $row['media_link'];
        }

        $deleteImageList = array();
        $newImageList = array();

        foreach ( $imageList2 as $key => $mediaLink ) {

            if (!in_array($mediaLink, $dbImageList)) { 
                $newImageList[] = $mediaLink;
            }
        }

        foreach ( $dbImageList as $key => $mediaLink ) {

            if (!in_array($mediaLink, $imageList2)) { 
                $deleteImageList[] = $mediaLink;
            }
        }


        foreach ( $deleteImageList as $key => $mediaLink ) {
            $deleteInfo = "DELETE FROM library_material WHERE media_link = '$mediaLink'";
            $deleteInfoQuery = mysqli_query( $dbConnection, $deleteInfo );
        }

        date_default_timezone_set( $GLOBAL_TIMEZONE );
        foreach ( $newImageList as $key => $mediaLink ) {

            $dateTime = date( 'Y-m-d H:i:s' );

            $insertRecord = 'INSERT INTO library_material (material_type, media_link, created_datetime)';
    
            $insertRecord .= " VALUES ('$materialType', '$mediaLink', '$dateTime')";
            mysqli_query( $dbConnection, $insertRecord );
        }

        
        $statusCode = $getAllImagesQuery ? 200 : 400;
        $message = $getAllImagesQuery ? 'Updated materials' : 'Failed to update materials';

        if($statusCode == 200){
            //INSERT ACTIVE LOG
            $logTitle = "Material Update";
            $content = "$accessUsername updated $materialType materials";

            $this -> insertAdminActiveLog( $accessUsername, $logTitle, $content, 'MATERIAL' );
            //INSERT ACTIVE LOG
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $message );
        outputJson( $outputArray );



    }

}