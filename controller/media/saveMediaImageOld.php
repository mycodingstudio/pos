<?php include_once 'media_api.php';?>
<?php

if (!isset($_POST['imageBase64']) || !isset($_POST['deviceId'])) {
    $outputArray = array('status' => 440, 'message' => "Parameters error");
    outputJson($outputArray);
    return;
}

$imageBase64    = escape($_POST["imageBase64"]);
$deviceId       = escape($_POST["deviceId"]);
$mediaPath      = "images";

$method = new Media();
$method -> saveMediaImage($imageBase64, $deviceId, $deviceId);


?>