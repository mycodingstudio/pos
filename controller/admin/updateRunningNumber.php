<?php include_once("admin_api.php"); ?>

<?php


        error_reporting(E_ALL);
ini_set('display_errors', 1);


$outputArray = array();
if (!isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['deviceCode']) || !isset($_POST['fromNumber']) || !isset($_POST['toNumber'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

$adminUsername      = escape($_POST["adminUsername"]);
$adminAccesstoken   = escape($_POST["adminAccesstoken"]);
$deviceCode         = escape($_POST["deviceCode"]);
$fromNumber         = escape($_POST["fromNumber"]);
$toNumber           = escape($_POST["toNumber"]);

$method = new Admin();
$isAdminAccessTokenValid = $method  -> isAdminAccessTokenValid($adminUsername, $adminAccesstoken);

if($isAdminAccessTokenValid){
    $method -> updateRunningNumber($adminUsername, $deviceCode, $fromNumber, $toNumber);
}else{
    $outputArray = array('status' => 440, 'message' => "Expired token");
    outputJson($outputArray);
    return;
}

?>