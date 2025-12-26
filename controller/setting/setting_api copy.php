<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/functions.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/config.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/constants.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/admin/admin_api.php';

class Setting extends Admin {

    public function loadSettingInfo(){
        global $dbConnection;

        $infoArray = array();

        $getAllInfo = "SELECT name, value FROM system_setting WHERE name = 'SUPPORT_EMAIL'";
        $getAllInfoQuery = mysqli_query( $dbConnection, $getAllInfo );

        while ( $row = mysqli_fetch_assoc( $getAllInfoQuery ) ) {
            $infoArray[] = $row;
        }

        $statusCode = $getAllInfoQuery ? 200 : 400;
        $statusMessage = $getAllInfoQuery ? 'Loaded info' : 'Failed to load info';

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
    }

    public function updateEmailSetting($adminUsername, $email){
        global $dbConnection;

        $updateInfo = "UPDATE system_setting SET value = '$email' ";
        $updateInfo .= " WHERE name = 'SUPPORT_EMAIL'";

        $status = mysqli_query($dbConnection, $updateInfo);

        $statusCode = $status ? 200 : 400;
        $statusMessage = $status ? "Edited support email successfully" : "Failed to edit support email";

        if($statusCode == 200){
            //INSERT ACTIVE LOG
            $logTitle = "Support Email Update";
            $content = array('email' => $email, 'updated_by' => $adminUsername);
            $content = json_encode( $content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

            $this -> insertAdminActiveLog( $adminUsername, $logTitle, $content, 'PRODUCT' );
            //INSERT ACTIVE LOG
        }
        
        $outputArray = array('status' => $statusCode, 'message' => $statusMessage);
        outputJson($outputArray);
    }


}