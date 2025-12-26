<?php include_once 'media_api.php';?>
<?php

if (!isset($_FILES['media']) || !isset($_POST['mediaPath'])) {
    $outputArray = array('status' => 440, 'message' => "Parameters error");
    outputJson($outputArray);
    return;
}

$media          = $_FILES['media'];

$mediaPath      = escape($_POST["mediaPath"]);

$method = new Media();
$method -> saveMediaFile($media, $mediaPath);

?>