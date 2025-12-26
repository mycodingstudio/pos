<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/functions.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/config.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/controller/constants.php';

class Admin
{

    private function generateRandom10Digits() {
        return (string) mt_rand(1000000000, 9999999999);
    }

    public function generateWarrantyCode($productionCode){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;
        
        do {
            $warrantyCode = $this -> generateRandom10Digits();

            // Prepare statement
            $stmt = $dbConnection->prepare("SELECT id FROM warranty_code_history WHERE warranty_code = ?");
            $stmt->bind_param("s", $warrantyCode);
            $stmt->execute();
            $stmt->store_result(); // Needed to use num_rows

            $getCount = $stmt->num_rows;

            $stmt->free_result();
            $stmt->close();

        } while ($getCount > 0);

        // Use prepared statement for INSERT
        date_default_timezone_set($GLOBAL_TIMEZONE);
        $dateTime = date("Y-m-d H:i:s");

        $insertRecord = 'INSERT INTO warranty_code_history (warranty_code, production_code, created_at) VALUES (?, ?, ?)';
        $stmt = $dbConnection->prepare($insertRecord);

        $status = false;

        if ($stmt) {
            $stmt->bind_param("sss", $warrantyCode, $productionCode, $dateTime);
            $status = $stmt->execute();
            $stmt->close();
            if (!$status) {
                error_log("Failed to execute INSERT statement in insertAdminActiveLog: " . $stmt->error);
            }
        } else {
            error_log("Failed to prepare INSERT statement in insertAdminActiveLog: " . $dbConnection->error);
        }
            
        $statusCode = $status ? 200 : 400;
        $statusMessage = $status ? "Insert successfully" : "Failed to insert";
        $outputArray = array('status' => $statusCode, 'message' => $statusMessage, 'data' => array('warranty_code' => $warrantyCode));
        outputJson($outputArray);

        
    }

    public function updateRunningNumber($createdBy, $deviceCode, $fromNumber, $toNumber){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        $updateInfo = "UPDATE barcode_counter SET counter = ? WHERE device_type = ? AND counter < ?";
        $stmt2 = $dbConnection->prepare($updateInfo);
        $updateStatus = false;

        if ($stmt2) {
            $stmt2->bind_param("isi", $toNumber, $deviceCode, $toNumber); // 's' for string types
            $updateStatus = $stmt2->execute();
            $stmt2->close();

            date_default_timezone_set( $GLOBAL_TIMEZONE );
            $dateTime = date( 'Y-m-d H:i:s' );
            $currentTimestamp = currentTimestamp();

            // Use prepared statement for INSERT
            $insertRecord = 'INSERT INTO barcode_history (device_type, from_number, to_number, created_datetime, created_by) VALUES (?, ?, ?, ?, ?)';
            $stmt = $dbConnection->prepare($insertRecord);

            if ($stmt) {
                $stmt->bind_param("siiss", $deviceCode, $fromNumber, $toNumber, $dateTime, $createdBy); // 'i' for integer type for timestamp if it's int
                $status = $stmt->execute();
                $stmt->close();
                if (!$status) {
                    error_log("Failed to execute INSERT statement in insertAdminActiveLog: " . $stmt->error);
                }
            } else {
                error_log("Failed to prepare INSERT statement in insertAdminActiveLog: " . $dbConnection->error);
            }

        } else {

            error_log("Failed to prepare UPDATE statement in verifyAccount: " . $dbConnection->error);
        }

        $statusCode = $updateStatus ? 200 : 400;
        $statusMessage = $updateStatus ? "Update successfully" : "Failed to update";
        $outputArray = array('status' => $statusCode, 'message' => $statusMessage);
        outputJson($outputArray);

    }

