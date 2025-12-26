<?php include_once("order_api.php"); ?>

<?php

if (!isset($_POST['orderId']) || !isset($_POST['salesCode']) || !isset($_POST['itemList']) ) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}


$orderId              = $_POST["orderId"];
$salesCode            = $_POST["salesCode"];
$itemList              = $_POST["itemList"];


$method = new Order();
$method -> insertOrderItems($orderId, $salesCode, $itemList);


?>