<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/functions.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/includes/config.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/vendor/autoload.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/constants.php';
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/admin/admin_api.php';

class Review extends Admin {


    public function getAllReviewList($salesCode){
        global $dbConnection;

        $infoArray = array();

        $getAllInfo = "SELECT * FROM sales_executive_review WHERE sales_executive_code = '$salesCode' ORDER BY id DESC";
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
            error_log("Failed to prepare getAllOrderList statement: " . $dbConnection->error);
            $statusCode = 400;
            $statusMessage = 'Failed to load info';
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
    }

}