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

        // Use prepared statement for SELECT
        $getAllInfo = "SELECT name, value FROM system_setting WHERE name = 'SUPPORT_EMAIL'";
        $stmt = $dbConnection->prepare( $getAllInfo );

        if ( $stmt ) {
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            while ( $row = mysqli_fetch_assoc( $result ) ) {
                $infoArray[] = $row;
            }

            $statusCode = 200; // If query prepared and executed, it's a success regardless of rows
            $statusMessage = 'Loaded info';
        } else {
            error_log("Failed to prepare loadSettingInfo statement: " . $dbConnection->error);
            $statusCode = 400;
            $statusMessage = 'Failed to load info';
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
    }

    public function updateEmailSetting($adminUsername, $email){
        global $dbConnection;

        $updateInfo = "UPDATE system_setting SET value = ? WHERE name = 'SUPPORT_EMAIL'";
        $stmt = $dbConnection->prepare($updateInfo);
        $status = false;

        if ($stmt) {
            $stmt->bind_param("s", $email);
            $status = $stmt->execute();
            $stmt->close();
        } else {
            error_log("Failed to prepare updateEmailSetting statement: " . $dbConnection->error);
        }

        $statusCode = $status ? 200 : 400;
        $statusMessage = $status ? "Edited support email successfully" : "Failed to edit support email";

        if($statusCode == 200){
            //INSERT ACTIVE LOG
            $logTitle = "Support Email Update";
            $content = array('email' => $email, 'updated_by' => $adminUsername);
            $content = json_encode( $content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

            $this->insertAdminActiveLog( $adminUsername, $logTitle, $content, 'PRODUCT' ); // Assuming 'PRODUCT' module is correct for this log.
            //INSERT ACTIVE LOG
        }
        
        $outputArray = array('status' => $statusCode, 'message' => $statusMessage);
        outputJson($outputArray);
    }
}