    public function getDeviceRunningNumber($deviceCode){
        global $dbConnection;

        $counterNumber = 0;
        $status = false;

        $getInfo = "SELECT counter FROM barcode_counter WHERE device_type = ?";
        $stmt = $dbConnection->prepare($getInfo);

        if ($stmt) {
            $stmt->bind_param("s", $deviceCode); // 's' for string type
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $status = true;

            if ($row = mysqli_fetch_assoc($result)) {
                $counterNumber = (int) $row['counter'];
            }
        } 

        $statusCode = $status ? 200 : 400;
        $message = $status ? 'Loaded info' : 'Failed to load info';

        $outputArray = array( 'status' => $statusCode, 'message' => $message, 'data' => array('counter' => $counterNumber) );
        outputJson( $outputArray );

    }

    public function verifyAccount($username, $password)
    {
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        $outputArray = array();

        $password = str_replace(' ', '', $password);

        date_default_timezone_set($GLOBAL_TIMEZONE);
        $dateTime = date("Y-m-d h:i");
        $adminName = "";

        // Use prepared statement for SELECT
        $getAccountInfo = "SELECT id, password, status FROM library_sales_executive WHERE username = ?";
        $stmt = $dbConnection->prepare($getAccountInfo);

        if ($stmt) {
            $stmt->bind_param("s", $username); // 's' for string type
            $stmt->execute();
            $result = $stmt->get_result();
            $getCount = $result->num_rows; // Correct way to get count from result set
            $stmt->close();

            if ($getCount == 0) {
                $outputArray = array('status' => 440, 'message' => "Failed to login, please check username and password");
                outputJson($outputArray);
                return;
            }

            if ($row = mysqli_fetch_assoc($result)) {
                $status = $row['status'];

                if($status == "blocked"){
                    $outputArray = array('status' => 444, 'message' => "Your account is blocked");
                    outputJson($outputArray);
                    return;
                }

                $verifyPassword = password_verify($password, $row['password']);

                if (!$verifyPassword) {
                    $outputArray = array('status' => 400, 'message' => "Failed to login, please check username and password");
                    outputJson($outputArray);
                    return;
                } else { // Removed 'else if ($verifyPassword)' as it's redundant
                    $oneTimeToken = getNewTokenWithTimestamp();

                    date_default_timezone_set('Asia/Singapore');
                    $dateTime = date("Y-m-d H:i:s");

                    // Use prepared statement for UPDATE
                    $updateInfo = "UPDATE library_sales_executive SET access_token = ?, last_login = ? WHERE username = ?";
                    $stmt2 = $dbConnection->prepare($updateInfo);
                    $updateStatus = false;

                    if ($stmt2) {
                        $stmt2->bind_param("sss", $oneTimeToken, $dateTime, $username); // 's' for string types
                        $updateStatus = $stmt2->execute();
                        $stmt2->close();
                    } else {
                        error_log("Failed to prepare UPDATE statement in verifyAccount: " . $dbConnection->error);
                    }

                    $data = array('access_token' => $oneTimeToken);
                    $statusCode = $updateStatus ? 200 : 400;
                    $statusMessage = $updateStatus ? "Login successfully" : "Failed to login";
                    
                    $outputArray = array('status' => $statusCode, 'message' => $statusMessage, 'data' => $data);
                    outputJson($outputArray);
                    return;
                }
            }
        } else {
            error_log("Failed to prepare SELECT statement in verifyAccount: " . $dbConnection->error);
            $outputArray = array('status' => 500, 'message' => "Internal server error during login verification."); // Generic error for internal issues
            outputJson($outputArray);
            return;
        }
    }

    public function verifyAdminToken($username, $token)
    {
        global $dbConnection;
        // Use prepared statement for SELECT
        $getAccountInfo = "SELECT id, status FROM library_sales_executive WHERE username = ? AND access_token = ?";
        $stmt = $dbConnection->prepare($getAccountInfo);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $token); // 's' for string types
            $stmt->execute();
            $result = $stmt->get_result();
            $getCount = $result->num_rows;
            $stmt->close();

