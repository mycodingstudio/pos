<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/functions.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/config.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/constants.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/admin/admin_api.php';

class Order extends Admin {

    public function generateNewToken(){
        $newToken = getNewTokenWithTimestamp();
        $outputArray = array( 'status' => 200, 'message' => 'Generated new token', 'data' => $newToken );
        outputJson( $outputArray );
    }

    // public function insertOrderItems($orderId, $salesCode, $itemList) {
    //     global $dbConnection;
    //     global $GLOBAL_TIMEZONE;

    //     // Sanitize and decode the item list
    //     $itemList = str_replace('\\', '', $itemList);
    //     $items = json_decode($itemList, true);

    //     if (empty($items)) {
    //         outputJson(['status' => 400, 'message' => 'No items to insert.']);
    //         return;
    //     }

    //     date_default_timezone_set($GLOBAL_TIMEZONE);
    //     $dateTime = date('Y-m-d H:i:s');

    //     // Build the query dynamically
    //     $query = 'INSERT INTO item_checkout_history (sales_code, order_id, serial_number, created_date) VALUES ';
    //     $params = [];
    //     $placeholders = [];
    //     $types = '';

    //     foreach ($items as $item) {
    //         $placeholders[] = '(?, ?, ?, ?)';
    //         $params[] = $salesCode;
    //         $params[] = $orderId;
    //         $params[] = $item;
    //         $params[] = $dateTime;
    //         $types .= 'ssss';
    //     }

    //     $query .= implode(', ', $placeholders);

    //     // Prepare the statement
    //     $stmt = $dbConnection->prepare($query);

    //     // Bind parameters using the array_merge trick to avoid `call_user_func_array` and `refValues`
    //     if ($stmt) {
    //         $bind_names = [$types];
    //         for ($i = 0; $i < count($params); $i++) {
    //             $bind_name = 'bind' . $i;
    //             $$bind_name = $params[$i];
    //             $bind_names[] = &$$bind_name;
    //         }

    //         call_user_func_array([$stmt, 'bind_param'], $bind_names);
            
    //         $status = $stmt->execute();
    //         $stmt->close();
    //     } else {
    //         error_log("Failed to prepare INSERT statement: " . $dbConnection->error);
    //         $status = false;
    //     }

    //     $statusCode = $status ? 200 : 400;
    //     $message = $status ? 'Updated system successfully.' : 'Failed to update system.';
        
    //     outputJson(['status' => $statusCode, 'message' => $message]);
    // }

    public function insertOrderItems($orderId, $salesCode, $itemList){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        $itemList = str_replace('\\', '', $itemList);
        $items = json_decode($itemList, true);

        if (empty($items)) {
            outputJson(['status' => 400, 'message' => 'No items to insert.']);
            return;
        }

        // --- Step 1: Check for existing serial numbers ---
        // Create a list of placeholders and parameters for the search query
        $placeholders = implode(', ', array_fill(0, count($items), '?'));
        $query = "SELECT serial_number FROM item_checkout_history WHERE serial_number IN ({$placeholders})";
        
        $stmt = $dbConnection->prepare($query);
        if (!$stmt) {
            error_log("Failed to prepare check statement: " . $dbConnection->error);
            outputJson(['status' => 500, 'message' => 'Internal server error during item check.']);
            return;
        }

        // Bind parameters dynamically
        $types = str_repeat('s', count($items));
        $stmt->bind_param($types, ...$items);

        $stmt->execute();
        $result = $stmt->get_result();
        $existingItems = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        if (!empty($existingItems)) {
            // At least one item was found, so we return an error immediately
            $usedSerialNumbers = array_column($existingItems, 'serial_number');
            $message = 'The following item(s) have already been used: ' . implode(', ', $usedSerialNumbers);
            outputJson(['status' => 409, 'message' => $message]);
            return;
        }
        
        // --- Step 2: Proceed with the transaction if no existing items are found ---
        $dbConnection->begin_transaction();
        $status = true;

        date_default_timezone_set($GLOBAL_TIMEZONE);
        $dateTime = date('Y-m-d H:i:s');
        
        $insertRecord = 'INSERT INTO item_checkout_history (sales_code, order_id, serial_number, created_date) VALUES (?, ?, ?, ?)';
        $stmt = $dbConnection->prepare($insertRecord);

        if (!$stmt) {
            $dbConnection->rollback();
            outputJson(['status' => 400, 'message' => 'Failed to prepare statement.']);
            return;
        }

        foreach ($items as $item) {
            $stmt->bind_param("ssss", $salesCode, $orderId, $item, $dateTime);
            
            if (!$stmt->execute()) {
                $status = false;
                break; // Exit the loop on any failure
            }
        }

        $stmt->close();

        if ($status) {
            $dbConnection->commit();
            $statusCode = 200;
            $message = 'Updated system successfully.';
        } else {
            $dbConnection->rollback();
            $statusCode = 400;
            $message = 'Failed to insert all items due to an unexpected error.';
        }

        $outputArray = ['status' => $statusCode, 'message' => $message];
        outputJson($outputArray);
    }

