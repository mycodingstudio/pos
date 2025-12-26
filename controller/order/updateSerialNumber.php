<?php include_once("order_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['serialNumberList'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

$adminUsername          = escape($_POST["adminUsername"]);
$adminAccesstoken       = escape($_POST["adminAccesstoken"]);
$serialNumberList       = escape($_POST["serialNumberList"]);

$method = new Order();
$isAdminAccessTokenValid = $method  -> isAdminAccessTokenValid($adminUsername, $adminAccesstoken);

if($isAdminAccessTokenValid){
    $method -> updateSerialNumber($adminUsername, $serialNumberList);
}else{
    $outputArray = array('status' => 440, 'message' => "Expired token");
    outputJson($outputArray);
    return;
}

?>