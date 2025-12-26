<?php include_once("admin_api.php"); ?>

<?php
$outputArray = array();
if (!isset($_POST['adminUsername']) || !isset($_POST['adminAccesstoken']) || !isset($_POST['code']) || !isset($_POST['adminName'])) {
    $outputArray = array('status' => 440, 'message' => "Wrong parameters");
    outputJson($outputArray);
    return;
}

$adminUsername      = escape($_POST["adminUsername"]);
$adminAccesstoken   = escape($_POST["adminAccesstoken"]);
$code               = escape($_POST["code"]);
$adminName          = escape($_POST["adminName"]);

$method = new Admin();
$isAdminAccessTokenValid = $method  -> isAdminAccessTokenValid($adminUsername, $adminAccesstoken);

if($isAdminAccessTokenValid){
    $method -> deleteAdmin($adminUsername, $code, $adminName);
}else{
    $outputArray = array('status' => 440, 'message' => "Expired token");
    outputJson($outputArray);
    return;
}

?>