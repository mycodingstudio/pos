
<?php
include_once $_SERVER[ 'DOCUMENT_ROOT' ] . '/controller/admin/admin_api.php';
if ($_FILES['file']) {

    $file = $_FILES['file'];
    $fileName = $file['name'];
    $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);

    // Generate a unique name and append the file extension
    $pathName = getNewTokenWithTimestamp() . '.' . $fileExt;

    move_uploaded_file($_FILES['file']['tmp_name'], '../../upload/' . $pathName);
    $outputArray = array( 'status' => 200, 'message' => 'Done', 'data' => array('image_path' => $pathName) );
    outputJson( $outputArray );
}
?>