            if ($getCount > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $user_status = $row['status'];
        
                    if ($user_status == "blocked") {
                        return 444;
                    }
                }
                return 200;
            } else {
                return 400;
            }
        } else {
            error_log("Failed to prepare SELECT statement in verifyAdminToken: " . $dbConnection->error);
            return 500; // Indicate an internal server error
        }
    }

    public function getOwnAccountInfo($username, $cookieToken){
        global $dbConnection;

        $infoArray = array();

        // Use prepared statement for SELECT
        $getAllInfo = "SELECT name, username, executive_db_code, last_login, status FROM library_sales_executive WHERE username = ? AND access_token = ?";
        $stmt = $dbConnection->prepare($getAllInfo);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $cookieToken);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ( $row = mysqli_fetch_assoc( $result ) ) {
                $infoArray = $row;
            }

            $statusCode = $result->num_rows > 0 ? 200 : 400; // Check if any row was found
            $statusMessage = $result->num_rows > 0 ? 'Loaded data' : 'Failed to load data';
        } else {
            error_log("Failed to prepare SELECT statement in getOwnAccountInfo: " . $dbConnection->error);
            $statusCode = 500;
            $statusMessage = 'Internal server error';
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'account_info' => $infoArray );
        outputJson( $outputArray );
    }

    public function isAdminAccessTokenValid($username, $accessToken){
        global $dbConnection;

        // Use prepared statement for SELECT
        $getInfo = "SELECT id FROM library_sales_executive WHERE username = ? AND access_token = ?";
        $stmt = $dbConnection->prepare($getInfo);

        if ($stmt) {
            $stmt->bind_param("ss", $username, $accessToken);
            $stmt->execute();
            $result = $stmt->get_result();
            $getInfoCount = $result->num_rows;
            $stmt->close();
            return $getInfoCount == 0 ? false : true;
        } else {
            error_log("Failed to prepare SELECT statement in isAdminAccessTokenValid: " . $dbConnection->error);
            return false; // Return false on preparation failure
        }
    }

     public function loadAdminInfo($code){
        global $dbConnection;

        $infoArray = array();

        // Use prepared statement for SELECT
        $getAllInfo = "SELECT name, username, modules, status FROM library_sales_executive WHERE admin_db_code = ?";
        $stmt = $dbConnection->prepare($getAllInfo);

        if ($stmt) {
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ( $row = mysqli_fetch_assoc( $result ) ) {
                $row['modules'] = json_decode($row[ 'modules' ], true );
                $infoArray = $row;
            }

            $statusCode = $result->num_rows > 0 ? 200 : 400;
            $statusMessage = $statusCode == 200 ? 'Loaded info' : 'Failed to load info';
        } else {
            error_log("Failed to prepare SELECT statement in loadAdminInfo: " . $dbConnection->error);
            $statusCode = 500;
            $statusMessage = 'Internal server error';
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
     }

     public function updateAdmin($code, $accessUsername, $adminAccesstoken, $userName, $userUsername, $userPassword, $modules, $accountStatus){
        global $dbConnection;

        // No change needed for modules2 as it's for JSON decoding after retrieval or before storing
        $modules2 = str_replace('\\', '', $modules);
        $modules2 = json_decode($modules2, true);

        $isAdminAccessTokenValid = $this  -> isAdminAccessTokenValid($accessUsername, $adminAccesstoken);

        if(!$isAdminAccessTokenValid){
            $outputArray = array( 'status' => 440, 'message' => 'Token expired' );
            outputJson( $outputArray );
            return;
        }

        $updateStatus1 = true; // Assume true if password not updated
        if($userPassword != ""){
            $userPassword = str_replace( ' ', '', $userPassword );
            $encryptPassword = password_hash( $userPassword, PASSWORD_BCRYPT );
            $newAccessToken = getNewTokenWithTimestamp();

            // Use prepared statement for UPDATE password
            $updateInfo = "UPDATE library_sales_executive SET password = ?, access_token = ? WHERE admin_db_code = ?";
            $stmt1 = $dbConnection->prepare($updateInfo);

            if ($stmt1) {
                $stmt1->bind_param("sss", $encryptPassword, $newAccessToken, $code);
                $updateStatus1 = $stmt1->execute();
                $stmt1->close();

            } else {
                error_log("Failed to prepare UPDATE password statement in updateAdmin: " . $dbConnection->error);
                $updateStatus1 = false;
            }
        }

        $statusStr = $accountStatus === "true" ? 'unblocked' : 'blocked';

        // Use prepared statement for UPDATE other admin info
        $updateInfo = "UPDATE library_sales_executive SET name = ?, modules = ?, status = ? WHERE admin_db_code = ?";
        $stmt2 = $dbConnection->prepare($updateInfo);
        $status = false;

        if ($stmt2) {
            $stmt2->bind_param("ssss", $userName, $modules, $statusStr, $code);
            $status = $stmt2->execute();
            $stmt2->close();
        } else {
            error_log("Failed to prepare UPDATE info statement in updateAdmin: " . $dbConnection->error);
        }

        // Overall status depends on both updates
        $finalStatus = $updateStatus1 && $status;
        $statusCode = $finalStatus ? 200 : 400;
        $statusMessage = $finalStatus ? "Edited admin info successfully" : "Failed to edit admin info";
        
        $outputArray = array('status' => $statusCode, 'message' => $statusMessage);
        outputJson($outputArray);
     }

     public function initProfileInfo($adminUsername, $adminAccesstoken){
        global $dbConnection;

        $infoArray = array();

        // Use prepared statement for SELECT
        $getAllInfo = "SELECT name, username, status, sales_code, phone, email FROM library_sales_executive WHERE username = ? AND access_token = ? AND status = 'available'";
        $stmt = $dbConnection->prepare($getAllInfo);

        if ($stmt) {
            $stmt->bind_param("ss", $adminUsername, $adminAccesstoken);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ( $row = mysqli_fetch_assoc( $result ) ) {
                $infoArray = $row;
            }

            $statusCode = $result->num_rows > 0 ? 200 : 400;
            $statusMessage = $statusCode == 200 ? 'Loaded info' : 'Failed to load info';
        } else {
            error_log("Failed to prepare SELECT statement in initProfileInfo: " . $dbConnection->error);
            $statusCode = 500;
            $statusMessage = 'Internal server error';
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
     }

     public function updateProfile($adminUsername, $adminAccesstoken, $currentPassword, $newPassword, $name, $phone, $email){
        global $dbConnection;

        // Use prepared statement for UPDATE password and token
        $updateInfo = "UPDATE library_sales_executive SET name = ?, phone = ?, email = ? WHERE username = ? AND access_token = ?";
        $stmt2 = $dbConnection->prepare($updateInfo);
        $status = false;

        if ($stmt2) {
            $stmt2->bind_param("sssss", $name, $phone, $email, $adminUsername, $adminAccesstoken);
            $status = $stmt2->execute();
            $stmt2->close();
        } else {
            error_log("Failed to prepare UPDATE statement in updateProfile: " . $dbConnection->error);
        }

        // Use prepared statement for SELECT to get password
        $getAccountInfo = "SELECT password FROM library_sales_executive WHERE username = ? AND access_token = ?";
        $stmt1 = $dbConnection->prepare($getAccountInfo);
        $getAccountInfoResult = null; // Initialize to null

        if ($stmt1) {
            $stmt1->bind_param("ss", $adminUsername, $adminAccesstoken);
            $stmt1->execute();
            $getAccountInfoResult = $stmt1->get_result();
            $stmt1->close();
        } else {
            error_log("Failed to prepare SELECT statement in updateProfile (password check): " . $dbConnection->error);
            $outputArray = array( 'status' => 500, 'message' => 'Internal server error during password verification.' );
            outputJson( $outputArray );
            return;
        }


        if ( $row = mysqli_fetch_assoc( $getAccountInfoResult )) {

            if($currentPassword == ""){
                $outputArray = array( 'status' => 200, 'message' => 'Updated profile' );
                outputJson( $outputArray );
                return;
            }

            $verifyPassword = password_verify( $currentPassword, $row[ 'password' ] );

            if ( $verifyPassword ) {
                $newPassword = str_replace( ' ', '', $newPassword );
                $encryptPassword = password_hash( $newPassword, PASSWORD_BCRYPT );
                $oneTimeToken = getNewTokenWithTimestamp();

                // Use prepared statement for UPDATE password and token
                $updateInfo = "UPDATE library_sales_executive SET password = ?, access_token = ? WHERE username = ? AND access_token = ?";
                $stmt2 = $dbConnection->prepare($updateInfo);
                $status = false;

                if ($stmt2) {
                    $stmt2->bind_param("ssss", $encryptPassword, $oneTimeToken, $adminUsername, $adminAccesstoken);
                    $status = $stmt2->execute();
                    $stmt2->close();
                } else {
                    error_log("Failed to prepare UPDATE statement in updateProfile: " . $dbConnection->error);
                }

                $statusCode = $status ? 200 : 400;
                $statusMessage = $status ? 'Update successfully' : 'Failed to update';

                $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => array() );
                outputJson( $outputArray );
                return;

            } else {
                $outputArray = array( 'status' => 400, 'message' => 'Current password is wrong' );
                outputJson( $outputArray );
                return;
            }
        } 

        // This case implies username/token mismatch, or account not found
        $outputArray = array( 'status' => 200, 'message' => 'Updated profile' );
        outputJson( $outputArray );
        return;

     }

     public function getGlobalSetting(){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        date_default_timezone_set($GLOBAL_TIMEZONE);
        $todayDate = date("Y-m-d");
        
        $infoArray = array();

        // Use prepared statement for SELECT system_setting
        $getAllInfo = "SELECT name, value FROM system_setting";
        $stmt1 = $dbConnection->prepare($getAllInfo);
        $systemSettingStatus = false;

        if ($stmt1) {
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $systemSettingStatus = true; // Query executed successfully
            $stmt1->close();

            while ( $row = mysqli_fetch_assoc( $result1 ) ) {
                $infoArray[] = $row;
            }
        } else {
            error_log("Failed to prepare SELECT statement for system_setting in getGlobalSetting: " . $dbConnection->error);
        }

        // Use prepared statement for SELECT pending_order
        $getPendingOrder = "SELECT id FROM order_list WHERE status = 'pending' AND soft_delete = 'false'";
        $stmt2 = $dbConnection->prepare($getPendingOrder);
        $getPendingCount = 0;

        if ($stmt2) {
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $getPendingCount = $result2->num_rows;
            $stmt2->close();
        } else {
            error_log("Failed to prepare SELECT statement for pending_order in getGlobalSetting: " . $dbConnection->error);
        }
        
        // Use prepared statement for SELECT ongoing_event
        $getOngoingEvent = "SELECT id FROM event_list WHERE event_date >= ? AND soft_delete = 'false'";
        $stmt3 = $dbConnection->prepare($getOngoingEvent);
        $getOngoingEventCount = 0;

        if ($stmt3) {
            $stmt3->bind_param("s", $todayDate);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            $getOngoingEventCount = $result3->num_rows;
            $stmt3->close();
        } else {
            error_log("Failed to prepare SELECT statement for ongoing_event in getGlobalSetting: " . $dbConnection->error);
        }

        $statusCode = $systemSettingStatus ? 200 : 400; // Base status on the main setting query
        $statusMessage = $statusCode == 200 ? 'Loaded info' : 'Failed to load info';

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray, 'pending_order' => $getPendingCount, 'ongoing_event' => $getOngoingEventCount );
        outputJson( $outputArray );
     }
     
    public function insertAdminActiveLog($adminUser, $title, $content, $module) {


    }

}
?>