    public function getBasicInfo(){
        global $dbConnection;

        $priceList = array();
        $resellerList = array();

        // Get product prices
        $getAllInfo = "SELECT device_type, sales_price FROM library_product WHERE soft_delete = 'false'";
        $stmt = $dbConnection->prepare( $getAllInfo );
        $productLoadStatus = false;

        if ( $stmt ) {
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            $productLoadStatus = true; // Query executed successfully

            while ( $row = mysqli_fetch_assoc( $result ) ) {
                $priceList[$row['device_type']] = $row['sales_price'];
            }
        } else {
            error_log("Failed to prepare product price statement in getBasicInfo: " . $dbConnection->error);
        }

        // Get reseller list
        $getAllReseller = "SELECT reseller_db_code, name FROM library_reseller";
        $stmtReseller = $dbConnection->prepare( $getAllReseller );
        $resellerLoadStatus = false;

        if ( $stmtReseller ) {
            $stmtReseller->execute();
            $resultReseller = $stmtReseller->get_result();
            $stmtReseller->close();
            $resellerLoadStatus = true; // Query executed successfully

            while ( $row = mysqli_fetch_assoc( $resultReseller ) ) {
                $resellerList[] = $row;
            }
        } else {
            error_log("Failed to prepare reseller list statement in getBasicInfo: " . $dbConnection->error);
        }

        $data = array();
        $data['price_list'] = $priceList;
        $data['reseller_list'] = $resellerList;

        // Overall status depends on both queries succeeding
        $statusCode = ($productLoadStatus && $resellerLoadStatus) ? 200 : 400;
        $statusMessage = ($productLoadStatus && $resellerLoadStatus) ? 'Loaded info' : 'Failed to load info';

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $data );
        outputJson( $outputArray );
    }

    public function addOrder($adminUsername, $clientEmail, $clientName, $clientPhone, $resellerName, $fromPlatform, $trackingLink, $billingFullAddress, $shippingFullAddress, $getOrderDetail, $totalAmount, $orderRemark, $seCode, $orderStatus, $paymentProofPath, $byCash){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        $orderDbCode = getNewTokenWithTimestamp();

        date_default_timezone_set( $GLOBAL_TIMEZONE );
        $dateTime = date( 'Y-m-d H:i:s' );

        $currentTimestamp = currentTimestamp();

        $resellerNameParam = ($resellerName === '' || $resellerName === "Select") ? null : $resellerName;

        // Modified SQL query to include the 'status' and 'payment_proof' columns
        $insertRecord = 'INSERT INTO order_list (order_db_code, total_amount, order_details, billing_address, shipping_address, client_email, client_phone, client_name, reseller_db_code, created_datetime, created_timestamp, remark, status, tracking_link, created_by, order_from, sales_executive_code, payment_proof, by_cash)';
        $insertRecord .= " VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $dbConnection->prepare( $insertRecord );
        $status = false;

        if ( $stmt ) {
            // Updated bind_param with 's' for the new string parameters
            $stmt->bind_param(
                "sdssssssssisssssssd", // Correct types based on your columns
                $orderDbCode,
                $totalAmount,
                $getOrderDetail,
                $billingFullAddress,
                $shippingFullAddress,
                $clientEmail,
                $clientPhone,
                $clientName,
                $resellerNameParam, // Use the nullable parameter
                $dateTime,
                $currentTimestamp,
                $orderRemark,
                $orderStatus, // New parameter
                $trackingLink,
                $adminUsername,
                $fromPlatform,
                $seCode,
                $paymentProofPath, $byCash // New parameter
            );
            $status = $stmt->execute();
            $stmt->close();
        } else {
            error_log("Failed to prepare addOrder statement: " . $dbConnection->error);
        }

        $statusCode = $status ? 200 : 400;
        $message = $status ? 'Created new order' : 'Failed to create new order';

        if($statusCode == 200){
            //INSERT ACTIVE LOG
            $logTitle = "Order Creation";
            $content = "$adminUsername created new order $clientName, total amount: $totalAmount";

            $this->insertAdminActiveLog( $adminUsername, $logTitle, $content, 'ORDER' );
            $this->insertOrderLog($orderDbCode, $content, $adminUsername);
            //INSERT ACTIVE LOG
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $message );
        outputJson( $outputArray );
    }
    private function getSEName($seCode){
        global $dbConnection;

        $infoArray = array();

        $getInfo = "SELECT name, phone FROM library_sales_executive WHERE sales_code = ?";
        $stmt = $dbConnection->prepare( $getInfo );

        if ($stmt) {
            $stmt->bind_param("s", $seCode);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = mysqli_fetch_assoc($result)) {
                $infoArray = $row;
            }

            $stmt->close();
        }

        return $infoArray;

    }

    public function getAllOrderList($salesCode){
        global $dbConnection;

        $infoArray = array();

        $getAllInfo = "SELECT * FROM order_list WHERE status != 'cancelled' AND soft_delete = 'false' AND sales_executive_code = '$salesCode' ORDER BY id DESC";
        $stmt = $dbConnection->prepare( $getAllInfo );

        if ( $stmt ) {
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            while ( $row = mysqli_fetch_assoc( $result ) ) {
                $row['total_amount'] = (double) $row['total_amount'];
                $seCode = $row['sales_executive_code'];
                $row['se_code'] = "";
                $row['se_name'] = "";
                $row['se_phone'] = "";
                $row['order_details'] = json_decode($row[ 'order_details' ], true );

                if($seCode != "" && $seCode != "HQ"){
                  $seInfo = $this -> getSEName($seCode);
                  $row['se_code'] = $seCode;
                  $row['se_name'] = $seInfo['name'];
                  $row['se_phone'] = $seInfo['phone'];
                }

                unset($row['sales_executive_code']);
                
                $infoArray[] = $row;
            }

            $statusCode = 200; // If query prepared and executed, it's a success regardless of rows
            $statusMessage = 'Loaded info';
        } else {
            error_log("Failed to prepare getAllOrderList statement: " . $dbConnection->error);
            $statusCode = 400;
            $statusMessage = 'Failed to load info';
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
    }

    public function deleteOrder($adminUsername, $code, $clientEmail, $totalAmount, $orderId){
        global $dbConnection;

        $deleteAcc = "UPDATE order_list SET soft_delete = 'true' WHERE order_db_code = ?";
        $stmt = $dbConnection->prepare($deleteAcc);
        $status = false;

        if ($stmt) {
            $stmt->bind_param("s", $code);
            $status = $stmt->execute();
            $stmt->close();
        } else {
            error_log("Failed to prepare deleteOrder statement: " . $dbConnection->error);
        }

        $statusCode = $status ? 200 : 400;
        $statusMessage = $status ? "Deleted order successfully" : "Failed to delete order";

        if($statusCode == 200){
            //INSERT ACTIVE LOG
            $logTitle = "Order Deletion";
            $content = "$adminUsername deleted order id $orderId, for client $clientEmail and total $totalAmount";

            $this->insertAdminActiveLog( $adminUsername, $logTitle, $content, 'ORDER' );
            //INSERT ACTIVE LOG
        }
        
        $outputArray = array('status' => $statusCode, 'message' => $statusMessage);
        outputJson($outputArray);
    }

    private function insertOrderLog($orderDbCode, $content, $updatedBy){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        date_default_timezone_set( $GLOBAL_TIMEZONE );
        $dateTime = date( 'Y-m-d H:i:s' );

        $insertRecord = 'INSERT INTO order_log_history (order_db_code, content, created_datetime, updated_by) VALUES (?, ?, ?, ?)';
        $stmt = $dbConnection->prepare( $insertRecord );

        if ( $stmt ) {
            $stmt->bind_param( "ssss", $orderDbCode, $content, $dateTime, $updatedBy );
            if (!$stmt->execute()) {
                error_log("Failed to insert order log: " . $stmt->error);
            }
            $stmt->close();
        } else {
            error_log("Failed to prepare insertOrderLog statement: " . $dbConnection->error);
        }
    }

    public function loadOrderInfo($code){
        global $dbConnection;

        $infoArray = array();

        $getInfo = "SELECT * FROM order_list WHERE order_db_code = ?";
        $stmt = $dbConnection->prepare( $getInfo );

        if ( $stmt ) {
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            if ( $row = mysqli_fetch_assoc( $result ) ) {
                $row['total_amount'] = (double) $row['total_amount'];
                $row['order_log'] = $this->getFromOrderLog($code);
                $row['serial_number_list'] = $this->getDeviceFromOrder($code);
                $row['order_details'] = json_decode($row[ 'order_details' ], true );
                $infoArray = $row;
            }

            $statusCode = $result->num_rows > 0 ? 200 : 400;
            $statusMessage = $statusCode == 200 ? 'Loaded info' : 'Failed to load info';
        } else {
            error_log("Failed to prepare loadOrderInfo statement: " . $dbConnection->error);
            $statusCode = 400;
            $statusMessage = 'Failed to load info';
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
    }

    private function getDeviceFromOrder($code){
        global $dbConnection;
 
        $infoArray = array();

        $getInfo = "SELECT device_type, serial_number FROM order_device_serial_number WHERE order_db_code = ?";
        $stmt = $dbConnection->prepare( $getInfo );

        if ( $stmt ) {
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            while ( $row = mysqli_fetch_assoc( $result ) ) {
                $infoArray[] = $row;
            }
        } else {
            error_log("Failed to prepare getDeviceFromOrder statement: " . $dbConnection->error);
        }
        return $infoArray;
    }

    private function getFromOrderLog($code){
        global $dbConnection;
 
        $infoArray = array();

        $getInfo = "SELECT content, created_datetime, updated_by FROM order_log_history WHERE order_db_code = ? ORDER BY id DESC";
        $stmt = $dbConnection->prepare( $getInfo );

        if ( $stmt ) {
            $stmt->bind_param("s", $code);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            while ( $row = mysqli_fetch_assoc( $result ) ) {
                $infoArray[] = $row;
            }
        } else {
            error_log("Failed to prepare getFromOrderLog statement: " . $dbConnection->error);
        }
        return $infoArray;
    }

    // public function updateOrder($code, $orderId, $adminUsername, $clientEmail, $clientName, $clientPhone, $resellerName, $fromPlatform, $trackingLink, $billingFullAddress, $shippingFullAddress, $getOrderDetail, $totalAmount, $orderRemark, $orderStatus){

    //     global $dbConnection;
    //     global $GLOBAL_TIMEZONE;

    //     $getOrderDetail2 = str_replace('\\', '', $getOrderDetail);
    //     $getOrderDetail2 = json_decode($getOrderDetail2, true);

    //     date_default_timezone_set( $GLOBAL_TIMEZONE );
    //     $dateTime = date( 'Y-m-d H:i:s' );

    //     $currentTimestamp = currentTimestamp();

    //     $resellerNameParam = ($resellerName === '' || $resellerName === "Select") ? null : $resellerName;

    //     $updateInfo = "UPDATE order_list SET total_amount = ?, order_details = ?, billing_address = ?, shipping_address = ?, client_email = ?, client_phone = ?, client_name = ?, reseller_db_code = ?, updated_datetime = ?, updated_timestamp = ?, remark = ?, status = ?, tracking_link = ? WHERE order_db_code = ?";
    //     $stmt = $dbConnection->prepare($updateInfo);
    //     $status = false;

    //     if ($stmt) {
    //         $stmt->bind_param(
    //             "dssssssssissss", // Correct types based on your columns
    //             $totalAmount,
    //             $getOrderDetail,
    //             $billingFullAddress,
    //             $shippingFullAddress,
    //             $clientEmail,
    //             $clientPhone,
    //             $clientName,
    //             $resellerNameParam,
    //             $dateTime,
    //             $currentTimestamp,
    //             $orderRemark,
    //             $orderStatus,
    //             $trackingLink,
    //             $code
    //         );
    //         $status = $stmt->execute();
    //         $stmt->close();
    //     } else {
    //         error_log("Failed to prepare updateOrder statement: " . $dbConnection->error);
    //     }

    //     $statusCode = $status ? 200 : 400;
    //     $message = $status ? 'Updated order' : 'Failed to update order';

    //     if($statusCode == 200){
    //         //INSERT ACTIVE LOG
    //         $logTitle = "Order #$orderId Update";
    //         $content = array('order_id'  => $orderId, 'client_email'  => $clientEmail, 'client_phone'  => $clientPhone, 'client_name'  => $clientName, 'total_amount' => $totalAmount, 'order_details'  => $getOrderDetail2, 'billing_address'  => $billingFullAddress, 'shipping_address'  => $shippingFullAddress, 'remark'  => $orderRemark, 'status'  => $orderStatus, 'tracking_link'  => $trackingLink);
    //         $content = json_encode( $content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
    
    //         $this->insertAdminActiveLog( $adminUsername, $logTitle, $content, 'ORDER' );
    //         $this->insertOrderLog($code, $content, $adminUsername);
    //         //INSERT ACTIVE LOG
    //     }

    //     $outputArray = array( 'status' => $statusCode, 'message' => $message );
    //     outputJson( $outputArray );
    // }

    public function updateOrder($code, $orderId, $adminUsername, $clientEmail, $clientName, $clientPhone, $resellerName, $fromPlatform, $trackingLink, $billingFullAddress, $shippingFullAddress, $getOrderDetail, $totalAmount, $orderRemark, $orderStatus, $paymentProofPath, $hasNewFile){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        $getOrderDetail2 = str_replace('\\', '', $getOrderDetail);
        $getOrderDetail2 = json_decode($getOrderDetail2, true);

        date_default_timezone_set( $GLOBAL_TIMEZONE );
        $dateTime = date( 'Y-m-d H:i:s' );

        $currentTimestamp = currentTimestamp();

        $resellerNameParam = ($resellerName === '' || $resellerName === "Select") ? null : $resellerName;

        if($hasNewFile == 'true'){
            // SQL query to update the order, including the new payment_proof column
            $updateInfo = "UPDATE order_list SET payment_proof = ? WHERE order_db_code = ?";
            $stmt = $dbConnection->prepare($updateInfo);

            $stmt->bind_param(
                "ss", 
                $paymentProofPath,
                $code
            );
            $status = $stmt->execute();
            $stmt->close();

        }
        // SQL query to update the order, including the new payment_proof column
        $updateInfo = "UPDATE order_list SET total_amount = ?, order_details = ?, billing_address = ?, shipping_address = ?, client_email = ?, client_phone = ?, client_name = ?, reseller_db_code = ?, updated_datetime = ?, updated_timestamp = ?, remark = ?, status = ?, tracking_link = ? WHERE order_db_code = ?";
        $stmt = $dbConnection->prepare($updateInfo);
        $status = false;

        if ($stmt) {
            $stmt->bind_param(
                "dssssssssissss", // Correct types: 'd' for double, 's' for strings
                $totalAmount,
                $getOrderDetail,
                $billingFullAddress,
                $shippingFullAddress,
                $clientEmail,
                $clientPhone,
                $clientName,
                $resellerNameParam,
                $dateTime,
                $currentTimestamp,
                $orderRemark,
                $orderStatus,
                $trackingLink,
                $code
            );
            $status = $stmt->execute();
            $stmt->close();
        } else {
            error_log("Failed to prepare updateOrder statement: " . $dbConnection->error);
        }

        $statusCode = $status ? 200 : 400;
        $message = $status ? 'Updated order' : 'Failed to update order';

        if($statusCode == 200){
            //INSERT ACTIVE LOG
            $logTitle = "Order #$orderId Update";
            $content = array('order_id'  => $orderId, 'client_email'  => $clientEmail, 'client_phone'  => $clientPhone, 'client_name'  => $clientName, 'total_amount' => $totalAmount, 'order_details'  => $getOrderDetail2, 'billing_address'  => $billingFullAddress, 'shipping_address'  => $shippingFullAddress, 'remark'  => $orderRemark, 'status'  => $orderStatus, 'tracking_link'  => $trackingLink);
            $content = json_encode( $content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
    
            $this->insertAdminActiveLog( $adminUsername, $logTitle, $content, 'ORDER' );
            $this->insertOrderLog($code, $content, $adminUsername);
            //INSERT ACTIVE LOG
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $message );
        outputJson( $outputArray );
    }

    public function updateSerialNumber($adminUsername, $serialNumberList){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        date_default_timezone_set( $GLOBAL_TIMEZONE );

        $serialNumberList2 = str_replace('\\', '', $serialNumberList);
        $serialNumberList2 = json_decode($serialNumberList2, true);

        foreach ( $serialNumberList2 as $item ) {
            mysqli_begin_transaction($dbConnection);
            try {
                $orderId = $item['order_id'];
                $orderDbCode = $item['order_db_code'];
                $deviceType = $item['device_type'];
                $serialNumber = $item['serial_number'];
                
                // Check if serial number already exists in order_device_serial_number
                $getInfo = "SELECT id FROM order_device_serial_number WHERE serial_number = ?";
                $stmtCheck = $dbConnection->prepare( $getInfo );
                $totalCount1 = 0;

                if ($stmtCheck) {
                    $stmtCheck->bind_param("s", $serialNumber);
                    $stmtCheck->execute();
                    $resultCheck = $stmtCheck->get_result();
                    $totalCount1 = $resultCheck->num_rows;
                    $stmtCheck->close();
                } else {
                    throw new Exception("Failed to prepare check serial number statement: " . $dbConnection->error);
                }
    
                if($totalCount1 == 0){
                    $dateTime = date( 'Y-m-d H:i:s' );
            
                    // Insert into order_device_serial_number
                    $insertRecord = 'INSERT INTO order_device_serial_number (order_db_code, device_type, serial_number, added_by, created_datetime) VALUES (?, ?, ?, ?, ?)';
                    $stmtInsert = $dbConnection->prepare( $insertRecord );
                    if ($stmtInsert) {
                        $stmtInsert->bind_param("sssss", $orderDbCode, $deviceType, $serialNumber, $adminUsername, $dateTime);
                        if (!$stmtInsert->execute()) {
                            throw new Exception("Failed to insert into order_device_serial_number: " . $stmtInsert->error);
                        }
                        $stmtInsert->close();
                    } else {
                        throw new Exception("Failed to prepare insert order_device_serial_number statement: " . $dbConnection->error);
                    }
    
                    // Update library_device
                    $updateInfo = "UPDATE library_device SET order_db_code = ?, order_id = ? WHERE serial_number = ?";
                    $stmtUpdateDevice = $dbConnection->prepare($updateInfo);
                    if ($stmtUpdateDevice) {
                        $stmtUpdateDevice->bind_param("sss", $orderDbCode, $orderId, $serialNumber);
                        if (!$stmtUpdateDevice->execute()) {
                            throw new Exception("Failed to update library_device: " . $stmtUpdateDevice->error);
                        }
                        $stmtUpdateDevice->close();
                    } else {
                        throw new Exception("Failed to prepare update library_device statement: " . $dbConnection->error);
                    }

                    mysqli_commit($dbConnection);
                } else {
                    // If serial number already exists, just commit (no change, but not an error)
                    mysqli_commit($dbConnection);
                }

            } catch(Exception $e){
                mysqli_rollback($dbConnection);
                $outputArray = array( 'status' => 400, 'message' => "Transaction error for serial number $serialNumber: " . $e->getMessage() );
                outputJson( $outputArray );
                return; // Exit on first error in the loop
            }
        }

        $outputArray = array( 'status' => 200, 'message' => 'Updated serial number list' );
        outputJson( $outputArray );
    }

    public function initRecentOrder(){
        global $dbConnection;

        $infoArray = array();

        $getAllInfo = "SELECT total_amount, order_details, client_phone, client_name, created_datetime FROM order_list WHERE soft_delete = 'false' ORDER BY id DESC LIMIT 50";
        $stmt = $dbConnection->prepare( $getAllInfo );

        if ( $stmt ) {
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            while ( $row = mysqli_fetch_assoc( $result ) ) {
                $row['order_details'] = json_decode($row[ 'order_details' ], true );
                $infoArray[] = $row;
            }

            $statusCode = 200;
            $statusMessage = 'Loaded info';
        } else {
            error_log("Failed to prepare initRecentOrder statement: " . $dbConnection->error);
            $statusCode = 400;
            $statusMessage = 'Failed to load info';
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
    }
}