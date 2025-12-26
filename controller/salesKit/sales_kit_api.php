<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/functions.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/config.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/constants.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/admin/admin_api.php';

class SalesKit extends Admin {

    public function verifySeCode($seCode){
        global $dbConnection;

        $salesKitCode = "";
        $status = false;
        
        $getInfo = "SELECT sales_kit_code 
                    FROM library_sales_executive 
                    WHERE UPPER(sales_code) = UPPER(?)";
        $stmt = $dbConnection->prepare($getInfo);

        if ($stmt) {
            $stmt->bind_param("s", $seCode);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $status = true;

            if ($row = $result->fetch_assoc()) {
                $salesKitCode = $row['sales_kit_code'];
            }
        }

        $statusCode = $status ? 200 : 400;
        $message = $status ? 'Loaded info' : 'Failed to load info';

        $outputArray = array( 'status' => $statusCode, 'message' => $message, 'data' => array('sales_kit_code' => $salesKitCode) );
        outputJson( $outputArray );
    }

}

?>