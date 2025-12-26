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

        // Use prepared statement for SELECT
        $getAllInfo = "SELECT media_link FROM library_material WHERE material_type = ? ORDER BY id DESC";
        $stmt = $dbConnection->prepare( $getAllInfo );

        if ( $stmt ) {
            $stmt->bind_param("s", $type); // Bind the material_type
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            while ( $row = mysqli_fetch_assoc( $result ) ) {
                $infoArray[] = $row['media_link'];
            }

            $statusCode = 200; // If query prepared and executed, it's a success regardless of rows
            $statusMessage = 'Loaded info';
        } else {
            error_log("Failed to prepare getAllMaterial statement: " . $dbConnection->error);
            $statusCode = 400;
            $statusMessage = 'Failed to load info';
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $statusMessage, 'data' => $infoArray );
        outputJson( $outputArray );
    }

    public function updateImageList($accessUsername, $materialType, $imageList){
        global $dbConnection;
        global $GLOBAL_TIMEZONE;

        $imageList2 = str_replace('\\', '', $imageList);
        $imageList2 = json_decode($imageList2, true);

        $dbImageList = array();

        // Use prepared statement for fetching existing images
        $getAllImages = "SELECT media_link FROM library_material WHERE material_type = ?";
        $stmt = $dbConnection->prepare( $getAllImages );

        if ( $stmt ) {
            $stmt->bind_param("s", $materialType);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            while ( $row = mysqli_fetch_assoc( $result ) ) {
                $dbImageList[] = $row['media_link'];
            }
        } else {
            error_log("Failed to prepare getAllImages statement in updateImageList: " . $dbConnection->error);
            $outputArray = array( 'status' => 400, 'message' => 'Failed to retrieve existing materials.' );
            outputJson( $outputArray );
            return;
        }

        $deleteImageList = array();
        $newImageList = array();

        foreach ( $imageList2 as $mediaLink ) {
            if (!in_array($mediaLink, $dbImageList)) { 
                $newImageList[] = $mediaLink;
            }
        }

        foreach ( $dbImageList as $mediaLink ) {
            if (!in_array($mediaLink, $imageList2)) { 
                $deleteImageList[] = $mediaLink;
            }
        }

        mysqli_begin_transaction($dbConnection);
        $allOperationsStatus = true; // Track overall success of DML operations

        try {
            // Use prepared statement for DELETE operations
            if (!empty($deleteImageList)) {
                $deleteInfo = "DELETE FROM library_material WHERE media_link = ?";
                $stmtDelete = $dbConnection->prepare( $deleteInfo );
                if ($stmtDelete) {
                    foreach ( $deleteImageList as $mediaLink ) {
                        $stmtDelete->bind_param("s", $mediaLink);
                        if (!$stmtDelete->execute()) {
                            $allOperationsStatus = false;
                            error_log("Failed to delete material: " . $stmtDelete->error);
                            break; // Exit loop on first failure
                        }
                    }
                    $stmtDelete->close();
                } else {
                    $allOperationsStatus = false;
                    error_log("Failed to prepare DELETE statement in updateImageList: " . $dbConnection->error);
                }
            }

            // Use prepared statement for INSERT operations
            if (!empty($newImageList) && $allOperationsStatus) {
                date_default_timezone_set( $GLOBAL_TIMEZONE );
                $insertRecord = 'INSERT INTO library_material (material_type, media_link, created_datetime) VALUES (?, ?, ?)';
                $stmtInsert = $dbConnection->prepare( $insertRecord );
                if ($stmtInsert) {
                    foreach ( $newImageList as $mediaLink ) {
                        $dateTime = date( 'Y-m-d H:i:s' );
                        $stmtInsert->bind_param("sss", $materialType, $mediaLink, $dateTime);
                        if (!$stmtInsert->execute()) {
                            $allOperationsStatus = false;
                            error_log("Failed to insert new material: " . $stmtInsert->error);
                            break; // Exit loop on first failure
                        }
                    }
                    $stmtInsert->close();
                } else {
                    $allOperationsStatus = false;
                    error_log("Failed to prepare INSERT statement in updateImageList: " . $dbConnection->error);
                }
            }

            if ($allOperationsStatus) {
                mysqli_commit($dbConnection);
                $statusCode = 200;
                $message = 'Updated materials';
                //INSERT ACTIVE LOG
                $logTitle = "Material Update";
                $content = "$accessUsername updated $materialType materials";
                $this->insertAdminActiveLog( $accessUsername, $logTitle, $content, 'MATERIAL' );
                //INSERT ACTIVE LOG
            } else {
                mysqli_rollback($dbConnection);
                $statusCode = 400;
                $message = 'Failed to update materials due to a database error.';
            }

        } catch (Exception $e) {
            mysqli_rollback($dbConnection);
            $statusCode = 400;
            $message = 'Transaction error: ' . $e->getMessage();
            error_log("Exception in updateImageList transaction: " . $e->getMessage());
        }

        $outputArray = array( 'status' => $statusCode, 'message' => $message );
        outputJson( $outputArray );
    }
}