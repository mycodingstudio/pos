<?php include_once("setting_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

$adminUsername      = escape($_POST["adminUsername"]);
$adminAccesstoken   = escape($_POST["adminAccesstoken"]);

$method = new Setting();
$isAdminAccessTokenValid = $method  -> isAdminAccessTokenValid($adminUsername, $adminAccesstoken);

if($isAdminAccessTokenValid){
    $method -> loadSettingInfo();
}else{
    $outputArray = array('status' => 440, 'message' => "Expired token");
    outputJson($outputArray);
    return;
}

?>