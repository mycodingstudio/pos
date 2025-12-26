<?php include_once("sales_kit_api.php"); ?>

<?php

if (!isset($_POST['seCode'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}


$seCode              = $_POST["seCode"];

$method = new SalesKit();
$method -> verifySeCode($seCode);


?>